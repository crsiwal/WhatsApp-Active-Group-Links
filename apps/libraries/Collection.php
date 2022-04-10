<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Collection.php
 *  Path: application/libraries/Collection.php
 *  Description: 
 *  This library used for handle database requests
 * 
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         11/02/2020              Created
 *
 */
if (!class_exists('Collection')) {

    class Collection {

        private $ci;

        public function __construct() {
            $this->ci = & get_instance();
            $this->ci->load->model('CollectionModel', 'mod_coll');
        }

        public function select($table = FALSE, $fields = [], $condition = [], $wvalue = [], $order_by = [], $group_by = []) {
            $result = [];
            if ($table) {
                $where = where($condition, $wvalue);
                $cols = (is_string($fields)) ? $fields : (is_array($fields) ? implode(",", $fields) : "id");
                $result = $this->ci->mod_coll->select($table, $cols, $where, $order_by, $group_by);
            }
            return (is_array($result)) ? $result : [];
        }

        public function select_row($table = FALSE, $fields = [], $condition = [], $wvalue = [], $order_by = [], $group_by = []) {
            $result = [];
            if ($table) {
                $where = where($condition, $wvalue);
                $cols = (is_string($fields)) ? $fields : (is_array($fields) ? implode(",", $fields) : "id");
                $result = $this->ci->mod_coll->select_row($table, $cols, $where, $order_by, $group_by);
            }
            return (is_array($result)) ? $result : [];
        }

        public function select_value($table = FALSE, $fields = [], $condition = [], $wvalue = [], $order_by = [], $group_by = []) {
            if ($table) {
                $where = where($condition, $wvalue);
                $cols = (is_string($fields)) ? $fields : (is_array($fields) ? implode(",", $fields) : "id");
                return $this->ci->mod_coll->select_value($table, $cols, $where, $order_by, $group_by);
            }
            return NULL;
        }

        public function count($table = FALSE, $condition = [], $wvalue = [], $group_by = []) {
            if ($table) {
                $where = where($condition, $wvalue);
                return $this->ci->mod_coll->rows_count($table, $where, $group_by);
            }
            return 0;
        }

        public function exist($table = FALSE, $condition = [], $wvalue = []) {
            if ($table) {
                $where = where($condition, $wvalue);
                return ($this->ci->mod_coll->rows_count($table, $where) > 0) ? TRUE : FALSE;
            }
            return NULL;
        }

    }

}