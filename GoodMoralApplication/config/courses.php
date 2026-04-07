<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SPUP Courses by Department
    |--------------------------------------------------------------------------
    |
    | This configuration file contains all the courses offered by SPUP
    | organized by department. This ensures consistency across the application.
    |
    */

    'departments' => [
        'SITE' => [
            'name' => 'School of Information Technology and Engineering',
            'courses' => [
                'BSIT' => 'Bachelor of Science in Information Technology',
                'BLIS' => 'Bachelor of Library and Information Science',
                'BS ENSE' => 'Bachelor of Science in Environmental Science and Engineering',
                'BS CpE' => 'Bachelor of Science in Computer Engineering',
                'BSCE' => 'Bachelor of Science in Civil Engineering',
            ]
        ],
        'SBAHM' => [
            'name' => 'School of Business Administration and Hospitality Management',
            'courses' => [
                'BSA' => 'Bachelor of Science in Accountancy',
                'BSE' => 'Bachelor of Science in Entrepreneurship',
                'BSBAMM' => 'Bachelor of Science in Business Administration Major in Marketing Management',
                'BSBA MFM' => 'Bachelor of Science in Business Administration Major in Financial Management',
                'BSBA MOP' => 'Bachelor of Science in Business Administration Major in Operations Management',
                'BSMA' => 'Bachelor of Science in Management Accounting',
                'BSHM' => 'Bachelor of Science in Hospitality Management',
                'BSTM' => 'Bachelor of Science in Tourism Management',
                'BSPDMI' => 'Bachelor of Science in Public Administration Major in Development Management and Innovation',
            ]
        ],
        'SASTE' => [
            'name' => 'School of Arts, Sciences, Teacher Education',
            'courses' => [
                'BAELS' => 'Bachelor of Arts in English Language Studies',
                'BS Psych' => 'Bachelor of Science in Psychology',
                'BS Bio' => 'Bachelor of Science in Biology',
                'BSSW' => 'Bachelor of Science in Social Work',
                'BSPA' => 'Bachelor of Science in Public Administration',
                'BS Bio MB' => 'Bachelor of Science in Biology Major in Microbiology',
                'BSEd' => 'Bachelor of Secondary Education',
                'BEEd' => 'Bachelor of Elementary Education',
                'BPEd' => 'Bachelor of Physical Education',
            ]
        ],
        'SNAHS' => [
            'name' => 'School of Nursing and Allied Health Sciences',
            'courses' => [
                'BSN' => 'Bachelor of Science in Nursing',
                'BSPh' => 'Bachelor of Science in Pharmacy',
                'BSMT' => 'Bachelor of Science in Medical Technology',
                'BSPT' => 'Bachelor of Science in Physical Therapy',
                'BSRT' => 'Bachelor of Science in Radiologic Technology',
            ]
        ],
        'SOM' => [
            'name' => 'School of Medicine',
            'courses' => [
                'MD' => 'Doctor of Medicine',
                'BS Med' => 'Bachelor of Science in Medicine',
            ]
        ],
        'GRADSCH' => [
            'name' => 'Graduate School',
            'courses' => [
                'MBA' => 'Master of Business Administration',
                'MPA' => 'Master of Public Administration',
                'MEd' => 'Master of Education',
                'MS' => 'Master of Science',
                'MA' => 'Master of Arts',
                'PhD' => 'Doctor of Philosophy',
                'EdD' => 'Doctor of Education',
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Course Helper Methods
    |--------------------------------------------------------------------------
    |
    | Helper methods to get courses in different formats
    |
    */

    'helpers' => [
        // Get all courses as a flat array (code => full name)
        'getAllCourses' => function() {
            $allCourses = [];
            foreach (config('courses.departments') as $dept) {
                $allCourses = array_merge($allCourses, $dept['courses']);
            }
            return $allCourses;
        },

        // Get courses by department (code only)
        'getCoursesByDepartment' => function() {
            $coursesByDept = [];
            foreach (config('courses.departments') as $deptCode => $dept) {
                $coursesByDept[$deptCode] = array_keys($dept['courses']);
            }
            return $coursesByDept;
        },

        // Get course full name by code
        'getCourseName' => function($courseCode) {
            foreach (config('courses.departments') as $dept) {
                if (isset($dept['courses'][$courseCode])) {
                    return $dept['courses'][$courseCode];
                }
            }
            return $courseCode; // Return code if not found
        },
    ],
];
