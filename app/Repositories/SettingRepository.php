<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\Setting;

class SettingRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Setting);
	}

	/**
	 * Save setting
	 * 
	 * @param  array  $settingData
	 * @return \App\Models\Setting
	 */
	public function save(array $settingData)
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

		return $this->getModel();
	}

	/**
	 * Save settings
	 * 
	 * @param  array  $settings
	 * @return Collection
	 */
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
