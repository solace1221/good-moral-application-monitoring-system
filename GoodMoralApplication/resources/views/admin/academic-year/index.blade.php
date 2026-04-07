@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Academic Year Management</h1>
        <p class="text-gray-600">Manage academic years and student year level promotions</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Students</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalStudents }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Eligible for Promotion</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $eligibleForPromotion }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full {{ $promotionActive ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Promotion Status</p>
                    <p class="text-lg font-bold {{ $promotionActive ? 'text-yellow-600' : 'text-gray-600' }}">
                        {{ $promotionActive ? 'Active' : 'Inactive' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Academic Years List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">Academic Years</h2>
                        <button onclick="openCreateModal()" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create New Year
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    @if($academicYears->count() > 0)
                        <div class="space-y-4">
                            @foreach($academicYears as $year)
                                <div class="border rounded-lg p-4 {{ $year->is_current ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="text-lg font-semibold text-gray-800">{{ $year->year_name }}</h3>
                                                @if($year->is_current)
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Current</span>
                                                @endif
                                                @if($year->year_level_promotion_active)
                                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Promotion Active</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600 mb-1">
                                                <span class="font-medium">Period:</span> 
                                                @if($year->start_date && $year->end_date)
                                                    {{ \Carbon\Carbon::parse($year->start_date)->format('M d, Y') }} - 
                                                    {{ \Carbon\Carbon::parse($year->end_date)->format('M d, Y') }}
                                                @elseif($year->start_year && $year->end_year)
                                                    {{ $year->start_year }} - {{ $year->end_year }}
                                                @else
                                                    Academic Year Period
                                                @endif
                                            </p>
                                            @if($year->notes)
                                                <p class="text-sm text-gray-500">{{ $year->notes }}</p>
                                            @endif
                                            @if($year->promotion_triggered_at)
                                                <p class="text-xs text-blue-600 mt-1">
                                                    Promotion triggered: {{ \Carbon\Carbon::parse($year->promotion_triggered_at)->format('M d, Y g:i A') }}
                                                    by {{ $year->promotion_triggered_by }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="flex gap-2 ml-4">
                                            @if($year->is_current && !$year->year_level_promotion_active)
                                                <button onclick="triggerNewYear({{ $year->id }})" class="btn-warning btn-sm">
                                                    Trigger New Year
                                                </button>
                                            @endif
                                            @if($year->is_current && $year->year_level_promotion_active)
                                                <button onclick="processPromotions()" class="btn-success btn-sm">
                                                    Process Promotions
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 1v10m-6 0V8a6 6 0 1112 0v9"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No academic years</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first academic year.</p>
                            <div class="mt-6">
                                <button onclick="openCreateModal()" class="btn-primary">
                                    Create Academic Year
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <!-- Manual Student Promotion -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Manual Student Promotion</h3>
                <form id="manualPromotionForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Student</label>
                        <input type="text" id="studentSearch" placeholder="Enter student ID or name..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <div id="studentResults" class="hidden mt-2 max-h-48 overflow-y-auto border border-gray-200 rounded-md"></div>
                    </div>
                    
                    <div id="selectedStudentInfo" class="hidden p-3 bg-blue-50 rounded-md">
                        <p class="text-sm font-medium text-blue-900" id="selectedStudentName"></p>
                        <p class="text-xs text-blue-700" id="selectedStudentDetails"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Year Level</label>
                        <select id="newYearLevel" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select year level</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                        <textarea id="promotionReason" rows="3" placeholder="Enter reason for manual promotion..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <button type="submit" class="w-full btn-primary">
                        Promote Student
                    </button>
                </form>
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.academic-year.history') }}" class="block w-full btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        View Promotion History
                    </a>
                    <button onclick="exportPromotionData()" class="w-full btn-info">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Academic Year Modal -->
<div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 transition-all duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md transform transition-all duration-300 scale-95" id="createModalContent">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Create New Academic Year</h3>
                    <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('admin.academic-year.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Academic Year Name</label>
                        <input type="text" name="year_name" placeholder="e.g., 2024-2025" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                            <input type="date" name="start_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                            <input type="date" name="end_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea name="notes" rows="3" placeholder="Additional notes or description..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeCreateModal()" class="btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            Create Academic Year
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let selectedStudentId = null;

// Modal management
function openCreateModal() {
    const modal = document.getElementById('createModal');
    const content = document.getElementById('createModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
}

function closeCreateModal() {
    const modal = document.getElementById('createModal');
    const content = document.getElementById('createModalContent');
    content.classList.remove('scale-100');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Academic year actions
function triggerNewYear(id) {
    if (confirm('Are you sure you want to trigger the new academic year? This will activate year level promotions.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('admin/academic-years') }}/${id}/trigger-new-year`;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

function processPromotions() {
    if (confirm('Are you sure you want to process automatic year level promotions? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.academic-year.process-promotions") }}';
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

// Student search functionality
document.getElementById('studentSearch').addEventListener('input', function(e) {
    const query = e.target.value;
    if (query.length < 2) {
        document.getElementById('studentResults').classList.add('hidden');
        return;
    }

    fetch(`{{ route('admin.academic-year.search-students') }}?search=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(students => {
            const resultsDiv = document.getElementById('studentResults');
            if (students.length === 0) {
                resultsDiv.innerHTML = '<div class="p-3 text-sm text-gray-500">No students found</div>';
            } else {
                resultsDiv.innerHTML = students.map(student => `
                    <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" 
                         onclick="selectStudent('${student.student_id}', '${student.fullname}', '${student.year_level}', '${student.course}', '${student.department}')">
                        <p class="font-medium text-gray-900">${student.fullname}</p>
                        <p class="text-sm text-gray-600">${student.student_id} • Year ${student.year_level} • ${student.course} - ${student.department}</p>
                    </div>
                `).join('');
            }
            resultsDiv.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
        });
});

function selectStudent(studentId, fullname, yearLevel, course, department) {
    selectedStudentId = studentId;
    document.getElementById('studentSearch').value = fullname;
    document.getElementById('studentResults').classList.add('hidden');
    
    const infoDiv = document.getElementById('selectedStudentInfo');
    document.getElementById('selectedStudentName').textContent = fullname;
    document.getElementById('selectedStudentDetails').textContent = `${studentId} • Current: Year ${yearLevel} • ${course} - ${department}`;
    infoDiv.classList.remove('hidden');
}

// Manual promotion form
document.getElementById('manualPromotionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!selectedStudentId) {
        alert('Please select a student first.');
        return;
    }

    const newYearLevel = document.getElementById('newYearLevel').value;
    const reason = document.getElementById('promotionReason').value.trim();

    if (!newYearLevel) {
        alert('Please select a new year level.');
        return;
    }

    if (!reason) {
        alert('Please provide a reason for the promotion.');
        return;
    }

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('student_id', selectedStudentId);
    formData.append('new_year_level', newYearLevel);
    formData.append('reason', reason);

    fetch('{{ route("admin.academic-year.promote-student") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the promotion.');
    });
});

function exportPromotionData() {
    alert('Export functionality will be implemented in the next update.');
}

// Close modal when clicking outside
document.getElementById('createModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateModal();
    }
});
</script>
@endsection