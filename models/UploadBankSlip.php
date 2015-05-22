<?php
/**
 * Upload Bank Slip Class.
 *

"date","time","site","depth","duration","access","tags","buddies","notes"
"15/09/2013","15:45","Vodelée","36","38","1","vodelée","rackham, Ann","Belle plongée"

 */

namespace app\models;

use Yii;
use yii\base\Model;

class UploadBankSlip {

	var $encoding;
	var $delimiter;
	var $ignorefirst;
	var $raw_data;
	var $confirmation_report;
	var $creation_report;
	var $headers;
	var $number_of_failed_divelogs = 0;
	var $divelogs_to_confirm = NULL; /// An array containing divelog info from csv file
	var $divelogs_to_create = NULL; /// An array of divelogs to create
	var $transfer_date_format = 'd-m-Y';

	function __construct() {

	}

	/**
	 * Set encoding of the CSV file
	 *
	 * @param $encoding
	 */
	function setEncoding($encoding) {
		$this->encoding = $encoding;
	}

	/**
	 * Set delimiter of the CSV file
	 *
	 * @param $delimiter
	 */
	function setIgnoreFirst($ignorefirst) {
		$this->ignorefirst = $ignorefirst;
	}

	/**
	 * Set delimiter of the CSV file
	 *
	 * @param $delimiter
	 */
	function setDelimiter($delimiter) {
		$this->delimiter = $delimiter;
	}

	/**
	 * Process the file
	 *
	 * @param $file
	 * @return boolean
	 */
	function openFile($file) {
		if (!$contents = get_uploaded_file($file)) {
			register_error(elgg_echo('divelog:error:cannot_open_file'));
			return false;
		}

		/// Check the encoding
		if ($this->encoding == 'ISO-8859-1') {
			$contents = utf8_encode($contents);
		}

		$this->raw_data = $contents;
		return true;
	}

	/**
	 * Process divelog accounts from the raw data from the file
	 *
	 * @param $data
	 * @return boolean
	 */
	function processFile() {
		/// Turn the string into an array
		$rows = explode("\n", $this->raw_data);

		/// First row includes headers
		$headers = array();
		if($this->ignorefirst) { // if we must ignore first line, it is because it contains field names (and therefore their order)
			$headers = $rows[0];
			$headers = explode($this->delimiter, $headers);

			/// Trim spaces from $headers
			$headers = array_map('trim', $headers);

			/// Check that there are no empty headers. This can happen if there are delimiters at the end of the file
			foreach ($headers as $header) {
				if (!empty($header)) { // remove " and '
					$headers2[] = strtolower( trim(trim($header, "'"), '"') );
				}
			}
			$headers = $headers2;
		} else {
			$headers = array('date','time','site','depth','duration','access','tags','buddies','notes');
		}
		
		$headers[] = 'status';
		$this->headers = $headers;

		/// Check that at least divelog date and site are provided in the headers
		if (!in_array('date', $headers) ||
		    !in_array('site', $headers) ) {
			register_error(elgg_echo('divelog:error:wrong_csv_format'));
			return false;
		}

		/// Create a nicer array of divelogs for processing
		$divelogs = array();

		/// Go through the divelog rows
		for ($i = 0; $i < count($rows); $i++) {
			if($this->ignorefirst and ($i == 0) ) continue; // skip first line
			
			$rows[$i] = trim($rows[$i]);
			if (empty($rows[$i])) {
				continue;
			}
			$divelog_details = explode($this->delimiter, $rows[$i]);
			$divelog = array();
			/// Go through divelog information
			foreach ($divelog_details as $key => $field) {
				$fieldname = trim($headers[$key]); /// Remove whitespaces
				$field = trim($field);   /// and other garbage.
				$field = trim(trim($field, "'"), '"'); /// and " and '
				if (in_array($fieldname, $headers))
					$divelog[$fieldname] = $field;
			}
			$divelogs[] = $divelog;
		}
		$this->divelogs_to_confirm = $divelogs;
		return true;
	}

	function checkDivelogs() {
		$final_report = array(); /// Final report of the upload process
		/// Check all the divelogs from $divelogs_to_confirm array
		foreach ($this->divelogs_to_confirm as $divelog) {
				
			try {
				// reformat date as a check
				$divelog_date = strtotime( DateTime::createFromFormat('d/m/Y', $divelog['date'])->format($this->transfer_date_format));
				//$tc = explode(':', $divelog['time']);
				//$divelog_time = $tc[0] * 60 + $tc[1];				
				//@todo: should check time format?
			
				// normalize access_id values to 0, 1, or 2.
				if (empty($divelog['access'])) {
					$divelog['access'] = 0;
				} else {
					switch($divelog['access']) {
						case 1:
						case 2:
							break;
						default:
							$divelog['access'] = 0;
					}
				}
				
				// @todo: should we check other values as well?

				$report = array(
					'date' => date($this->transfer_date_format, $divelog_date),
					'time' => $divelog['time'],// . ' ('.$divelog_time.')',
					'site' => $divelog['site'],
					'depth' => $divelog['depth'],
					'duration' => $divelog['duration'],
					'tags' => $divelog['tags'],
					'buddies' => $divelog['buddies'],
					'access' => $divelog['access'],
					'notes' => $divelog['notes'],
					'create_divelog' => true
				);			
			} catch (Exception $r) {
				$report['status'] = '<span class="error">' . $r->getMessage() . '</span>';
			}


			/// Add the divelog to the creation list if we can create the divelog
			if ($report['status'] == '') {
				$report['status'] = elgg_echo('divelog:upload:statusok'); /// Set status to ok
				$this->divelogs_to_create[] = $divelog;
			} else {
				$this->number_of_failed_divelogs++;
			}
			$final_report[] = $report;
		}
		$this->confirmation_report = $final_report;
		return true;
	}

	/**
	 * Get a display friendly status report of the accounts creation
	 *
	 * @return unknown_type
	 */
	public function getCreationReport() {
		$data = array('headers' => $this->headers,
					  'report' => $this->creation_report,
					  'num_of_failed' => $this->number_of_failed_divelogs);

		return elgg_view('divelog/upload/creation_report', $data);
	}

	/**
	 * Get a display friendly status report of the accounts creation
	 *
	 * @return unknown_type
	 */
	public function getConfirmationReport() {
		$data = array('headers' => $this->headers,
					  'report' => $this->confirmation_report,
					  'num_of_failed' => $this->number_of_failed_divelogs,
					  'delimiter' => $this->delimiter,
					  'encoding' => $this->encoding);

		return elgg_view_form('divelog/confirmation_report',
								 array('enctype' => 'multipart/form-data',
									   'method' => 'POST',
									   'id' => 'divelog-upload-form'),
								 $data);
	}
}
