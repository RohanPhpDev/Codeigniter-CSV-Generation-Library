<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Excel {

	public $filename 		= 'Demo';
	public $custom_titles;

	public function make_from_db($db_results,$titles=[]) {
		$data 		= NULL;
		$fields 	= $db_results->field_data();

		if ($db_results->num_rows() == 0) {
			show_error('The table appears to have no data');
		}
		else {
			if(empty($titles))
				$headers = $this->titles($fields);
			else
				$headers=$titles;
			$this->generate($headers, $db_results->result_array());
		}
	}

	public function make_from_array($titles, $array) {
		$data = NULL;

		if (!is_array($array)) {
			show_error('The data supplied is not a valid array');
		}
		else { 
			$headers = $this->titles($titles);
			$this->generate($headers,$array);
			}
		}

	private function generate($headers, $data) {
		$this->set_headers();
		/*echo "<pre>";
		print_r($data);die();*/
		// create a file pointer connected to the output stream
		$file = fopen('php://output', 'w');
		//fputcsv($file, array('Column 1', 'Column 2', 'Column 3', 'Column 4', 'Column 5'));
		fputcsv($file, $headers);
		foreach ($data as $row)
		{
		fputcsv($file, $row);
		}
	}

	public function titles($titles) {
		if (is_array($titles)) {
			$headers = array();

			if (is_null($this->custom_titles)) {
				if (is_array($titles)) {
					foreach ($titles AS $title) {
						$headers[] = $title->name;
					}
				}
				else {
					foreach ($titles AS $title) {
						$headers[] = $title->name;
					}
				}
			}
			else {
				$keys = array();
				foreach ($titles AS $title) {
					$keys[] = $title->name;
				}
				foreach ($keys AS $key) {
					$headers[] = $this->custom_titles[array_search($key, $keys)];
				}
			}
			//print_r($headers);die();
			return ($headers);
		}
	}

	private function set_headers() {
		header("Pragma: public");
	    header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Content-Type: application/force-download");
	    header("Content-Type: application/octet-stream");
	    header("Content-Type: application/download");;
	    header("Content-Disposition: attachment;filename=$this->filename.csv");
	    header("Content-Transfer-Encoding: binary ");
	}
}
