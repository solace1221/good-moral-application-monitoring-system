<?php

namespace App\Http\Controllers\SecOSA;

use App\Http\Controllers\Controller;
use App\Services\NotificationCountService;
use App\Traits\RoleCheck;

class NotificationController extends Controller
{
    use RoleCheck;

    public function __construct(protected NotificationCountService $notificationCountService)
    {
        $this->checkRole(['sec_osa']);
    }

    /**
     * Get notification counts for the moderator navbar
     */
    public function getNotificationCounts()
    {
        return response()->json($this->notificationCountService->getSecOSACounts());
    }
}
