<?php

namespace App\Http\Controllers\SecOSA;

use App\Http\Controllers\Controller;
use App\Models\GoodMoralApplication;
use App\Services\CertificateService;
use App\Traits\RoleCheck;
use App\Services\NotificationArchiveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CertificateController extends Controller
{
    use RoleCheck;

    protected CertificateService $certificateService;
    protected NotificationArchiveService $notifService;

    public function __construct(CertificateService $certificateService, NotificationArchiveService $notifService)
    {
        $this->certificateService = $certificateService;
        $this->notifService = $notifService;
        $this->checkRole(['admin', 'sec_osa']);
    }

    public function application()
    {
        $goodMoralApplications = GoodMoralApplication::whereIn('application_status', [
                'Ready for Moderator Print',
                'Ready for Pickup',
                'Claimed',
            ])
            ->orderBy('updated_at', 'desc')
            ->get();

        $applications = [
            'ready_to_print' => $goodMoralApplications->where('application_status', 'Ready for Moderator Print'),
            'ready_for_pickup' => $goodMoralApplications->where('application_status', 'Ready for Pickup'),
            'claimed' => $goodMoralApplications->where('application_status', 'Claimed'),
            'all' => $goodMoralApplications,
        ];

        $departments = GoodMoralApplication::whereIn('application_status', [
                'Ready for Moderator Print',
                'Ready for Pickup',
                'Claimed',
            ])
            ->select('department')
            ->distinct()
            ->whereNotNull('department')
            ->orderBy('department')
            ->pluck('department');

        return view('sec_osa.application', compact('applications', 'departments'));
    }

    public function printCertificate($id)
    {
        return $this->certificateService->generateCertificate(
            $id,
            'download',
            ['Ready for Moderator Print', 'Ready for Pickup', 'Claimed'],
            'sec_osa.application',
            false
        );
    }

    public function downloadCertificate($id)
    {
        return $this->certificateService->generateCertificate(
            $id,
            'download',
            ['Ready for Moderator Print', 'Ready for Pickup', 'Claimed'],
            'sec_osa.application',
            false
        );
    }

    public function markAsClaimed($id)
    {
        $result = $this->certificateService->markCertificateAsClaimed($id);

        return redirect()->route('sec_osa.application')->with($result['type'], $result['message']);
    }
}
