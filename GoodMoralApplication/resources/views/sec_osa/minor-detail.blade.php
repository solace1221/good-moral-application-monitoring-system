<x-dashboard-layout>
  <x-slot name="roleTitle">Moderator</x-slot>

  <x-slot name="navigation">
    <x-moderator-navigation />
  </x-slot>

  <div style="padding: 24px; background: #f8f9fa; min-height: 100vh;">

    <!-- Header -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
          <h1 style="margin: 0 0 8px 0; color: var(--primary-green); font-size: 1.75rem; font-weight: 700;">Minor Violation Details</h1>
          <p style="margin: 0; color: #6c757d; font-size: 1rem;">Viewing details for violation record</p>
        </div>
        <a href="{{ route('sec_osa.minor') }}"
           style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500;">
          ← Back to Minor Violations
        </a>
      </div>
    </div>

    <!-- Student Information -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 24px;">
      <h3 style="margin: 0 0 16px 0; color: #333; font-size: 1.1rem; font-weight: 600; border-bottom: 1px solid #e9ecef; padding-bottom: 12px;">Student Information</h3>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
          <strong style="color: #495057; display: block; margin-bottom: 4px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Student Name</strong>
          <span style="color: #212529; font-size: 15px; font-weight: 500;">{{ $violation->first_name }} {{ $violation->last_name }}</span>
        </div>
        <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
          <strong style="color: #495057; display: block; margin-bottom: 4px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Student ID</strong>
          <span style="color: #212529; font-size: 15px; font-family: monospace;">{{ $violation->student_id }}</span>
        </div>
        <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
          <strong style="color: #495057; display: block; margin-bottom: 4px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Department</strong>
          <span style="color: #212529; font-size: 15px;">{{ $violation->department }}</span>
        </div>
        <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
          <strong style="color: #495057; display: block; margin-bottom: 4px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Course</strong>
          <span style="color: #212529; font-size: 15px;">{{ $violation->course ?? 'N/A' }}</span>
        </div>
      </div>
    </div>

    <!-- Violation Information -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; margin-bottom: 24px;">
      <h3 style="margin: 0 0 16px 0; color: #333; font-size: 1.1rem; font-weight: 600; border-bottom: 1px solid #e9ecef; padding-bottom: 12px;">Violation Information</h3>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 16px;">
        <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
          <strong style="color: #495057; display: block; margin-bottom: 4px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Reference No.</strong>
          @if($violation->ref_num)
            <span style="font-family: monospace; color: #212529; font-size: 15px;">{{ $violation->ref_num }}</span>
          @else
            <span style="color: #adb5bd; font-style: italic;">Not yet assigned</span>
          @endif
        </div>
        <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
          <strong style="color: #495057; display: block; margin-bottom: 4px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Offense Type</strong>
          <span style="display: inline-block; padding: 3px 10px; background: #fff3cd; color: #856404; border-radius: 12px; font-size: 12px; font-weight: 700; text-transform: uppercase;">
            {{ $violation->offense_type ?? 'Minor' }}
          </span>
        </div>
        <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
          <strong style="color: #495057; display: block; margin-bottom: 4px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Status</strong>
          @if($violation->status == 0)
            <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; background: #fff3cd; color: #856404; border-radius: 12px; font-size: 12px; font-weight: 500;">
              <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              Pending
            </span>
          @elseif($violation->status == 1)
            <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; background: #d1ecf1; color: #0c5460; border-radius: 12px; font-size: 12px; font-weight: 500;">
              <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              Dean Approved
            </span>
          @elseif($violation->status == 2)
            <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px; font-weight: 500;">
              <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Case Closed
            </span>
          @endif
        </div>
        <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
          <strong style="color: #495057; display: block; margin-bottom: 4px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Added By</strong>
          <span style="color: #212529; font-size: 15px;">{{ $violation->added_by ?? 'N/A' }}</span>
        </div>
        <div style="padding: 14px; background: #f8f9fa; border-radius: 8px;">
          <strong style="color: #495057; display: block; margin-bottom: 4px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Date Recorded</strong>
          <span style="color: #212529; font-size: 15px;">{{ $violation->created_at?->format('M d, Y') ?? 'N/A' }}</span>
        </div>
      </div>

      @if($violation->violation)
        <div style="padding: 16px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
          <strong style="color: #856404; display: block; margin-bottom: 8px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Violation Description</strong>
          <p style="margin: 0; color: #856404; line-height: 1.6;">{{ $violation->violation }}</p>
        </div>
      @endif

      @if($violation->violation)
        @php $vDetail = $violation->violation; @endphp
        @if($vDetail && ($vDetail->article || $vDetail->description))
          <div style="padding: 16px; background: #e8f4f8; border-radius: 8px; border-left: 4px solid #17a2b8; margin-top: 12px;">
            <strong style="color: #0c5460; display: block; margin-bottom: 8px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Violation Article</strong>
            @if($vDetail->article)
              <p style="margin: 0 0 6px 0; color: #0c5460; font-weight: 600;">{{ $vDetail->article }}</p>
            @endif
            @if($vDetail->description)
              <p style="margin: 0; color: #0c5460; line-height: 1.6; font-size: 14px;">{{ $vDetail->description }}</p>
            @endif
          </div>
        @endif
      @endif
    </div>

  </div>

</x-dashboard-layout>
