<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Categories.php
 *  Path: application/controllers/Categories.php
 *  Description: It's a category listing controller of whatsapp group links tool.
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         02/06/2021              Created
 *
 */

class Categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(["Category", "Group"]);
    }

    public function index() {
        $data = array(
            "popup" => array(),
            "categories" => $this->category->top(),
            "childs" => true,
            "trending" => $this->group->trending(12)
        );
        $this->template->addMeta('title', 'Categories | Whatsapp Groups');
        $this->template->addMeta('description', 'Whatsapp Group categories.');
        $this->template->show('pages/category/view', $data, 'categories');
    }

    public function sub_category($category_slug = "") {
        $data = array(
            "popup" => array(),
            "categories" => $this->category->top_child($category_slug),
            "childs" => false,
            "trending" => $this->group->trending(12)
        );
        $this->template->addMeta('title', 'Categories | Whatsapp Groups');
        $this->template->addMeta('description', 'Whatsapp Group categories.');
        $this->template->show('pages/sub_category/view', $data, 'sub_categories');
    }
}
