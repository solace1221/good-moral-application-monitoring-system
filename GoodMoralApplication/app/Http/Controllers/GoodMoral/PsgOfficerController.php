<?php

namespace App\Http\Controllers\GoodMoral;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\GoodMoralApplication;
use App\Models\StudentViolation;
use App\Models\RoleAccount;
use App\Models\StudentRegistration;
use App\Models\Department;
use App\Helpers\CourseHelper;
use App\Http\Requests\ApplyGoodMoralRequest;

class PsgOfficerController extends Controller
{
    public function __construct()
    {
        // Constructor - authorization will be handled in individual methods
    }

    /**
     * PSG Officer dashboard - redirects to applications page (task-based module)
     */
    public function dashboard()
    {
        return redirect()->route('PsgOfficer.applications');
    }

    /**
     * Show good moral application form for PSG officers
     */
    public function showGoodMoralForm()
    {
        // Check if user is PSG officer
        if (!Auth::check() || Auth::user()->account_type !== 'psg_officer') {
            abort(403, 'Unauthorized access. PSG Officer access required.');
        }

        $user = Auth::user();
        $student = RoleAccount::where('email', $user->email)->first();
        
        if (!$student) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'PSG Officer record not found. Please contact the administrator.');
        }

        $studentId = $student->student_id;
        
        // Get violations against this PSG officer
        $violations = StudentViolation::where('student_id', $studentId)
            ->where('status', '!=', 2)
            ->get();

        // Determine available certificate types
        $availableCertificates = $this->getAvailableCertificates($violations);

        // Get PSG officer's course and year level (static, not changeable)
        $studentCourse = $student->course;
        $studentCourseName = $studentCourse ? CourseHelper::getCourseName($studentCourse) : null;
        $studentYearLevel = $student->year_level;

        return view('PsgOfficer.good-moral-form', compact(
            'violations', 
            'availableCertificates', 
            'studentCourse', 
            'studentCourseName', 
            'studentYearLevel'
        ));
    }

    /**
     * Process good moral application for PSG officers
     */
    public function applyForGoodMoral(ApplyGoodMoralRequest $request): RedirectResponse
    {
        // Check if user is PSG officer
        if (!Auth::check() || Auth::user()->account_type !== 'psg_officer') {
            abort(403, 'Unauthorized access. PSG Officer access required.');
        }

        $user = Auth::user();
        
        // Generate reference number
        $prefix = 'REF';
        $timestamp = time();
        $randomString = Str::upper(Str::random(6));
        $referenceNumber = $prefix . '-' . $timestamp . '-' . $randomString;

        // Get PSG officer's information
        $student = RoleAccount::where('email', $user->email)->first();
        
        if (!$student) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'PSG Officer record not found. Please contact the administrator.');
        }

        $studentId = $student->student_id;
        $fullname = $student->fullname;
        $studentDepartment = $student->department;

        // Process reasons
        $selectedReason = $request->reason;
        if (in_array('Others', $selectedReason) && $request->reason_other) {
            $selectedReason = array_filter($selectedReason, fn($r) => $r !== 'Others');
            $selectedReason[] = $request->reason_other;
        }

        // Get gender from user profile
        $userGender = Auth::user()->gender ?? 'male'; // Default to male if not set

        // Save the application
        GoodMoralApplication::create([
            'number_of_copies' => $request->num_copies,
            'reference_number' => $referenceNumber,
            'fullname' => $fullname,
            'gender' => $userGender, // Get gender from user profile
            'reason' => $selectedReason,
            'student_id' => $studentId,
            'department' => $studentDepartment,
            'department_id' => Department::findIdByCode($studentDepartment),
            'course_completed' => $request->course_completed ?? null,
            'graduation_date' => $request->graduation_date ?? null,
            'application_status' => null,
            'is_undergraduate' => $request->is_undergraduate === 'yes',
            'last_course_year_level' => $request->last_course_year_level ?? null,
            'last_semester_sy' => $request->last_semester_sy ?? null,
            'certificate_type' => $request->certificate_type,
        ]);

        return redirect()->route('PsgOfficer.dashboard')
            ->with('success', 'Good Moral Certificate application submitted successfully! Reference Number: ' . $referenceNumber);
    }

    /**
     * Show PSG officer's personal violations
     */
    public function showPersonalViolations()
    {
        // Check if user is PSG officer
        if (!Auth::check() || Auth::user()->account_type !== 'psg_officer') {
            abort(403, 'Unauthorized access. PSG Officer access required.');
        }

        $user = Auth::user();
        $student = RoleAccount::where('email', $user->email)->first();
        
        if (!$student) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'PSG Officer record not found. Please contact the administrator.');
        }

        $studentId = $student->student_id;
        
        // Get all violations against this PSG officer
        $violations = StudentViolation::where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('PsgOfficer.personal-violations', compact('violations'));
    }

    /**
     * Show PSG officer's good moral applications
     */
    public function showApplications()
    {
        // Check if user is PSG officer
        if (!Auth::check() || Auth::user()->account_type !== 'psg_officer') {
            abort(403, 'Unauthorized access. PSG Officer access required.');
        }

        $user = Auth::user();
        $student = RoleAccount::where('email', $user->email)->first();
        
        if (!$student) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'PSG Officer record not found. Please contact the administrator.');
        }

        $studentId = $student->student_id;
        
        // Get all applications by this PSG officer
        $applications = GoodMoralApplication::where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('PsgOfficer.applications', compact('applications'));
    }

    /**
     * Determine which certificate types are available for the PSG officer
     */
    private function getAvailableCertificates($violations)
    {
        $certificates = [];

        if ($violations->isEmpty()) {
            // PSG officers with no violations can apply for Good Moral Certificate
            $certificates[] = [
                'type' => 'good_moral',
                'name' => 'Good Moral Certificate',
                'description' => 'Certificate of good moral character for PSG officers'
            ];
        } else {
            // PSG officers with violations can apply for Certificate of Residency
            $certificates[] = [
                'type' => 'residency',
                'name' => 'Certificate of Residency',
                'description' => 'Certificate confirming residency/attendance at the institution'
            ];
        }

        return $certificates;
    }
}
