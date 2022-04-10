<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Pages.php
 *  Path: application/controllers/Pages.php
 *  Description: About the website
 *	
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         25/02/2022              Created
 */

class Pages extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->load->library(["Category", "Group"]);
		$data = array(
			"popup" => array("groups/submit"),
			"select" => $this->category->category(),
			"categories" => $this->category->top(24),
			"trending" => $this->group->trending(12),
		);
		$this->template->addMeta('title', 'Whatsapp Groups');
		$this->template->addMeta('description', 'Collection of whatsapp groups');
		$this->template->show('pages/home/view', $data, 'home');
	}

	public function aboutUs() {
		$this->load->library(["Group"]);
		$data = array(
			"popup" => array(),
			"trending" => $this->group->trending(12)
		);
		$this->template->addMeta('title', 'About Us | Whatsapp Groups');
		$this->template->addMeta('description', 'About us details.');
		$this->template->show('pages/about_us/view', $data, 'aboutus');
	}

	public function contactUs() {
		$this->load->library(["Group"]);
		$data = array(
			"popup" => array(),
			'g_login_url' => $this->google->get_login_url(),
			"trending" => $this->group->trending(12)
		);
		$this->template->addMeta('title', 'Contact Us | Whatsapp Groups');
		$this->template->addMeta('description', 'Contact us details.');
		$this->template->show('pages/contact_us/view', $data, 'contactus');
	}

	public function termOfService() {
		$this->load->library(["Group"]);
		$data = array(
			"popup" => array(),
			"trending" => $this->group->trending(12)
		);
		$this->template->addMeta('title', 'Term Of Service | Whatsapp Groups');
		$this->template->addMeta('description', 'Term of service.');
		$this->template->show('pages/term_of_service/view', $data, 'termofservice');
	}

	public function privacyPolicy() {
		$this->load->library(["Group"]);
		$data = array(
			"popup" => array(),
			"trending" => $this->group->trending(12)
		);
		$this->template->addMeta('title', 'Privacy Policy | Whatsapp Groups');
		$this->template->addMeta('description', 'Privacy Policy.');
		$this->template->show('pages/privacy_policy/view', $data, 'privacypolicy');
	}

	public function disclaimer() {
		$this->load->library(["Group"]);
		$data = array(
			"popup" => array(),
			"trending" => $this->group->trending(12)
		);
		$this->template->addMeta('title', 'Disclaimer | Whatsapp Groups');
		$this->template->addMeta('description', 'Disclaimer.');
		$this->template->show('pages/disclaimer/view', $data, 'disclaimer');
	}
}
