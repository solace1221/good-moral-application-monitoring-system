<?php

namespace App\Http\Requests;

use App\Helpers\CourseHelper;
use Illuminate\Foundation\Http\FormRequest;

class ApplyGoodMoralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $validCourses = array_keys(CourseHelper::getAllCourses());
        $validSemesters = ['First Semester', 'Second Semester', 'Summer Term'];
        $accountType = $this->user()?->account_type;

        $rules = [
            'num_copies' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'array', 'min:1'],
            'reason.*' => ['required', 'string', 'max:255'],
            'reason_other' => ['nullable', 'string', 'max:255'],
            'is_undergraduate' => ['nullable', 'in:yes,no'],
            'certificate_type' => ['required', 'in:good_moral,residency'],
        ];

        if ($accountType === 'student' || $accountType === 'psg_officer') {
            $rules['last_course_year_level'] = ['nullable', 'string', 'max:50'];
            $rules['last_semester'] = ['required', 'string', 'in:' . implode(',', $validSemesters)];
            $rules['last_school_year'] = ['required', 'string', 'regex:/^\d{4}-\d{4}$/'];
        } elseif ($accountType === 'alumni') {
            $rules['course_completed'] = ['nullable', 'string', 'max:50'];
            $rules['graduation_date'] = ['required', 'date'];
        } else {
            $rules['last_course_year_level'] = ['nullable', 'string', 'max:50'];
            $rules['last_semester'] = ['nullable', 'string', 'in:' . implode(',', $validSemesters)];
            $rules['last_school_year'] = ['nullable', 'string', 'regex:/^\d{4}-\d{4}$/'];
            $rules['course_completed'] = ['nullable', 'string', 'max:50'];
            $rules['graduation_date'] = ['nullable', 'date'];
        }

        return $rules;
    }
}
