<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: GroupProcessing.php
 *  Path: application/controllers/service/GroupProcessing.php
 *  Description: Process the groups file links.
 *  Syntex: php index.php service/GroupProcessing
 * 
 *  Processing List:
 *  	01. Rename store.log file to current_timestamp.log file
 *  	02. Create blank store.log file for continious store other files.
 *  	03. Read Groups file line by line till end.
 * 		04. Retrive group details from log
 * 		05. Get Group details from Whatsapp Website -> Continue if Group available otherwise go to next.
 * 		06. Validate the category and Sub Category of group selected by user.
 * 		07. Check Group already exist with our database. -> If Yes (Update Name/Icon/Category/Sub Category) Other wise Continue to add new.
 * 		08. Get the IP details (Country/State/City/Language)
 * 		09. 
 * 		10. 
 * 		11. 
 *   
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         25/06/2021              Created
 */
if (!class_exists('GroupProcessing')) {

	class GroupProcessing extends CI_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->library("WhatsApp", null, "wa");
			$this->load->library("IpInfo", null, "ip");
			$this->load->library(["Category", "group", "Tags"]);
		}

		public function index() {
			clog("Whastapp Group File Processing Start", __METHOD__);
			if ($groups_file = $this->_rename_file()) {
				$this->_read_groups_from_file($groups_file);
				// Move File to History Data
				$this->_move_file($groups_file);
			}
			clog("Whastapp Group File Processing End", __METHOD__);
		}

		private function _move_file($groups_file) {
			$move_path = store_move_path();
			if (!is_dir($move_path)) {
				mkdir($move_path, 0777, TRUE);
			}
			$move_path .= url_file_name($groups_file) . ".log";
			if (rename($groups_file, $move_path)) {
				clog("Groups file moved", __METHOD__);
				clog("File Path: $groups_file", __METHOD__);
				clog("Moved at: $move_path", __METHOD__);
			} else {
				clog("Unable to move data file in history", __METHOD__);
			}
		}

		private function _rename_file() {
			$file_path = store_file_path();
			if (!file_exists($file_path)) {
				$this->logger->error(__METHOD__, "Store File not exist");
				clog("WhatsApp Group File does not exist", __METHOD__);
				return false;
			}
			$new_file = store_file_path(get_time("Y-m-d-H-i-s"));
			if (rename($file_path, $new_file)) {
				clog("WhatsApp Group File renamed to " . $new_file, __METHOD__);
				return $new_file;
			}
			return FALSE;
		}

		private function _read_groups_from_file($groups_file) {
			clog("Calling", __METHOD__);
			if (!file_exists($groups_file))
				return false;

			if ($reader = fopen($groups_file, "r")) {
				while (($line = fgets($reader)) !== false) {
					// process the line read.
					clog($line, __METHOD__);
					$this->_retrive_group_from_log($line);
				}
				fclose($reader);
				return true;
			}
			return false;
		}

		/** Extract Group Details and Call to Process it */
		private function _retrive_group_from_log($line) {
			clog("Calling", __METHOD__);
			$data = explode("$", $line);
			if (count($data) >= 5) {
				$ip = trim($data[0]);
				$category_id = trim($data[1]);
				$sub_category_id = trim($data[2]);
				$invite_link = trim($data[3]);
				$tags = empty(trim($data[4])) ? [] : explode(",", trim($data[4]));
				$this->_process_group_from_log($ip, $category_id, $sub_category_id, $invite_link, $tags);
			} else {
				clog("Found a invalid Group Details", __METHOD__);
			}
		}

		/** Process Group With Details */
		private function _process_group_from_log($ip, $category_id, $sub_category_id, $invite_link, $tags) {
			clog("Calling", __METHOD__);
			// Get the group details from WhatsApp Website if not exist then return false.
			if ($wa_group = $this->wa->group_by_invite_link($invite_link)) {
				clog("Group Found ${wa_group["name"]} - ${wa_group["invite_key"]}", __METHOD__);

				// validate the category and sub category
				$category = $this->category->group_category($category_id, $sub_category_id);

				// Get the group from database using Unique Invite key
				if ($group = $this->group->by_invite_key($wa_group["invite_key"])) {
					clog("This Group exist in system. Invite key: " . $wa_group["invite_key"], __METHOD__);
					// Update the details of group [Name, Picture, Category, Subcategory, Country, State, City, Language, tags]
				} else {
					clog("This Group new to system. Invite key: " . $wa_group["invite_key"], __METHOD__);
					/** Add new group in database */
					$geo = $this->ip->get($ip, true); // Get IP details from Third Paty IP Service API

					$icon_relative_url = get_local_image_by_url($wa_group["icon_url"]);
					$group_id = $this->group->create(
						[
							"user_id" => get_logged_in_user_id(),
							"name" => $wa_group["name"],
							"invite_key" => $wa_group["invite_key"],
							"category_id" => $category["category_id"],
							"subcategory_id" => $category["sub_category_id"],
							"country_id" => $geo["country_id"],
							"region_id" => $geo["region_id"],
							"city_id" => $geo["city_id"],
							"icon_url" => $icon_relative_url,
						]
					);
					if ($group_id) {
						clog("New Whatsapp Group added to system", __METHOD__);
						$this->category->addGroup($category["category_id"], $category["sub_category_id"]);
						clog("Start Mapping Tags with Group. New Group Id-> " . $group_id, __METHOD__);
						if (is_array($tags) && count($tags) > 0) {
							$this->tags->map($group_id, $tags);
						}
						return true;
					}
					return false;
				}
			} else {
				return false;
			}
		}
	}
}
