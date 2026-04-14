<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use App\Services\NotificationCountService;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
  public function __construct(protected NotificationCountService $notificationCountService)
  {
  }

  /**
   * Get notification counts for dean sidebar
   */
  public function getNotificationCounts()
  {
    $department = Auth::user()->department;

    return response()->json($this->notificationCountService->getDeanCounts($department));
  }
}
