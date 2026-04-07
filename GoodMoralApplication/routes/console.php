<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\ConvertGraduatingStudentsToAlumni;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic conversion of graduating students to alumni
Schedule::job(new ConvertGraduatingStudentsToAlumni())
    ->daily()
    ->at('00:30')
    ->name('convert-graduating-students')
    ->description('Convert graduating students to alumni status on their graduation date');
