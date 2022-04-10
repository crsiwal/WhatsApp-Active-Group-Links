<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Tools.php
 *  Path: application/controllers/service/Tools.php
 *  Description: Process the groups file links.
 *  Syntex: {base_url}/tools/{ToolName}
 *  
 *  Tools List:
 *  	01. urlGroups ==== Get Whatsapp Group links from any web page and uplaod to Proccessing file
 * 		02. multiGroups ==== Submit whsatapp multiple groups link line by line and uplaod to Proccessing file
 *   
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         08/07/2021              Created
 */
if (!class_exists('Tools')) {

	class Tools extends CI_Controller {

		public function __construct() {
			parent::__construct();
		}

		public function index() {
			$this->template->addMeta('title', 'Website Tools List | Whatsapp Groups');
			$this->template->addMeta('description', 'Whatsapp tools list.');
			$this->template->show('pages/tools/public_tools', [], 'public_tools');
		}

		public function urlGroups() {
			$response = [];
			switch (get_input_method()) {
				case "POST":
					$this->load->helper(array('form', 'security'));
					$this->load->library('form_validation', NULL, 'form');
					$this->form->set_rules('category', 'Category', 'trim|required|min_length[1]|max_length[30]|xss_clean');
					$this->form->set_rules('subcategory', 'Sub Category', 'trim|required|min_length[1]|max_length[30]|xss_clean');
					$this->form->set_rules('websitelink', 'Website Link', 'trim|required|min_length[1]|max_length[300]|xss_clean');
					if ($this->form->run() != FALSE) {
						$this->load->library("Storage");
						$category_id = $this->input->post('category', true);
						$sub_category_id = $this->input->post('subcategory', true);
						$website_link = $this->input->post('websitelink', true);
						$groups = groups_url_from_others($website_link);
						if (is_array($groups) && count($groups) > 0) {
							foreach ($groups as $invite_url) {
								$this->storage->add_group_manually($category_id, $sub_category_id, $invite_url, "");
							}
							$data = ["groups" => $groups, "count" => count($groups)];
							$this->template->addMeta('title', 'Group Uploaded Successfully | Whatsapp Groups');
							$this->template->addMeta('description', 'Groups Uploaded successfully to system.');
							$this->template->show('pages/tools/upload_groups/groups_upload_success', $data, 'groups_upload_success');
							break;
						} else {
							$this->sessions->set_error("Not found any valid group");
						}
					} else {
						set_input_error();
					}
				case "GET":
					$this->load->library(["Category"]);
					$data = array(
						"select" => $this->category->category(),
						"error" => $this->sessions->get_error(),
					);
					$this->template->addMeta('title', 'Group Links From Url | Whatsapp Groups');
					$this->template->addMeta('description', 'Whatsapp Group categories.');
					$this->template->show('pages/tools/upload_groups/group_link_from_url', $data, 'group_link_from_url');
					break;
				case "PUT":
					break;
				case "DELETE":
					break;
			}
		}

		public function multiGroups() {
			$response = [];
			switch (get_input_method()) {
				case "POST":
					$this->load->helper(array('form', 'security'));
					$this->load->library('form_validation', NULL, 'form');
					$this->form->set_rules('groups', 'Groups Link', 'trim|required|min_length[1]|max_length[300]|xss_clean');
					$this->form->set_rules('category', 'Category', 'trim|required|min_length[1]|max_length[30]|xss_clean');
					$this->form->set_rules('subcategory', 'Sub Category', 'trim|required|min_length[1]|max_length[30]|xss_clean');
					if ($this->form->run() != FALSE) {
						$this->load->library("Storage");
						$category_id = $this->input->post('category', true);
						$sub_category_id = $this->input->post('subcategory', true);
						$group_links_text = $this->input->post('groups', true);
						$tags = empty($this->input->post('tags', true)) ? "" : $this->input->post('tags', true);
						$groups = groups_url_from_line_by_line_text($group_links_text);
						if (is_array($groups) && count($groups) > 0) {
							foreach ($groups as $invite_url) {
								$this->storage->add_group_manually($category_id, $sub_category_id, $invite_url, $tags);
							}
							$data = ["groups" => $groups, "count" => count($groups)];
							$this->template->addMeta('title', 'Group Uploaded Successfully | Whatsapp Groups');
							$this->template->addMeta('description', 'Groups Uploaded successfully to system.');
							$this->template->show('pages/tools/upload_groups/groups_upload_success', $data, 'groups_upload_success');
							break;
						} else {
							$this->sessions->set_error("Not found any group from this url");
						}
					} else {
						set_input_error();
					}
				case "GET":
					$this->load->library(["Category"]);
					$data = array(
						"select" => $this->category->category(),
						"error" => $this->sessions->get_error(),
					);
					$this->template->addMeta('title', 'Upload Mutiple Whatsapp Groups | Whatsapp Groups');
					$this->template->addMeta('description', 'Upload Mutiple Whatsapp Groups.');
					$this->template->show('pages/tools/upload_groups/upload_multiple_group', $data, 'upload_multiple_group');
					break;
				case "PUT":
					break;
				case "DELETE":
					break;
			}
		}
	}
}
