<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Groups.php
 *  Path: application/controllers/Groups.php
 *  Description: It's a Groups controler .
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         29/05/2021              Created
 *
 */

class Groups extends CI_Controller {

    public function __construct() {
        parent::__construct();
        checkLogin();
    }

    public function index() {
        $data = array(
            'popup' => array('login'),
        );
        $this->template->addMeta('title', 'Groups');
        $this->template->addMeta('description', 'Login to your account.');
        $this->template->header('admin');
        $this->template->show('admin/groups/view', $data, 'groups', 'admin_overview');
    }
}
