<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\Newsletter;

class NewsletterRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Newsletter);
	}

	/**
	 * Save newsletter
	 * 
	 * @param  array  $newsletterData
	 * @return \App\Models\Newsletter
	 */
	public function save(array $newsletterData)
	{
		try {
			$newsletter = $this->getModel();
			$newsletter->fill($newsletterData);
			$newsletter->save();

			$this->setModel($newsletter);

			$this->setSuccess('Successfully publish a newsletter');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to publis a newsletter.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete newsletter
	 * 
	 * @return  bool
	 */
	public function delete()
	{
		try {
			$newsletter = $this->getModel();
			$newsletter->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete newsletter.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete newsletter.', $error);
		}

		return $this->returnResponse();
	}
}
