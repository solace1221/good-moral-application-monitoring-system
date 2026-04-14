<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationCountService;

class NotificationController extends Controller
{
  public function __construct(protected NotificationCountService $notificationCountService)
  {
  }

  /**
   * Get notification counts for admin sidebar
   */
  public function getNotificationCounts()
  {
    return response()->json($this->notificationCountService->getAdminCounts());
  }
}
