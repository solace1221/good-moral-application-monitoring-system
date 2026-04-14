<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricalViolationTrend extends Model
{
    protected $table = 'historical_violation_trends';

    protected $fillable = [
        'department_id',
        'academic_year',
        'minor_count',
        'major_count',
        'population',
    ];

    /**
     * Get the department this trend record belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
