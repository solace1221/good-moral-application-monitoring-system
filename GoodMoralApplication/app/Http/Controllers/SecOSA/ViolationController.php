<?php

namespace App\Http\Controllers\SecOSA;

use App\Http\Controllers\Controller;
use App\Models\RoleAccount;
use App\Models\StudentViolation;
use App\Models\ViolationNotif;
use App\Services\ViolationService;
use App\Traits\RoleCheck;
use App\Http\Requests\UploadProceedingsRequest;
use App\Http\Requests\UploadViolationDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ViolationController extends Controller
{
    use RoleCheck;

    protected ViolationService $violationService;

    public function __construct(ViolationService $violationService)
    {
        $this->violationService = $violationService;
        $this->checkRole(['sec_osa']);
    }

    public function major()
    {
        $students = StudentViolation::with('studentAccount')
            ->major()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $statusCounts = $this->violationService->getMajorStatusCounts();
        extract($statusCounts);

        return view('sec_osa.major', compact('students', 'pendingCount', 'proceedingsUploadedCount', 'forwardedCount', 'closedCount'));
    }

    /**
     * Show form to upload proceedings for a specific major violation
     */
    public function showUploadProceedings($id)
    {
        $violation = StudentViolation::findOrFail($id);

        if ($violation->offense_type !== 'major' || $violation->status !== '0') {
            return redirect()->route('sec_osa.major')
                ->with('error', 'This violation is not eligible for proceedings upload.');
        }

        return view('sec_osa.upload_proceedings', compact('violation'));
    }

    /**
     * Upload proceedings document for major violation
     */
    public function uploadProceedings(UploadProceedingsRequest $request, $id)
    {
        $violation = StudentViolation::findOrFail($id);

        $file = $request->file('proceedings_document');
        $filename = 'proceedings_' . $violation->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('violation_proceedings', $filename, 'public');

        if (!$violation->ref_num) {
            $violation->ref_num = ViolationService::generateCaseNumber();
        }

        $violation->document_path = $path;
        $violation->status = '1';
        $violation->meeting_date = $request->meeting_date;
        $violation->meeting_notes = $request->meeting_notes;
        $violation->proceedings_uploaded_by = Auth::user()->fullname;
        $violation->proceedings_uploaded_at = now();
        $violation->save();

        ViolationNotif::create([
            'ref_num' => $violation->ref_num,
            'student_id' => $violation->student_id,
            'status' => 0,
            'notif' => "Meeting proceedings for your major violation have been uploaded by the Moderator. Case Number: {$violation->ref_num}. The case is now under review.",
        ]);

        $progCoordinators = RoleAccount::where('account_type', 'prog_coor')
            ->where('department', $violation->department)
            ->get();

        foreach ($progCoordinators as $coordinator) {
            if ($coordinator->student_id) {
                ViolationNotif::create([
                    'ref_num' => $violation->ref_num,
                    'student_id' => $coordinator->student_id,
                    'status' => 0,
                    'notif' => "Major violation proceedings uploaded for student {$violation->first_name} {$violation->last_name} ({$violation->student_id}) from your department. Case: {$violation->ref_num}.",
                ]);
            }
        }

        return redirect()->route('sec_osa.major')
            ->with('success', "Proceedings uploaded successfully! Case Number: {$violation->ref_num}");
    }

    /**
     * Download proceedings document uploaded by moderator
     */
    public function downloadProceedings($id)
    {
        $violation = StudentViolation::findOrFail($id);

        if (!$violation->document_path || !Storage::disk('public')->exists($violation->document_path)) {
            return redirect()->back()->with('error', 'Proceedings document not found.');
        }

        if (!str_contains($violation->document_path, 'violation_proceedings/')) {
            return redirect()->back()->with('error', 'Invalid proceedings document.');
        }

        return response()->download(Storage::disk('public')->path($violation->document_path));
    }

    /**
     * Forward major violation to admin for case closure
     */
    public function forwardToAdmin($id)
    {
        $violation = StudentViolation::findOrFail($id);

        if ($violation->offense_type !== 'major' || $violation->status !== '1' || !$violation->document_path) {
            return redirect()->route('sec_osa.major')
                ->with('error', 'This violation is not eligible for forwarding to admin.');
        }

        $violation->status = '1.5';
        $violation->forwarded_to_admin_at = now();
        $violation->forwarded_by = Auth::user()->fullname;
        $violation->save();

        $admins = RoleAccount::where('account_type', 'admin')->get();
        foreach ($admins as $admin) {
            if ($admin->student_id) {
                ViolationNotif::create([
                    'ref_num' => $violation->ref_num,
                    'student_id' => $admin->student_id,
                    'status' => 0,
                    'notif' => "Major violation case forwarded by Moderator for closure. Case: {$violation->ref_num}. Student: {$violation->first_name} {$violation->last_name} ({$violation->student_id}). Please review proceedings and close the case.",
                ]);
            }
        }

        ViolationNotif::create([
            'ref_num' => $violation->ref_num,
            'student_id' => $violation->student_id,
            'status' => 0,
            'notif' => "Your major violation case has been forwarded to the Administrator for final review and closure. Case Number: {$violation->ref_num}.",
        ]);

        return redirect()->route('sec_osa.major')
            ->with('success', "Case {$violation->ref_num} has been forwarded to Admin for closure.");
    }

    public function minor()
    {
        $students = StudentViolation::with('studentAccount')
            ->minor()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $statusCounts = $this->violationService->getMinorStatusCounts();
        extract($statusCounts);

        return view('sec_osa.minor', compact('students', 'pendingCount', 'approvedCount', 'closedCount'));
    }

    public function uploadDocument(UploadViolationDocumentRequest $request, $id)
    {
        $violation = StudentViolation::findOrFail($id);

        $path = $request->file('document')->store('violations_documents', 'public');

        $caseNumber = ViolationService::generateCaseNumber();

        $violation->document_path = $path;
        $violation->ref_num = $caseNumber;
        $violation->status = "1";
        $violation->save();

        ViolationNotif::create([
            'ref_num' => $caseNumber,
            'student_id' => $violation->student_id,
            'status' => 0,
            'notif' => "Uploaded the proceedings with case number: $caseNumber",
        ]);

        return back()->with('success', "Document uploaded successfully! Case No: {$caseNumber}");
    }

    public function searchMinor(Request $request)
    {
        $query = StudentViolation::minor();

        if ($request->filled('ref_num')) {
            $query->where('ref_num', 'like', '%' . $request->ref_num . '%');
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', 'like', '%' . $request->student_id . '%');
        }

        if ($request->filled('last_name')) {
            $query->where('last_name', 'like', '%' . $request->last_name . '%');
        }

        if ($request->filled('first_name')) {
            $query->where('first_name', 'like', '%' . $request->first_name . '%');
        }

        if ($request->filled('department') && $request->department !== '') {
            $query->where('department', $request->department);
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->filled('added_by')) {
            $query->where('added_by', 'like', '%' . $request->added_by . '%');
        }

        if ($request->filled('violation_count') && $request->violation_count !== '') {
            $countFilter = $request->violation_count;

            if ($countFilter === '1') {
                $query->whereIn('student_id', function ($subquery) {
                    $subquery->select('student_id')
                        ->from('student_violations')
                        ->where('offense_type', 'minor')
                        ->groupBy('student_id')
                        ->havingRaw('COUNT(*) = 1');
                });
            } elseif ($countFilter === '2-3') {
                $query->whereIn('student_id', function ($subquery) {
                    $subquery->select('student_id')
                        ->from('student_violations')
                        ->where('offense_type', 'minor')
                        ->groupBy('student_id')
                        ->havingRaw('COUNT(*) BETWEEN 2 AND 3');
                });
            } elseif ($countFilter === '4+') {
                $query->whereIn('student_id', function ($subquery) {
                    $subquery->select('student_id')
                        ->from('student_violations')
                        ->where('offense_type', 'minor')
                        ->groupBy('student_id')
                        ->havingRaw('COUNT(*) >= 4');
                });
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(10);

        $statusCounts = $this->violationService->getMinorStatusCounts();
        extract($statusCounts);

        return view('sec_osa.minor', compact('students', 'pendingCount', 'approvedCount', 'closedCount'));
    }

    public function searchMajor(Request $request)
    {
        $query = StudentViolation::major();

        if ($request->filled('first_name')) {
            $query->where('first_name', 'like', '%' . $request->first_name . '%');
        }

        if ($request->filled('last_name')) {
            $query->where('last_name', 'like', '%' . $request->last_name . '%');
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', 'like', '%' . $request->student_id . '%');
        }

        if ($request->filled('department') && $request->department !== '') {
            $query->where('department', $request->department);
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->filled('has_proceedings') && $request->has_proceedings !== '') {
            if ($request->has_proceedings === 'yes') {
                $query->whereNotNull('document_path');
            } else {
                $query->whereNull('document_path');
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(10);

        $statusCounts = $this->violationService->getMajorStatusCounts();
        extract($statusCounts);

        return view('sec_osa.major', compact('students', 'pendingCount', 'proceedingsUploadedCount', 'forwardedCount', 'closedCount'));
    }

    /**
     * Show all violations view
     */
    public function violation()
    {
        $violations = StudentViolation::with('studentAccount')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('sec_osa.violations', compact('violations'));
    }

    /**
     * Show violations for a specific department
     */
    public function viewDepartmentViolations($department)
    {
        $violations = StudentViolation::with('studentAccount')
            ->where('department', $department)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('sec_osa.departmentViolations', compact('violations', 'department'));
    }

    /**
     * Show escalation notifications for students with 3 minor violations
     */
    public function escalationNotifications()
    {
        $escalationNotifications = $this->violationService->getEscalationNotificationsList();

        return view('sec_osa.escalationNotifications', compact('escalationNotifications'));
    }
}
