<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Group.php
 *  Path: application/libraries/Group.php
 *  Description: This is Group data management library
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         06/06/2021              Created
 *
 */

if (!class_exists('Group')) {

    class Group {

        private $ci;

        public function __construct() {
            $this->ci = &get_instance();
            $this->ci->load->model('GroupModel', 'mod_group');
        }

        public function trending($limit = 500, $offset = 0) {
            return $this->ci->mod_group->trending_groups($limit, $offset);
        }

        public function by_category($category_id, $limit = 500, $offset = 0) {
            $filter = [
                "category_id" => $category_id
            ];
            return $this->_groups($filter, $limit, $offset);
        }

        public function by_invite_key($invite_key) {
            $filter = [
                "invite_key" => $invite_key,
                "single" => true
            ];
            return $this->_groups($filter, 1, 0);
        }

        public function create($group) {
            return $this->ci->mod_group->create($group);
        }

        private function _groups($filter, $limit = 500, $offset = 0) {
            $cache_key = cache_key(array_merge(["groups"], $filter, [$limit, $offset]));

            if (!$response = $this->ci->cache->get($cache_key)) {
                $response = $this->ci->mod_group->groups($filter, $limit, $offset);

                // Cache Will be Stored for 8 hours.
                $this->ci->cache->save($cache_key, $response, cache_time());
            }

            return $response;
        }
    }
}
