<x-dashboard-layout>
    <x-slot name="roleTitle">Moderator</x-slot>

    <x-slot name="navigation">
        <x-sec-osa-navigation />
    </x-slot>

    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-2">Good Moral Applications</h1>
        <p class="text-gray-600 mb-6">Review and manage good moral certificate applications</p>

        @if(session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Pending Applications Section -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h2 class="text-lg font-semibold text-gray-700">Pending Applications</h2>
            </div>
            
            @if($pendingApplications->isEmpty())
                <div class="p-4 text-gray-500">No pending applications found.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Certificate Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Applied</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendingApplications as $application)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->student_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $application->department }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->fullname }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ ucfirst(str_replace('_', ' ', $application->certificate_type ?? 'good_moral')) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="text-gray-700">{{ $application->created_at->format('M j, Y') }}</div>
                                        <div class="text-gray-400 text-xs">{{ $application->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="text-green-600 font-semibold">₱{{ number_format($application->payment_amount, 2) }}</div>
                                        <div class="text-gray-400 text-xs">{{ count($application->reasons_array) }} {{ count($application->reasons_array) === 1 ? 'reason' : 'reasons' }} × {{ $application->number_of_copies }} {{ $application->number_of_copies == 1 ? 'copy' : 'copies' }} × ₱50.00</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="openDetailsModal('{{ $application->id }}')" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-sm">View Details</button>
                                        
                                        <a href="{{ route('moderator.viewReceipt', ['reference_number' => $application->reference_number]) }}" class="bg-purple-500 hover:bg-purple-600 text-white py-1 px-3 rounded text-sm">View Receipt</a>
                                        
                                        <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded text-sm">Print</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Printed Applications Section -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h2 class="text-lg font-semibold text-gray-700">Recently Printed Applications</h2>
            </div>
            
            @if($printedApplications->isEmpty())
                <div class="p-4 text-gray-500">No printed applications found.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Certificate Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Applied</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Printed Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($printedApplications as $application)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->student_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $application->department }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->fullname }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ ucfirst(str_replace('_', ' ', $application->certificate_type ?? 'good_moral')) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="text-gray-700">{{ $application->created_at->format('M j, Y') }}</div>
                                        <div class="text-gray-400 text-xs">{{ $application->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="text-green-600 font-semibold">₱{{ number_format($application->payment_amount, 2) }}</div>
                                        <div class="text-gray-400 text-xs">{{ count($application->reasons_array) }} {{ count($application->reasons_array) === 1 ? 'reason' : 'reasons' }} × {{ $application->number_of_copies }} {{ $application->number_of_copies == 1 ? 'copy' : 'copies' }} × ₱50.00</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $application->updated_at->format('M d, Y h:i A') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white py-1 px-3 rounded text-sm">Reprint</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Application Details</h3>
                <div class="mt-2 px-7 py-3" id="modalContent">
                    <!-- Content will be loaded here -->
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDetailsModal(id) {
            // Fetch application details
            fetch(`/moderator/application-details/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalContent').innerHTML = `
                        <div class="text-left">
                            <p class="mb-2"><strong>Student ID:</strong> ${data.student_id}</p>
                            <p class="mb-2"><strong>Name:</strong> ${data.fullname}</p>
                            <p class="mb-2"><strong>Department:</strong> ${data.department}</p>
                            <p class="mb-2"><strong>Certificate Type:</strong> ${data.certificate_type ? data.certificate_type.replace('_', ' ') : 'Good Moral'}</p>
                            <p class="mb-2"><strong>Number of Copies:</strong> ${data.number_of_copies}</p>
                            <p class="mb-2"><strong>Reason:</strong> ${data.reason}</p>
                            <p class="mb-2"><strong>Status:</strong> ${data.status}</p>
                            <p class="mb-2"><strong>Application Status:</strong> ${data.application_status}</p>
                            <p class="mb-2"><strong>Date Applied:</strong> ${new Date(data.created_at).toLocaleString()}</p>
                            ${data.is_undergraduate ? 
                                `<p class="mb-2"><strong>Last Course/Year Level:</strong> ${data.last_course_year_level || 'N/A'}</p>
                                <p class="mb-2"><strong>Last Semester/SY:</strong> ${data.last_semester_sy || 'N/A'}</p>` 
                                : 
                                `<p class="mb-2"><strong>Course Completed:</strong> ${data.course_completed || 'N/A'}</p>
                                <p class="mb-2"><strong>Graduation Date:</strong> ${data.graduation_date || 'N/A'}</p>`
                            }
                        </div>
                    `;
                    document.getElementById('detailsModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load application details');
                });
        }

        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('detailsModal').classList.add('hidden');
        });
    </script>
</x-dashboard-layout>