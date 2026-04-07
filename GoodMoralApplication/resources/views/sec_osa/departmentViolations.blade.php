@extends('layouts.app')

@section('content')
<x-moderator-navigation />

<div style="margin-left: 250px; padding: 20px;">
    <div class="container-fluid">
        <!-- Header -->
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">{{ $department }} Department Violations</h2>
                <a href="{{ route('sec_osa.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>

        <!-- Violations Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                @if($violations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Department</th>
                                    <th>Course</th>
                                    <th>Violation Type</th>
                                    <th>Offense Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($violations as $violation)
                                <tr>
                                    <td>{{ $violation->student_id }}</td>
                                    <td>{{ $violation->first_name }} {{ $violation->last_name }}</td>
                                    <td>{{ $violation->department }}</td>
                                    <td>{{ $violation->course }}</td>
                                    <td>{{ $violation->violation }}</td>
                                    <td>
                                        <span class="badge {{ $violation->offense_type == 'major' ? 'bg-danger' : 'bg-warning' }}">
                                            {{ ucfirst($violation->offense_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $violation->status == '1' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $violation->status == '1' ? 'Resolved' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td>{{ $violation->created_at ? $violation->created_at->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        @if($violation->offense_type == 'major')
                                            <a href="{{ route('sec_osa.major') }}" class="btn btn-sm btn-primary">View Details</a>
                                        @else
                                            <a href="{{ route('sec_osa.minor') }}" class="btn btn-sm btn-info">View Details</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $violations->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <h4>No violations found for {{ $department }} department.</h4>
                        <p>This department currently has no recorded violations.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styling for the violations page */
.card {
    border-radius: 8px;
    border: none;
}

.card-header {
    background: linear-gradient(135deg, var(--primary-green) 0%, #2c7a2c 100%);
    color: white;
    border-radius: 8px 8px 0 0 !important;
}

.table th {
    font-weight: 600;
    font-size: 14px;
}

.table td {
    font-size: 13px;
    vertical-align: middle;
}

.badge {
    font-size: 11px;
    padding: 6px 8px;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
}

.alert-info {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
}
</style>
@endsection