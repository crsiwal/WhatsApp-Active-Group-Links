<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Tags.php
 *  Path: application/libraries/Tags.php
 *  Description: This is tags data management library
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         26/06/2021              Created
 *
 */

if (!class_exists('Tags')) {

    class Tags {

        private $ci;

        public function __construct() {
            $this->ci = &get_instance();
            $this->ci->load->model('TagsModel', 'mod_tag');
        }

        public function map($group_id, $group_tags, $update = false) {
            if (!is_array($group_tags))
                return false;
            $tags = $this->ci->mod_tag->search($group_tags);
            $new_tags = array_diff($group_tags, $tags);
            if (is_array($new_tags) && count($new_tags) > 0) {
                $batch_data = [];
                foreach ($new_tags as $tag) {
                    if (!empty($tag))
                        array_push($batch_data, ["name" => $tag]);
                }
                if (count($batch_data) > 0)
                    $this->ci->mod_tag->add_multiple($batch_data);
            }
            if ($update) {
                $this->ci->mod_tag->clear_existing_mapping($group_id, $group_tags);
            }
            return $this->ci->mod_tag->map_these_tags($group_id, $group_tags);
        }
    }
}
