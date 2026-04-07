<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Head, OSA Dashboard
    </h2>
  </x-slot>

  <div x-data="{ sidebarOpen: false }" class="flex">
    <!-- Mobile Sidebar Toggle -->
    <div class="sm:hidden w-full bg-gray-100 border-b border-gray-300 py-2 flex justify-between px-4">
      <button @click="sidebarOpen = !sidebarOpen" class="bg-gray-800 text-white p-2 rounded-md">
        ☰ Menu
      </button>
    </div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"
      class="w-64 bg-gray-800 text-white min-h-screen fixed sm:relative left-0 transform transition-transform duration-300 sm:translate-x-0">

      <div class="p-4 text-lg font-bold border-b border-gray-700">
        Head, OSA Dashboard
      </div>

      <nav class="mt-4">
        <a href="{{ route('head_osa.dashboard') }}" class="block px-4 py-2 hover:bg-gray-700">Applications</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 sm:px-8 lg:px-12">
      <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Applications from Registrar</h3>

        @if(session('status'))
        <div class="bg-green-500 text-white p-4 rounded-md mb-4">
          {{ session('status') }}
        </div>
        @endif

        @if($applications->isEmpty())
        <p>No applications available.</p>
        @else
        <table class="min-w-full bg-white border border-gray-300 rounded-lg">
          <thead>
            <tr class="text-left border-b">
              <th class="px-6 py-3 text-sm font-medium text-gray-500">Student ID</th>
              <th class="px-6 py-3 text-sm font-medium text-gray-500">Department</th>
              <th class="px-6 py-3 text-sm font-medium text-gray-500">Full Name</th>
              <th class="px-6 py-3 text-sm font-medium text-gray-500">Status</th>
              <th class="px-6 py-3 text-sm font-medium text-gray-500">Applied On</th>
              <th class="px-6 py-3 text-sm font-medium text-gray-500">Purpose</th>
              <th class="px-6 py-3 text-sm font-medium text-gray-500">Reason</th>
              <th class="px-6 py-3 text-sm font-medium text-gray-500">Course Completed</th>
              <th class="px-6 py-3 text-sm font-medium text-gray-500">Graduation Date</th>
              <th class="px-6 py-3 text-sm font-medium text-gray-500">Undergraduate</th>
              <th class="px-6 py-3 text-sm font-medium text-gray-500">Last Course Year Level</th>
              <th class="px-6 py-3 text-sm font-medium text-gray-500">Last Semester SY</th>
              <th class="px-6 py-3 text-sm font-medium text-gray-500">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($applications as $application)
            <tr class="border-b">
              <td class="px-6 py-4 text-sm text-gray-600">{{ $application->student->student_id }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ $application->student->department }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ $application->student->fullname }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($application->status) }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ $application->created_at->format('Y-m-d') }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ $application->purpose }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ $application->reason }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ $application->course_completed }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ $application->graduation_date }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ $application->is_undergraduate }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ $application->last_course_year_level }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ $application->last_semester_s }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">
                @if($application->status == 'pending')
                <!-- Approve -->
                <form action="{{ route('head_osa.approve', $application->id) }}" method="POST" style="display:inline;">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="bg-green-500 text-white p-2 rounded-md">Approve</button>
                </form>

                <!-- Reject -->
                <form action="{{ route('head_osa.reject', $application->id) }}" method="POST" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="bg-red-500 text-white p-2 rounded-md">Reject</button>
                </form>
                @else
                <span class="text-gray-500">No action available</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </main>
  </div>
</x-app-layout>