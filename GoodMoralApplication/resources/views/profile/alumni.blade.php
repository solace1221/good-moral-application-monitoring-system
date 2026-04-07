{{-- Redirect to the main student profile (which handles both students and alumni) --}}
@php
    return redirect()->route('student.profile');
@endphp
