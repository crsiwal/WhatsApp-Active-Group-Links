<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Dashboard.php
 *  Path: application/controllers/admin/Dashboard.php
 *  Description: It's a Administrator dashboard controler.
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         26/02/2022              Created
 *
 */

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        checkLogin();
    }

    public function index() {
        $data = array(
            'popup' => array('login'),
        );
        $this->template->addMeta('title', 'Dashboard');
        $this->template->addMeta('description', 'Admin Dashboard for user.');
        $this->template->header('admin');
        $this->template->show('admin/dashboard/view', $data, 'dashboard', 'admin_overview');
    }
}
