<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: AssetsRestAPI.php
 *  Path: apps/controllers/admin/restapi/AssetsRestAPI.php
 *  Description: Admin dashboard REST API for minify asstes like css and javascript.
 *  Syntax: php index.php rest recache
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         04/03/2022              Created
 *  
 */

class AssetsRestAPI extends CI_Controller {

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

    public function reCache() {
        $this->load->library("Minify");
        $success = false;
        $response = [];
        if ($assets = $this->minify->reCache()) {
            $success = true;
        } else {
            $this->sessions->set_error("Getting issue to recache the assets");
        }
        $response = array(
            "error" => !$success,
            "data" => ($success) ? $response : $this->sessions->get_error()
        );
        $this->restapi->response($response);
    }
}
