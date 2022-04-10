<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Request.php
 *  Path: apps/controllers/Request.php
 *  Description: Admin dashboard REST API.
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         28/02/2022              Created
 *  
 */

class Request extends CI_Controller {

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

    public function updateCategory() {
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

    public function uploadCloudFile() {
        $error = true;
        $filename = "file";
        $source_path = upload_image_path_relative();
        if (!empty($_FILES[$filename]['name'])) {
            $uplaod = upload_file($filename, [
                "upload_path" => $source_path,
                "allowed_types" => "jpg|jpeg|gif|png",
                "encrypt_name" => TRUE,
                "max_size" => 1024
            ]);
            if (!$uplaod || (is_array($uplaod) && !isset($uplaod["file_name"]))) {
                set_error("Unable to upload.");
            } else {
                $error = false;
                $file_relative_path = $source_path . $uplaod["file_name"];
                $response = [
                    "message" => "File uploaded",
                    "url" => url($file_relative_path, true),
                    "path" => $file_relative_path,
                ];
            }
        } else {
            set_error("Invalid file.");
        }
        $data = (!$error) ? $response  : get_error();
        $this->restapi->response($data, $error);
    }

    public function getCategoryDetails() {
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
}
