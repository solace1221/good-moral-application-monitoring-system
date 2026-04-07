<x-dashboard-layout>
  <x-slot name="roleTitle">Moderator</x-slot>

  <x-slot name="navigation">
    <x-sec-osa-navigation />
  </x-slot>

  <div class="header-section">
    <h1 class="role-title">Printed Certificates</h1>
    <p class="welcome-text">Certificates that have been printed</p>
    <div class="accent-line"></div>
  </div>

  <div class="content-section">
    @if(session('status'))
      <div class="alert alert-success">
        {{ session('status') }}
      </div>
    @endif

    <div class="card">
      <div class="card-header">
        <h2>Printed Certificates</h2>
        <a href="{{ route('sec_osa.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
      </div>
      <div class="card-body">
        @if($applications->isEmpty())
          <p>No certificates have been printed yet.</p>
        @else
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Reference #</th>
                  <th>Student ID</th>
                  <th>Name</th>
                  <th>Department</th>
                  <th>Certificate Type</th>
                  <th>Copies</th>
                  <th>Date Applied</th>
                  <th>Printed On</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($applications as $application)
                  <tr>
                    <td>{{ $application->reference_number }}</td>
                    <td>{{ $application->student_id }}</td>
                    <td>{{ $application->fullname }}</td>
                    <td>{{ $application->department }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $application->certificate_type ?? 'good_moral')) }}</td>
                    <td>{{ $application->number_of_copies }}</td>
                    <td>{{ $application->created_at->format('M d, Y') }}</td>
                    <td>{{ $application->updated_at->format('M d, Y h:i A') }}</td>
                    <td>
                      <form action="{{ route('moderator.printCertificate', $application->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                          Reprint Certificate
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="mt-4">
            {{ $applications->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
</x-dashboard-layout>