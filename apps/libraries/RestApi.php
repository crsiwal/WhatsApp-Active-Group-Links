<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: ApiLibrary.php
 *  Path: apps/libraries/ApiLibrary.php
 *  Description: 
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         24/01/2020              Created
 *
 */
if (!class_exists('RestApi')) {

    class RestApi {

        private $ci;

        function __construct() {
            $this->ci = &get_instance();
        }

        public function is_ajax() {
            if (!$this->ci->input->is_ajax_request()) {
                $this->response(url("", true), FALSE, FALSE, TRUE);
            }
        }

        public function response($response, $error = FALSE, $data = array(), $redirect = FALSE) {
            $error = (is_array($response) && isset($response["error"]) && $response["error"] == TRUE) ? TRUE : $error;
            $response = (is_array($response) && isset($response["error"]) && isset($response["data"])) ? $response["data"] : $response;
            $send = [
                "status" => ($error) ? 'false' : 'true',
                "logout" => 'false',
                "action" => (!$redirect) ? "process" : "redirect",
                (!$error) ? 'data' : ((!$redirect) ? "msg" : "url") => $response,
            ];
            if (is_array($data) && count($data) > 0) {
                $send = array_merge($send, $data);
            }
            $this->output($send);
        }

        /**
         * Convert array to json for send response to request
         * @param type $result
         */
        public function output($result = array()) {
            header('Content-Type: application/json');
            echo json_encode($result);
            die();
        }
    }
}
