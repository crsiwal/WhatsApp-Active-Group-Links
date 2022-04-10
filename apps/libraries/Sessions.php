<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Sessions.php
 *  Path: application/libraries/Sessions.php
 *  Description: 
 *  This is session library which is used for maintain user data in session storage.
 *  All session is handled by Codeigniter itself. You can only change the session storage type via config.
 * 
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         24/01/2020              Created
 *
 */

if (!class_exists('Sessions')) {

    class Sessions {

        private $ci;
        private $sessionKey;

        public function __construct() {
            $this->ci = &get_instance();
            $this->sessionKey = array(
                'page' => '_session_current_page',
                'login' => '_session_user_logged_in',
                'userid' => '_session_logged_in_user_id',
                'message' => '_session_response_message',
                'formerror' => '_session_error_form_error',
                'curlerror' => '_session_error_curl_error',
            );
        }

        /** Setter and Getter for Active User * */
        public function set_user($user_id) {
            $this->set('userid', $user_id);
        }

        public function get_user() {
            return $this->get('userid');
        }

        /** Setter and Getter for User Login Status * */
        public function set_login() {
            $this->set('login', TRUE);
        }

        public function get_login() {
            return $this->get('login');
        }

        /** Setter and Getter for HTML form errors * */
        public function set_error($error) {
            $this->set('formerror', $error);
        }

        public function get_error() {
            $error = $this->get('formerror');
            $this->del('formerror');
            return $error;
        }

        /** Setter and Getter for Curl errors * */
        public function set_curl_error($error) {
            $this->set('curlerror', json_encode($error));
        }

        public function get_curl_error() {
            $error = $this->get('curlerror');
            $this->del('curlerror');
            return json_decode($error, true);
        }

        /** Setter and Getter for HTML form errors * */
        public function set_msg($error) {
            $this->set('message', $error);
        }

        public function get_msg() {
            $error = $this->get('message');
            $this->del('message');
            return $error;
        }

        /** Setter and Getter for HTML form errors * */
        public function set_page($name) {
            $this->set('page', $name);
        }

        public function get_page() {
            $pagename = $this->get('page');
            return ($pagename == null) ? "unknown" : $pagename;
        }

        /**
         * 
         * @param type $key
         * @param type $value
         */
        private function set($key, $value) {
            if (isset($this->sessionKey[$key])) {
                $this->ci->session->set_userdata($this->sessionKey[$key], $value);
            } else {
                $this->ci->logger->error("Sessions::set - Session Name $key is not allowed");
                return NULL;
            }
        }

        /**
         * 
         * @param type $key
         */
        private function get($key) {
            if (isset($this->sessionKey[$key])) {
                return $this->ci->session->userdata($this->sessionKey[$key]);
            } else {
                $this->ci->logger->error("Sessions::get - Session Name $key is not allowed");
                return NULL;
            }
        }

        /**
         * 
         * @param type $key
         */
        private function del($key) {
            if (isset($this->sessionKey[$key])) {
                $this->ci->session->unset_userdata($this->sessionKey[$key]);
            } else {
                $this->ci->logger->error("Sessions::del - Session Name $key is not allowed");
                return NULL;
            }
        }
    }
}
