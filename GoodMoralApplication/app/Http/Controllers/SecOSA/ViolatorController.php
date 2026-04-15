<?php

namespace App\Http\Controllers\SecOSA;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreViolatorRequest;
use App\Traits\ViolationEscalationTrait;
use App\Traits\RoleCheck;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Violation;
use App\Models\StudentViolation;
use App\Helpers\CourseHelper;
use App\Services\ViolationService;

class ViolatorController extends Controller
{
    use ViolationEscalationTrait, RoleCheck;

    public function __construct(
        private ViolationService $violationService
    ) {
        $this->checkRole(['sec_osa']);
    }

    private function generateReferenceNumber(): string
    {
        $year = date('Y');
        $prefix = "VIO-{$year}-";

        $latest = StudentViolation::where('ref_num', 'LIKE', "{$prefix}%")
            ->orderByRaw('CAST(SUBSTRING(ref_num, ' . (strlen($prefix) + 1) . ') AS UNSIGNED) DESC')
            ->value('ref_num');

        if ($latest) {
            $lastSequence = (int) substr($latest, strlen($prefix));
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
    }

    public function addViolatorForm()
    {
        $violations = Violation::active()->get();
        $coursesByDepartment = CourseHelper::getCoursesByDepartment();

        return view('sec_osa.add-violator', compact('violations', 'coursesByDepartment'));
    }

    public function storeViolator(StoreViolatorRequest $request)
    {
        $validated = $request->validated();

        $uniqueID = uniqid();
        $referenceNumber = $this->generateReferenceNumber();
        $userName = Auth::user()->fullname ?? 'Moderator';

        $violation = StudentViolation::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'student_id' => $validated['student_id'],
            'department' => $validated['department'],
            'course' => $validated['course'],
            'offense_type' => $validated['offense_type'],
            'violation' => $validated['violation'],
            'ref_num' => $referenceNumber,
            'added_by' => $userName,
            'status' => '0',
            'unique_id' => $uniqueID,
            'case_type' => 'single',
            'group_size' => 1,
        ]);

        try {
            $this->violationService->createViolationNotification($violation);
        } catch (\Exception $e) {
            Log::error('Failed to create violation notification', [
                'violation_id' => $violation->id,
                'error' => $e->getMessage(),
            ]);
        }

        $successMessage = 'Violator added successfully!';

        if ($violation->offense_type === 'minor') {
            $escalated = $this->checkMinorViolationEscalation($validated['student_id']);
            if ($escalated) {
                $successMessage = 'Violator added successfully! 🚨 AUTOMATIC ESCALATION: This student now has 3 minor violations. A MAJOR VIOLATION has been automatically created and all admins have been notified.';
            }
        }

        return redirect()->route('sec_osa.addViolator')->with('success', $successMessage);
    }
}
