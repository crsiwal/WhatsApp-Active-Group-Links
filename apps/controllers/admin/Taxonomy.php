<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Taxonomy.php
 *  Path: application/controllers/Taxonomy.php
 *  Description: It's a categories and tags controler .
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         29/05/2021              Created
 *
 */

class Taxonomy extends CI_Controller {

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
        $this->template->addMeta('title', 'Category');
        $this->template->addMeta('description', 'Category list');
        $this->template->header('admin');
        $this->template->show('admin/category/view', $data, 'category', 'admin_overview');
    }
}
