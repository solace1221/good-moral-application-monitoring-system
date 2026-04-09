<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\StudentYearLevelHistory;
use App\Models\RoleAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreAcademicYearRequest;
use App\Http\Requests\PromoteStudentRequest;

class AcademicYearController extends Controller
{
    /**
     * Display academic year management dashboard
     */
    public function index()
    {
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();
        
        // Handle backward compatibility - populate year_name from academic_year if needed
        foreach ($academicYears as $year) {
            if (empty($year->year_name) && !empty($year->academic_year)) {
                $year->year_name = $year->academic_year;
            }
        }
        
        $currentYear = AcademicYear::where('is_current', true)->first();
        $promotionActive = $currentYear && $currentYear->year_level_promotion_active;
        
        // Get promotion statistics
        $totalStudents = RoleAccount::where('account_type', 'student')->where('status', '1')->count();
        $eligibleForPromotion = RoleAccount::where('account_type', 'student')
            ->where('status', '1')
            ->where('year_level', '<', 4)
            ->count();
        
        return view('admin.academic-year.index', compact(
            'academicYears', 
            'currentYear', 
            'promotionActive',
            'totalStudents',
            'eligibleForPromotion'
        ));
    }

    /**
     * Create new academic year
     */
    public function store(StoreAcademicYearRequest $request)
    {
        $academicYear = AcademicYear::create([
            'year_name' => $request->year_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notes' => $request->notes,
            'is_current' => false,
            'year_level_promotion_active' => false
        ]);

        return redirect()->route('admin.academic-year.index')
            ->with('success', 'Academic year created successfully.');
    }

    /**
     * Trigger new academic year and activate promotions
     */
    public function triggerNewYear(Request $request, $id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        $user = Auth::user();
        
        try {
            $academicYear->triggerNewAcademicYear($user->email ?? $user->name);
            
            return redirect()->route('admin.academic-year.index')
                ->with('success', 'New academic year triggered successfully! Year level promotions are now active.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.academic-year.index')
                ->with('error', 'Failed to trigger new academic year: ' . $e->getMessage());
        }
    }

    /**
     * Process automatic year level promotions
     */
    public function processPromotions(Request $request)
    {
        $currentYear = AcademicYear::where('is_current', true)->first();
        
        if (!$currentYear) {
            return redirect()->route('admin.academic-year.index')
                ->with('error', 'No current academic year set.');
        }

        if (!$currentYear->year_level_promotion_active) {
            return redirect()->route('admin.academic-year.index')
                ->with('error', 'Year level promotion is not active for the current academic year.');
        }

        try {
            $user = Auth::user();
            $promotedCount = $currentYear->processYearLevelPromotions($user->email ?? $user->name);
            
            return redirect()->route('admin.academic-year.index')
                ->with('success', "Successfully promoted {$promotedCount} students to the next year level.");
                
        } catch (\Exception $e) {
            return redirect()->route('admin.academic-year.index')
                ->with('error', 'Failed to process promotions: ' . $e->getMessage());
        }
    }

    /**
     * Manual student promotion
     */
    public function promoteStudent(PromoteStudentRequest $request)
    {

        $currentYear = AcademicYear::where('is_current', true)->first();
        if (!$currentYear) {
            return response()->json(['success' => false, 'message' => 'No current academic year set.']);
        }

        $student = RoleAccount::where('student_id', $request->student_id)->first();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found.']);
        }

        try {
            $oldYearLevel = $student->year_level;
            $newYearLevel = $request->new_year_level;
            $user = Auth::user();

            // Update student year level
            $student->update(['year_level' => $newYearLevel]);

            // Record in history
            StudentYearLevelHistory::create([
                'student_id' => $student->student_id,
                'academic_year_id' => $currentYear->id,
                'previous_year_level' => $oldYearLevel,
                'new_year_level' => $newYearLevel,
                'promotion_type' => 'manual',
                'reason' => $request->reason,
                'processed_by' => $user->email ?? $user->name,
                'effective_date' => now()
            ]);

            return response()->json([
                'success' => true, 
                'message' => "Student {$student->fullname} has been manually promoted from Year {$oldYearLevel} to Year {$newYearLevel}."
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to promote student: ' . $e->getMessage()]);
        }
    }

    /**
     * View year level history
     */
    public function history(Request $request)
    {
        $query = StudentYearLevelHistory::with(['academicYear', 'student']);

        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        $history = $query->orderBy('effective_date', 'desc')->paginate(20);
        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();

        return view('admin.academic-year.history', compact('history', 'academicYears'));
    }

    /**
     * Get student search results for manual promotion
     */
    public function searchStudents(Request $request)
    {
        $search = $request->get('search');
        
        $students = RoleAccount::where('account_type', 'student')
            ->where('status', '1')
            ->where(function($query) use ($search) {
                $query->where('student_id', 'like', "%{$search}%")
                      ->orWhere('fullname', 'like', "%{$search}%");
            })
            ->select('student_id', 'fullname', 'year_level', 'course', 'department')
            ->limit(10)
            ->get();

        return response()->json($students);
    }

    /**
     * Get active academic years for reports
     */
    public function getActiveYears()
    {
        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();

        return response()->json([
            'success' => true,
            'academic_years' => $academicYears->map(function($year) {
                return [
                    'id' => $year->id,
                    'year_name' => $year->year_name,
                    'is_current' => $year->is_current,
                    'start_date' => $year->start_date,
                    'end_date' => $year->end_date
                ];
            })
        ]);
    }
}
