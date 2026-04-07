<x-dashboard-layout>
  <x-slot name="roleTitle">Moderator</x-slot>

  <x-slot name="navigation">
    <x-moderator-navigation />
  </x-slot>
<div class="dashboard-container">
  <!-- Header Section -->
  <div class="header-section">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="role-title">Escalation Notifications</h1>
        <p class="welcome-text">Students with 3 Minor Violations (Auto-Escalation to Major)</p>
        <div class="accent-line"></div>
      </div>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div style="padding: 12px 16px; background: var(--light-green); border-radius: 8px; font-size: 14px; color: var(--primary-green); font-weight: 600;">
          {{ count($escalationNotifications) }} Student{{ count($escalationNotifications) !== 1 ? 's' : '' }} with 3+ Minor Violations
        </div>
      </div>
    </div>
  </div>

  <!-- Escalation Notifications Section -->
  <div class="header-section">
    <h3 style="color: var(--primary-green); margin-bottom: 20px; font-size: 1.3rem; display: flex; align-items: center; gap: 12px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 24px; width: 24px;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
      </svg>
      Escalation Alerts
    </h3>

    @if(empty($escalationNotifications))
    <div style="background: #f8f9fa; color: #6c757d; padding: 40px; border-radius: 8px; text-align: center; border: 2px dashed #dee2e6;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 48px; width: 48px; margin: 0 auto 16px; color: #adb5bd;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <h4 style="font-size: 1.2rem; margin-bottom: 8px; color: #495057;">No Escalation Alerts</h4>
      <p style="margin: 0;">No students currently have 3 or more minor violations requiring escalation.</p>
    </div>
    @else
    <div style="display: grid; gap: 20px;">
      @foreach ($escalationNotifications as $notification)
      <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                  border-left: 4px solid 
                  @if($notification['escalation_status'] === 'escalated') 
                    #28a745 
                  @else 
                    #dc3545 
                  @endif;">
        
        <!-- Student Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
          <div>
            <h4 style="margin: 0 0 4px 0; color: #333; font-size: 1.1rem; font-weight: 600;">
              {{ $notification['fullname'] }}
            </h4>
            <p style="margin: 0; color: #666; font-size: 14px;">
              <strong>Student ID:</strong> {{ $notification['student_id'] }} | 
              <strong>Department:</strong> {{ $notification['department'] }} | 
              <strong>Course:</strong> {{ $notification['course'] }}
            </p>
          </div>

          <span style="padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase;
                       background: 
                       @if($notification['escalation_status'] === 'escalated') 
                         #28a745 
                       @else 
                         #dc3545 
                       @endif;
                       color: white;">
            @if($notification['escalation_status'] === 'escalated')
              ✓ ESCALATED
            @else
              ⚠ NEEDS ESCALATION
            @endif
          </span>
        </div>

        <!-- Violation Summary -->
        <div style="background: #f8f9fa; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div>
              <strong style="color: #333; display: block; margin-bottom: 4px;">Minor Violations Count:</strong>
              <span style="color: #dc3545; font-size: 1.2rem; font-weight: 600;">
                {{ $notification['minor_violation_count'] }}/3
              </span>
            </div>
            <div>
              <strong style="color: #333; display: block; margin-bottom: 4px;">Latest Violation:</strong>
              <span style="color: #666;">
                {{ $notification['latest_violation_date'] ? $notification['latest_violation_date']->format('M j, Y g:i A') : 'N/A' }}
              </span>
            </div>
            <div>
              <strong style="color: #333; display: block; margin-bottom: 4px;">Escalation Status:</strong>
              <span style="color: {{ $notification['escalation_status'] === 'escalated' ? '#28a745' : '#dc3545' }};">
                @if($notification['escalation_status'] === 'escalated')
                  Major violation automatically created
                @else
                  Pending automatic escalation
                @endif
              </span>
            </div>
          </div>
        </div>

        <!-- Auto Major Violation Info -->
        @if($notification['auto_major_violation'])
        <div style="background: #d4edda; color: #155724; padding: 16px; border-radius: 8px; border-left: 4px solid #28a745; margin-bottom: 16px;">
          <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 20px; width: 20px; color: #28a745;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <strong>Automatic Major Violation Created</strong>
          </div>
          <p style="margin: 0; font-size: 14px;">
            <strong>Violation:</strong> {{ $notification['auto_major_violation']->violation }}<br>
            <strong>Reference:</strong> {{ $notification['auto_major_violation']->ref_num }}<br>
            <strong>Created:</strong> {{ $notification['auto_major_violation']->created_at->format('M j, Y g:i A') }}<br>
            <strong>Status:</strong> 
            @switch($notification['auto_major_violation']->status)
              @case('0') Pending @break
              @case('1') Approved @break
              @case('2') Resolved @break
              @default Unknown @break
            @endswitch
          </p>
        </div>
        @endif

        <!-- Minor Violations List -->
        <div style="margin-bottom: 16px;">
          <strong style="color: #333; display: block; margin-bottom: 12px;">Minor Violations Details:</strong>
          <div style="display: grid; gap: 8px;">
            @foreach($notification['minor_violations'] as $index => $violation)
            <div style="background: #fff3cd; color: #856404; padding: 12px; border-radius: 6px; border-left: 3px solid #ffc107;">
              <div style="display: flex; justify-content: between; align-items: flex-start; gap: 12px;">
                <div style="flex: 1;">
                  <strong>{{ $index + 1 }}. {{ $violation->violation }}</strong>
                  @if($violation->ref_num)
                    <span style="font-size: 12px; color: #6c757d;">(Ref: {{ $violation->ref_num }})</span>
                  @endif
                </div>
                <div style="text-align: right; font-size: 12px; color: #6c757d;">
                  {{ $violation->created_at->format('M j, Y') }}<br>
                  <span style="padding: 2px 6px; background: 
                    @switch($violation->status)
                      @case('0') #ffc107 @break
                      @case('1') #28a745 @break
                      @case('2') #6c757d @break
                      @default #dc3545 @break
                    @endswitch; 
                    color: white; border-radius: 4px; font-size: 10px;">
                    @switch($violation->status)
                      @case('0') PENDING @break
                      @case('1') APPROVED @break
                      @case('2') RESOLVED @break
                      @default UNKNOWN @break
                    @endswitch
                  </span>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
          <a href="{{ route('sec_osa.minor') }}" 
             style="padding: 8px 16px; background: var(--primary-green); color: white; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 500;">
            View All Minor Violations
          </a>
          <a href="{{ route('sec_osa.major') }}" 
             style="padding: 8px 16px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 500;">
            View All Major Violations
          </a>
        </div>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</div>
</x-dashboard-layout>