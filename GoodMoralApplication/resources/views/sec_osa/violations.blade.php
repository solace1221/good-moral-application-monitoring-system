@extends('layouts.app')

@section('content')
<x-moderator-navigation />

<div style="margin-left: 250px; padding: 20px;">
    <div class="container-fluid">
        <!-- Header -->
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">All Violations</h2>
                <a href="{{ route('sec_osa.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>

        <!-- Filter Options -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <select class="form-select" id="departmentFilter">
                            <option value="">All Departments</option>
                            <option value="SITE">SITE</option>
                            <option value="SASTE">SASTE</option>
                            <option value="SBAHM">SBAHM</option>
                            <option value="SNAHS">SNAHS</option>
                            <option value="SOM">SOM</option>
                            <option value="GRADSCH">GRADSCH</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="offenseFilter">
                            <option value="">All Types</option>
                            <option value="minor">Minor Violations</option>
                            <option value="major">Major Violations</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="0">Pending</option>
                            <option value="1">Resolved</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">Clear</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Violations Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                @if($violations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="violationsTable">
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
                                <tr data-department="{{ $violation->department }}" 
                                    data-offense="{{ $violation->offense_type }}" 
                                    data-status="{{ $violation->status }}">
                                    <td>{{ $violation->student_id }}</td>
                                    <td>{{ $violation->first_name }} {{ $violation->last_name }}</td>
                                    <td>{{ $violation->department }}</td>
                                    <td>{{ $violation->course }}</td>
                                    <td>{{ $violation->violation }}</td>
                                    <td>
                                        <span class="badge {{ $violation->offense_type == 'major' ? 'bg-danger' : 'bg-warning text-dark' }}">
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
                        <h4>No violations found.</h4>
                        <p>There are currently no recorded violations in the system.</p>
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

.form-select {
    font-size: 14px;
}
</style>

<script>
function applyFilters() {
    const departmentFilter = document.getElementById('departmentFilter').value;
    const offenseFilter = document.getElementById('offenseFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    const rows = document.querySelectorAll('#violationsTable tbody tr');
    
    rows.forEach(row => {
        let showRow = true;
        
        if (departmentFilter && row.getAttribute('data-department') !== departmentFilter) {
            showRow = false;
        }
        
        if (offenseFilter && row.getAttribute('data-offense') !== offenseFilter) {
            showRow = false;
        }
        
        if (statusFilter && row.getAttribute('data-status') !== statusFilter) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

function clearFilters() {
    document.getElementById('departmentFilter').value = '';
    document.getElementById('offenseFilter').value = '';
    document.getElementById('statusFilter').value = '';
    
    const rows = document.querySelectorAll('#violationsTable tbody tr');
    rows.forEach(row => {
        row.style.display = '';
    });
}
</script>
@endsection