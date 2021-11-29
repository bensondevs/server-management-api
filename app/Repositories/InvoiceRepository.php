<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Invoice;

class InvoiceRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Invoice);
	}

	public function save(array $invoiceData)
	{
		try {
			$invoice = $this->getModel();
			$invoice->fill($invoiceData);
			$invoice->save();

			$this->setModel($invoice);

			$this->setSuccess('Successfully save invoice data.');
		} catch (QueryException $qe) {
			$queryError = $qe->getMessage();
			$this->setError('Failed to save invoice.', $queryError);
		}

		return $this->getModel();
	}
}
