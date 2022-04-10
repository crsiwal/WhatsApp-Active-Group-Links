<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Accounts.php
 *  Path: application/controllers/Accounts.php
 *  Description: It's a accounts controler .
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         26/02/2022              Created
 *
 */

class Accounts extends CI_Controller {

    public function __construct() {
        parent::__construct();
        checkLogin();
    }

    public function index() {
        $data = array(
            'popup' => array('login'),
        );
        $this->template->addMeta('title', 'Login In');
        $this->template->addMeta('description', 'Login to your account.');
        $this->template->header('admin');
        $this->template->show('admin/accounts/view', $data, 'accounts', 'admin_overview');
    }
}
