<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: access.php
 *  Path: apps/controllers/api.php
 *  Description: 
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         16/06/2021              Created
 *  
 */

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('RestApi');
        //$this->restapi->is_ajax();
    }

    public function __destruct() {
        $this->db->close();
    }

    public function index() {
        $this->restapi->response("Invalid Request", TRUE);
    }

    public function group() {
        $response = [];
        $this->load->library("Storage");
        switch (get_input_method()) {
            case "GET":
                break;
            case "POST":
                $this->storage->add_group();
                break;
            case "PUT":
                break;
            case "DELETE":
                break;
        }
        $this->restapi->response(["error" => false, "data" => "Group Added"]);
    }

    public function subcat() {
        $response = [];
        $this->load->library("Category");
        switch (get_input_method()) {
            case "GET":
                break;
            case "POST":
                $response = $this->category->subcategory();
                break;
            case "PUT":
                break;
            case "DELETE":
                break;
        }
        $this->restapi->response($response);
    }
}
