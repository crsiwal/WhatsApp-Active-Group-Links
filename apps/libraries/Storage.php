<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Storage.php
 *  Path: application/libraries/Storage.php
 *  Description: This is Storage data management library
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         24/06/2021              Created
 *
 */

if (!class_exists('Storage')) {

    class Storage {

        private $ci;

        public function __construct() {
            $this->ci = &get_instance();
        }

        public function add_group() {
            $data = [
                $this->ci->input->ip_address(), // Ip Address Who submit this group
                $this->ci->input->post('_cid', true), // Category Id of this group
                $this->ci->input->post('_sid', true), // Sub Category Id of this group
                $this->ci->input->post('_lnk', true), // Invite link of this group
                $this->ci->input->post('_tag', true), // Tags of this group
            ];
            return $this->_store(implode("$", $data) . PHP_EOL);
        }

        public function add_group_manually($category_id, $sub_category_id, $invite_url, $tags = "") {
            $data = [
                $this->ci->input->ip_address(), // Ip Address Who submit this group
                $category_id,
                $sub_category_id,
                $invite_url,
                $tags
            ];
            return $this->_store(implode("$", $data) . PHP_EOL);
        }

        private function _store($data) {
            try {
                write_file(store_file_path(), $data, 'a');
                return TRUE;
            } catch (Exception $e) {
                $this->ci->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            }
        }
    }
}
