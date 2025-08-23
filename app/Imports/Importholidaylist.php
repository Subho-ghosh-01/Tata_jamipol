<?php

namespace App\Imports;

use App\Vendorholidaylist; // ✅ Use your actual model
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Importholidaylist implements ToModel, WithHeadingRow
{
	public function model(array $row)
	{
		if (empty($row['pno'])) {
			return null;
		}

		// Check for duplicates
		$exists = Vendorholidaylist::where('pno', $row['pno'])
			->where('year', $row['year'])
			->exists();

		if ($exists) {
			return null;
		}

		return new Vendorholidaylist([
			'pno' => $row['pno'],
			'year' => $row['year'],
			'name' => $row['name'],
			'pl' => $row['pl'],
			'fl' => $row['fl'],
			'cl' => $row['cl'],
			'flp' => $row['flp'],
			'spl' => $row['spl'],
			'created_by' => Session::get('user_idSession'),
			'created_datetime' => now(),
			'updated_by' => 0,
			'updated_datetime' => ''
		]);
	}
}

?>