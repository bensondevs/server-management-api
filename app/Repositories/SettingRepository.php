<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Setting;

class SettingRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Setting);
	}

	public function refreshSessions()
	{
		$settings = $this->allSettings();

		foreach ($settings as $key => $value)
			session()->put($key, $value);
	}

	public function allSettings()
	{
		$rawSettings = $this->all();
		$settings = [];

		foreach ($rawSettings as $rawSetting)
			$settings[$rawSetting['key']] = $rawSetting['value'];

		return collect($settings);
	}

	public function findKey($key)
	{
		$setting = $this->getModel();

		if (! $setting = $setting->where('key', $key)->first())
			return null;

		$this->setModel($setting);

		return $this->getModel();
	}

	public function save($settingData)
	{
		try {
			$setting = $this->getModel();
			$setting->fill($settingData);
			$setting->save();

			$this->setModel($setting);

			$this->setSuccess('Successfully save setting.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save setting.', $error);
		}
	}

	public function saveSettings(array $settings)
	{
		try {
			$_setings = [];

			foreach ($settings as $key => $value) {
				$setting = $this->findKey($key);
				$this->save(['value' => $value]);
				array_push($_setings, $this->getModel());
				$this->setModel(new Setting);
			}

			$this->setSuccess('Successfully update settings');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to update settings', $error);
		}

		return $this->getCollection();
	}
}
