<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Category.php
 *  Path: application/libraries/Category.php
 *  Description: This is Category data management library
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         05/06/2021              Created
 *
 */

if (!class_exists('Category')) {

    class Category {

        private $ci;

        public function __construct() {
            $this->ci = &get_instance();
            $this->ci->load->model('CategoryModel', 'mod_category');
        }

        /**
         * Top Categories which will be show on the home page or any other page via block. 
         */
        public function category() {
            return $this->_category(0, 500, 0, 0);
        }

        /**
         * Top Categories which will be show on the home page or any other page via block. 
         */
        public function top($limit = 500, $offset = 0) {
            return $this->_category(0, $limit, $offset);
        }

        /**
         * These are whitelist category which will be show to website in all categories page. 
         */
        public function high($limit = 500, $offset = 0) {
            return $this->_category(1, $limit, $offset);
        }

        /**
         * These are weakly blacklist category. Which will be shown to website based on the 
         * admin decision like time, user intrest etc.. 
         */
        public function medium($limit = 500, $offset = 0) {
            return $this->_category(2, $limit, $offset);
        }

        /**
         * These are blacklist category. User will be able to add groups in this category but these will not publicly
         * avilable to the website. User can directly access these catgory groups.
         */
        public function low($limit = 500, $offset = 0) {
            return $this->_category(3, $limit, $offset);
        }

        public function top_child($slug, $limit = 500, $offset = 0) {
            $id = $this->ci->mod_category->get_id_by("slug", $slug);
            return $this->_category(3, $limit, $offset, $id);
        }

        public function addGroup($category_id, $sub_category_id) {
            return $this->ci->mod_category->groups($category_id, $sub_category_id, TRUE);
        }

        public function category_exist_by($field = "id", $value = "") {
            return $this->ci->mod_category->category_exist_by($field, $value);
        }

        public function add_category($name, $slug, $parent_id = 0, $view_state = 2) {
            $slug = convert_username($slug);
            $exist = $this->category_exist_by("slug", $slug);
            if ($exist) {
                $slug .= "_" . unique_key(6);
            }
            return $this->ci->mod_category->insert([
                "name" => $name,
                "slug" => $slug,
                "parent_id" => $parent_id,
                "view_state" => $view_state
            ]);
        }

        public function group_category($category_id, $sub_category_id) {
            $cache_key = cache_key(["category", $category_id, $sub_category_id]);
            $filter = [
                "ids" => [$category_id, $sub_category_id]
            ];
            if (!$response = $this->ci->cache->get($cache_key)) {
                $response = $this->ci->mod_category->categories($filter, 500, 0, ["id", "parent_id"]);

                // Cache Will be Stored for 8 hours.
                $this->ci->cache->save($cache_key, $response, cache_time());
            }
            $ids = array_column($response, "id");
            $parent_ids = array_column($response, "parent_id");
            return [
                "category_id" => in_array($category_id, $ids) ? $category_id : 0,
                "sub_category_id" => (in_array($sub_category_id, $ids) && in_array($category_id, $parent_ids)) ? $sub_category_id : 0
            ];
        }

        /**
         * Sub Category list by category ID 
         */
        public function subcategory() {
            $success = false;
            $response = [];
            $this->ci->load->helper(array('form', 'security'));
            $this->ci->load->library('form_validation', NULL, 'form');
            $this->ci->form->set_rules('_ctid', 'Category', 'trim|required|min_length[1]|max_length[30]|xss_clean');
            if ($this->ci->form->run() != FALSE) {
                $category_id = $this->ci->input->post('_ctid', true);
                if (!empty($category_id)) {
                    $success = true;
                    $response = $this->_category(1, 500, 0, $category_id);
                } else {
                    $this->ci->sessions->set_error("Unable to add this adsize");
                }
            } else {
                set_input_error();
            }
            return array(
                "error" => !$success,
                "data" => ($success) ? $response : $this->ci->sessions->get_error()
            );
        }

        public function by_slug($slug, $limit = 500, $offset = 0) {
            $filter = [
                "slug" => $slug,
                "single" => true,
                "enabled" => true
            ];
            $cache_key = cache_key([$slug, $limit, $offset, $filter['single']]);
            if (!$response = $this->ci->cache->get($cache_key)) {
                $response = $this->ci->mod_category->categories($filter, $limit, $offset);
                // Cache Will be Stored for 8 hours.
                $this->ci->cache->save($cache_key, $response, cache_time());
            }
            return (is_array($response) && count($response) == 0) ? false : $response;
        }

        private function _category($view_state, $limit, $offset, $parent_id = NULL) {
            $filter = [
                "view_state" => $view_state,
                "enabled" => true
            ];
            if (!is_null($parent_id)) {
                $filter["parent_id"] = $parent_id;
            }
            $cache_key = cache_key([$view_state, $parent_id, $limit, $offset]);
            if (!$response = $this->ci->cache->get($cache_key)) {
                $response = $this->ci->mod_category->categories($filter, $limit, $offset);
                // Cache Will be Stored for 8 hours.
                $this->ci->cache->save($cache_key, $response, cache_time());
            }
            return $response;
        }
    }
}
