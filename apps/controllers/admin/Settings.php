<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Settings.php
 *  Path: application/controllers/Settings.php
 *  Description: It's a settings controler .
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         29/05/2021              Created
 *
 */

class Settings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        checkLogin();
    }

    public function index() {
        $data = array(
            'popup' => array('login'),
        );
        $this->template->addMeta('title', 'Settings');
        $this->template->addMeta('description', 'Login to your account.');
        $this->template->header('admin');
        $this->template->show('admin/settings/view', $data, 'settings', 'admin_overview');
    }
}
