<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Setting, Company };

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
	 * Populate company settings
	 * 
	 * @param  \App\Models\Company  $company
	 * @return array
	 */
	public function companySettings(Company $company)
	{
		// Load all types as check list to be collected
		$types = Type::asSelectArray();

		// Collect all settings, but not yet grouped
		// but later on, this will be grouped using top layer array_map
		// based on loaded types in one line above
        $settings = SettingResource::collection(Setting::all());

        // Collect all company values, but not yet grouped
        // This will be the value to be attached after the 
        // setting key is grouped based on type
        // So this scattered and ungrouped values will be the
        // resource for the processing of first nested array_map
        $settingValues = SettingValue::ofCompany($company)->get();
		$settingValues = SettingValueResource::collection($settingValues);

		// Process all planned array map
		// First layer of array_map will put value of 'keys' to each type
		// of setting enum
        $types = array_map(function ($type, $index) use ($settings, $settingValues) {
            $keys = $settings->where('type', $index)->toArray();

            // Seconf layer of the array_map will put the value for
            // each key that will be attached into the types
            $keys = array_map(function ($key, $index) use ($settingValues) {
            	$values = $settingValues->where('setting_id', $key['id'])->toArray();
                return array_merge($key, ['values' => $values]);
            }, $keys, array_keys($keys)); // Array keys at the third argument will allow index getting in 2nd parameter of array_map callback function

            return array_merge($type, ['keys' => $keys]);
        }, $types, array_keys($types)); // Array keys at the third argument will allow index getting in 2nd parameter of array_map callback function
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
