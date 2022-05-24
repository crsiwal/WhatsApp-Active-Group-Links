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
        $this->load->model('CategoryModel', 'mod_category');
        $data = array(
            "popup" => array('login'),
            "categories" =>  $this->mod_category->categories(["parent_id" => 0])
        );
        $this->template->addMeta('title', 'Groups');
        $this->template->addMeta('description', 'Manage groups uploaded to website.');
        $this->template->header('admin');
        $this->template->show('admin/groups/view', $data, 'groups', 'admin_overview');
    }
}
