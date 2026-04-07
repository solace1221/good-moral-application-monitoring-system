<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ConvertGraduatingStudentsToAlumni;
use App\Models\RoleAccount;

class TestGraduationConversion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'graduation:convert {--test : Run in test mode without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test or run the graduation to alumni conversion process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $testMode = $this->option('test');

        $this->info('=== Graduation to Alumni Conversion ===');
        $this->info($testMode ? 'Running in TEST mode (no changes will be made)' : 'Running in LIVE mode');
        $this->newLine();

        // Find graduating students
        $graduatingStudents = RoleAccount::where('account_type', 'student')
            ->where('is_graduating', true)
            ->whereNotNull('graduation_date')
            ->whereDate('graduation_date', '<=', now()->toDateString())
            ->whereNull('graduated_at')
            ->get();

        $this->info("Found {$graduatingStudents->count()} graduating students eligible for conversion:");
        $this->newLine();

        if ($graduatingStudents->isEmpty()) {
            $this->warn('No graduating students found who are eligible for conversion.');
            $this->info('Students are eligible when:');
            $this->info('- Account type is "student"');
            $this->info('- is_graduating is true');
            $this->info('- graduation_date is set and has passed');
            $this->info('- graduated_at is null (not already converted)');
            return 0;
        }

        // Display eligible students
        $headers = ['Student ID', 'Name', 'Department', 'Graduation Date', 'Days Since Graduation'];
        $rows = [];

        foreach ($graduatingStudents as $student) {
            $daysSince = now()->diffInDays($student->graduation_date, false);
            $rows[] = [
                $student->student_id,
                $student->fullname,
                $student->department,
                $student->graduation_date->format('M d, Y'),
                $daysSince >= 0 ? $daysSince . ' days ago' : 'Today'
            ];
        }

        $this->table($headers, $rows);
        $this->newLine();

        if ($testMode) {
            $this->info('TEST MODE: No changes were made.');
            $this->info('To actually convert these students, run: php artisan graduation:convert');
            return 0;
        }

        // Confirm before proceeding
        if (!$this->confirm('Do you want to proceed with converting these students to alumni?')) {
            $this->info('Conversion cancelled.');
            return 0;
        }

        // Run the conversion job
        $this->info('Starting conversion process...');
        $job = new ConvertGraduatingStudentsToAlumni();
        $job->handle();

        $this->info('✅ Conversion process completed!');
        $this->info('Check the logs for detailed results.');

        return 0;
    }
}
