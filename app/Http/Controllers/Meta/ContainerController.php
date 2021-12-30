<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Enums\Container\{
    ContainerStatus as Status,
    Vpn\ContainerVpnStatus as VpnStatus,
    Vpn\ContainerVpnEnability as VpnEnability,
    Samba\ContainerSambaSmbdStatus as SambaSmbdStatus,
    Samba\ContainerSambaNmbdStatus as SambaNmbdStatus,
    Nginx\ContainerNginxStatus as NginxStatus,
    Nfs\ContainerNfsStatus as NfsStatus,
};

class ContainerController extends Controller
{
    /**
     * Collect all possible status badges for container statuses
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function statusBadges()
    {
        $statuses = collect(Status::asSelectArray());
        $statuses = $statuses->map(function ($description, $value) {
            return [
                'content' => $description,
                'class' => (new Status($value))->badgeHtmlClass(),
            ];
        });

        return response()->json($statuses);
    }

    /**
     * Collect all VPN possible status badges for VPN statuses
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function vpnStatusBadges()
    {
        $statuses = collect(VpnStatus::asSelectArray());
        $statuses = $statuses->map(function ($description, $value) {
            return [
                'content' => $description,
                'class' => (new VpnStatus($value))->badgeHtmlClass(),
            ];
        });

        return response()->json($statuses);
    }

    /**
     * Collect all samba status badges
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function sambaStatusBadges()
    {
        $smbdStatuses = collect(SambaSmbdStatus::asSelectArray());
        $smbdStatuses = $smbdStatuses->map(function ($description, $value) {
            return [
                'content' => $description,
                'class' => (new SambaSmbdStatus($value))->badgeHtmlClass(),
            ];
        });

        $nmbdStatuses = collect(SambaNmbdStatus::asSelectArray());
        $nmbdStatuses = $nmbdStatuses->map(function ($description, $value) {
            return [
                'content' => $description,
                'class' => (new SambaNmbdStatus($value))->badgeHtmlClass(),
            ];
        });

        return response()->json([
            'smbd' => $smbdStatuses,
            'nmbd' => $nmbdStatuses,
        ]);
    }

    /**
     * Collect all samba status badged
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function nfsStatusBadges()
    {
        $statuses = collect(NfsStatus::asSelectArray());
        $statuses =  $statuses->map(function ($description, $value) {
            return [
                'content' => $description,
                'class' => (new NfsStatus($value))->badgeHtmlClass(),
            ];
        });

        return response()->json($statuses);
    }

    /**
     * Collect all nginx status badges
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function nginxStatusBadges()
    {
        $statuses = collect(NginxStatus::asSelectArray());
        $statuses =  $statuses->map(function ($description, $value) {
            return [
                'content' => $description,
                'class' => (new NginxStatus($value))->badgeHtmlClass(),
            ];
        });

        return response()->json($statuses);
    }
}
