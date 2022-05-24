<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: GroupCategory.php
 *  Path: application/controllers/service/GroupCategory.php
 *  Description: Create Group Categories from
 *  Syntex: php index.php service/GroupCategory/upload
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         17/08/2021              Created
 */
if (!class_exists('GroupCategory')) {

	class GroupCategory extends CI_Controller {

		private $tpc; // Top Parent Category
		public function __construct() {
			parent::__construct();
			$this->load->library(["Category"]);
			$this->tpc = [];
		}

		public function index() {
		}

		public function upload() {
			clog("Category CSV File Processing Start", __METHOD__);
			// Read csv file line by line
			$csv_file = root_path("storage/data/category.csv");
			$this->_read_csv_file($csv_file);

			clog("Category CSV File Processing End", __METHOD__);
		}

		private function _read_csv_file($csv_file) {
			if (!file_exists($csv_file)) {
				$this->logger->error(__METHOD__, "Category CSV File does not exist");
				clog("WhatsApp Category CSV File does not exist", __METHOD__);
				return false;
			}

			$file = fopen($csv_file, 'r');
			$counter = 0;
			while (($line = fgetcsv($file)) !== FALSE) {
				foreach ($line as $index => $category) {
					if (!empty($category)) {
						clog("Adding $category", __METHOD__);
						$parent_id = ($counter == 0) ? 0 : (isset($this->tpc[$index]) ? $this->tpc[$index] : 999999);
						$view_state = ($counter == 0) ? 0 : 1;
						$category_id = $this->category->add_category($category, $category, $parent_id, $view_state);
						clog("Assigned Category Id $category_id", __METHOD__);
						if ($counter == 0 && $category_id) {
							$this->tpc[$index] = $category_id;
						}
					}
				}
				$counter++;
			}
			fclose($file);
		}
	}
}
