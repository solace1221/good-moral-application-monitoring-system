<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\RoleAccount;
use Illuminate\Support\Facades\Log;

class ConvertGraduatingStudentsToAlumni implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * This job runs daily to check for graduating students whose graduation date
     * has arrived and automatically converts them to alumni status.
     */
    public function handle(): void
    {
        Log::info('Starting automatic conversion of graduating students to alumni');

        // Find all graduating students whose graduation date is today or has passed
        $graduatingStudents = RoleAccount::where('account_type', 'student')
            ->where('is_graduating', true)
            ->whereNotNull('graduation_date')
            ->whereDate('graduation_date', '<=', now()->toDateString())
            ->whereNull('graduated_at')
            ->get();

        $convertedCount = 0;

        foreach ($graduatingStudents as $student) {
            try {
                // Convert to alumni
                $student->update([
                    'account_type' => 'alumni',
                    'graduated_at' => now(),
                ]);

                Log::info("Converted student to alumni", [
                    'student_id' => $student->student_id,
                    'name' => $student->fullname,
                    'graduation_date' => $student->graduation_date,
                    'converted_at' => $student->graduated_at
                ]);

                $convertedCount++;

            } catch (\Exception $e) {
                Log::error("Failed to convert student to alumni", [
                    'student_id' => $student->student_id,
                    'name' => $student->fullname,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info("Completed automatic alumni conversion", [
            'total_converted' => $convertedCount,
            'total_checked' => $graduatingStudents->count()
        ]);
    }
}
