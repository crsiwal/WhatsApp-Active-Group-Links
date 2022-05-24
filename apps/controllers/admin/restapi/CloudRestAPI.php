<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: CloudRestAPI.php
 *  Path: apps/controllers/CloudRestAPI.php
 *  Description: Admin dashboard Cloud Upload Files REST API.
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         28/02/2022              Created
 *  
 */

class CloudRestAPI extends CI_Controller {

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
}
