<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: GroupsRestAPI.php
 *  Path: apps/controllers/admin/restapi/GroupsRestAPI.php
 *  Description: Admin dashboard Groups REST API.
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         04/03/2022              Created
 *  
 */

class GroupsRestAPI extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('RestApi');
        $this->restapi->is_ajax();
    }

    public function __destruct() {
        $this->db->close();
    }

    public function index() {
        $this->restapi->response("Invalid Request", TRUE);
    }

    public function updateGroup() {
        $this->load->library("Category");
        $success = false;
        $response = [];
        $this->load->helper(array('form', 'security'));
        $this->load->library('form_validation', NULL, 'form');
        $this->form->set_rules('_ctid', 'Category', 'trim|required|min_length[1]|xss_clean');
        $this->form->set_rules('name', 'Name', 'trim|required|min_length[1]|max_length[60]|xss_clean');
        $this->form->set_rules('slug', 'Slug', 'trim|required|min_length[1]|max_length[60]|xss_clean');
        $this->form->set_rules('banner', 'Banner', 'trim|required|min_length[10]|max_length[256]|xss_clean');
        $this->form->set_rules('parentid', 'Parent Category', 'trim|required|min_length[1]|xss_clean');
        $this->form->set_rules('viewstate', 'View State', 'trim|required|min_length[1]|xss_clean');
        if ($this->form->run() != FALSE) {
            $category_id = $this->input->post('_ctid', true);
            if (!empty($category_id)) {
                $this->load->model('CategoryModel', 'mod_category');
                $view_state = $this->input->post('viewstate', true);
                $data = [
                    "name" => $this->input->post('name', true),
                    "slug" => convert_username($this->input->post('slug', true)),
                    "parent_id" => $this->input->post('parentid', true),
                    "view_state" => ($view_state >= 0 && $view_state < 5) ? $view_state : 2,
                    "icon_url" => $this->input->post('banner', true),
                    "enabled" => ($this->input->post('status', true) == "on") ? 1 : 0,
                    "update_time" => get_time(),
                ];
                if ($this->mod_category->category_exist_by("slug", $data["slug"], [$category_id])) {
                    $data["slug"] .= "_" . unique_key(6);
                }
                $status = $this->mod_category->update($category_id, $data);
                if ($status === false) {
                    $this->sessions->set_error("Unable to update.");
                } else {
                    $response["category"] = [
                        "id" => $category_id,
                        "name" => $data["name"],
                        "slug" => $data["slug"],
                        "icon" => url($data["icon_url"], true),
                        "ricon" => $data["icon_url"],
                        "pid" => $data["parent_id"],
                        "vst" => $data["view_state"],
                        "enb" => $data["enabled"],
                    ];
                    $response["message"] = "Category updated.";
                }
                $success = true;
            } else {
                $this->sessions->set_error("Unable to update.");
            }
        } else {
            set_input_error();
        }
        $response = array(
            "error" => !$success,
            "data" => ($success) ? $response : $this->sessions->get_error()
        );
        $this->restapi->response($response);
    }

    public function getGroupDetail() {
        $response = [];
        $success = false;
        $this->load->helper(array('form', 'security'));
        $this->load->library('form_validation', NULL, 'form');
        $this->form->set_rules('_ctid', 'Category', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        if ($this->form->run() != FALSE) {
            $category_id = $this->input->post('_ctid', true);
            if (!empty($category_id)) {
                $this->load->model('CategoryModel', 'mod_category');
                $success = true;
                $default_category_url = url($this->config->item("category_default_banner"), true);
                $icon_url = url("", true);
                $category_url = category_url("", true);
                $category_group_url = category_group_url("", true);
                $fields = [
                    "id", "name", "slug", "parent_id as pid", "view_state as vst", "icon_url as ricon",
                    "CONCAT('$category_url', slug) as url",
                    "CONCAT('$category_group_url', slug) as gurl",
                    "case when icon_url IS NULL or icon_url = '' then '$default_category_url' else CONCAT('$icon_url', icon_url) end as icon",
                    "groups", "enabled as enb"
                ];
                $response["parent"] = $this->mod_category->categories(["id" => $category_id, "single" => true], 1000, 0, $fields);
                $response["childs"] = $this->mod_category->categories(["parent_id" => $category_id], 1000, 0, $fields);
            } else {
                $this->sessions->set_error("Unable to add this adsize");
            }
        } else {
            set_input_error();
        }
        $response = array(
            "error" => !$success,
            "data" => ($success) ? $response : $this->sessions->get_error()
        );
        $this->restapi->response($response);
    }

    public function getCategoryGroups() {
        $response = [];
        $success = false;
        $this->load->helper(array('form', 'security'));
        $this->load->library('form_validation', NULL, 'form');
        $this->form->set_rules('_ctid', 'Category', 'trim|required|min_length[1]|max_length[30]|xss_clean');
        if ($this->form->run() != FALSE) {
            $category_id = $this->input->post('_ctid', true);
            if (!empty($category_id)) {
                $this->load->model('GroupModel', 'mod_group');
                $success = true;
                $icon_url = url("", true);
                $fields = ["a.id", "a.name", "CONCAT('$icon_url', a.icon_url) as icon", "a.status"];
                $page = empty($this->input->post('_p', true)) ? 0 : $this->input->post('_p', true);
                $response["next"] = $page + 1;
                $response["childs"] = $this->mod_group->groups(["category_id" => $category_id], 25, ($page  * 25), $fields);
            } else {
                $this->sessions->set_error("Unable to get details");
            }
        } else {
            set_input_error();
        }
        $response = array(
            "error" => !$success,
            "data" => ($success) ? $response : $this->sessions->get_error()
        );
        $this->restapi->response($response);
    }
}
