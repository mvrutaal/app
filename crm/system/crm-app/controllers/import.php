<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import extends CRM_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	// ********************************************************************************* //

	public function index()
	{

	}

	// ********************************************************************************* //

	public function manual_street_import()
	{
		include_once APPPATH . 'libraries\PHPExcel.php';
		$file = 'D:\Code\Deviarte\Deviarte-CRM\import\companies_all_71101741.xlsx';
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadDataOnly(true);
		$objReader->setLoadSheetsOnly( array("companies_all_71101741") );
		$objPHPExcel = $objReader->load($file);

		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();

		$streets = array();

		for ($row = 1; $row <= $highestRow; ++$row)
		{
			$streets[] = $objWorksheet->getCellByColumnAndRow('2', $row)->getValue();
		}

		foreach ($streets as $street)
		{
			$street= trim($street);
			$query = $this->db->select('street_id')->from('crm_dataset_streets')->where('street_label', $street)->get();

			if (strpos($street, 'P.O') !== FALSE) continue;

			if ($query->num_rows() == 0)
			{
				$this->db->set('street_label', $street);
				//$this->db->insert('crm_dataset_streets');
			}
		}
	}

	// ********************************************************************************* //

	public function manual_company_import()
	{
		include_once APPPATH . 'libraries\PHPExcel.php';
		$file = 'D:\Code\Deviarte\Deviarte-CRM\import\activecompanies_71103842.xlsx';
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadDataOnly(true);
		$objReader->setLoadSheetsOnly( array("activecompanies_71103842") );
		$objPHPExcel = $objReader->load($file);

		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();

		for ($row = 2; $row <= $highestRow; ++$row)
		{
			$address = trim($objWorksheet->getCellByColumnAndRow('9', $row)->getValue());

			preg_match('/([^\d]+)\s?(.+)/i', $address, $result);
			$street = $result[1];
			$number = $result[2];

			// Get Street
			$query = $this->db->select('street_id')->from('crm_dataset_streets')->where('street_label', $street)->get();

			if ($query->num_rows() == 0)
			{

				if ($address != 'Adres Onbekend') echo $objWorksheet->getCellByColumnAndRow('3', $row)->getValue() . ' - '.$address."<br/>";
			}
			else
			{
				$this->db->set('company_housenumber', $number);
				$this->db->set('company_street_id', $query->row('street_id'));
			}

			$this->db->set('company_title', $objWorksheet->getCellByColumnAndRow('3', $row)->getValue());
			$this->db->set('company_coc_id', $objWorksheet->getCellByColumnAndRow('0', $row)->getValue());
			$this->db->insert('crm_companies');
		}

	}

	// ********************************************************************************* //
}

/* End of file welcome.php */
/* Location: ./application/controllers/contacts.php */