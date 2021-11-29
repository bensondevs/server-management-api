<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Settings\SaveSiteSettingsRequest;

use App\Http\Requests\Settings\SaveRabbitMQOptionRequest;
use App\Http\Requests\Settings\SaveRabbitMQConfigsRequest;
use App\Http\Requests\Settings\SaveRabbitMQConfigurationRequest;

use App\Repositories\SettingRepository;
use App\Repositories\ActivityLogRepository;

class SettingController extends Controller
{
    protected $log;
    protected $setting;

    public function __construct(
    	ActivityLogRepository $activityLogRepository,
        SettingRepository $settingRepository
    )
    {
    	$this->log = $activityLogRepository;
        $this->setting = $settingRepository;
    }

    public function index()
    {
        $settings = $this->setting->allSettings();

        $this->setting->refreshSessions();

    	return view(
            'dashboard.settings.index', 
            compact(['settings'])
        );
    }

    public function saveSiteSettings(SaveSiteSettingsRequest $request)
    {
        $this->setting->saveSettings(
            $request->onlyInRules()
        );

        $this->setting->refreshSessions();

        return redirect()->back();
    }

    public function saveRabbitMQOption(SaveRabbitMQOptionRequest $request)
    {
        $this->setting->saveSettings(
            $request->onlyInRules()
        );

        return redirect()->back();
    }

    public function saveRabbitMQConfigs(SaveRabbitMQConfigsRequest $request)
    {
        $this->setting->saveSettings(
            $request->onlyInRules()
        );

        return redirect()->back();
    }

    public function populateActivityLog(Request $request)
    {
    	if ($start = $request->get('start'))
    		$this->log->setStart($start);

    	if ($end = $request->get('end'))
    		$this->log->setEnd($end);

    	$activities = $this->log->allActivities();

    	return response()->json([
            'activities' => $activities
        ]);
    }
}
