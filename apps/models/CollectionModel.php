<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: CollectionModel.php
 *  Path: application/models/CollectionModel.php
 *  Description: Database Request handle
 * 
 * Function List
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         16/04/2019              Created

 *
 */
if (!class_exists('CollectionModel')) {

    class CollectionModel extends CI_Model {

        Public function __construct() {
            parent::__construct();
        }

        public function select($table, $cols, $condition = [], $order_by = [], $group_by = []) {
            try {
                $this->db->select($cols);
                $this->db->from($table);
                if (count($condition) > 0) {
                    $this->db->where($condition);
                }
                if (count($order_by) > 0) {
                    if (isset($order_by["name"])) {
                        $order_by = array($order_by);
                    }
                    foreach ($order_by as $order) {
                        $name = isset($order["name"]) ? $order["name"] : false;
                        $by = (isset($order["by"]) && $order["by"] == "desc") ? "DESC" : "ASC";
                        if ($name) {
                            $this->db->order_by($name, $by);
                        }
                    }
                }
                return $this->db->get()->result_array();
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function select_row($table, $cols, $condition = [], $order_by = [], $group_by = []) {
            try {
                $this->db->select($cols);
                $this->db->from($table);
                if (count($condition) > 0) {
                    $this->db->where($condition);
                }
                if (count($order_by) > 0) {
                    if (isset($order_by["name"])) {
                        $order_by = array($order_by);
                    }
                    foreach ($order_by as $order) {
                        $name = isset($order["name"]) ? $order["name"] : false;
                        $by = (isset($order["by"]) && $order["by"] == "desc") ? "DESC" : "ASC";
                        if ($name) {
                            $this->db->order_by($name, $by);
                        }
                    }
                }
                return $this->db->get()->row_array();
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function select_value($table, $cols, $condition = [], $order_by = [], $group_by = []) {
            try {
                $this->db->select($cols);
                $this->db->from($table);
                if (count($condition) > 0) {
                    $this->db->where($condition);
                }
                if (count($order_by) > 0) {
                    if (isset($order_by["name"])) {
                        $order_by = array($order_by);
                    }
                    foreach ($order_by as $order) {
                        $name = isset($order["name"]) ? $order["name"] : false;
                        $by = (isset($order["by"]) && $order["by"] == "desc") ? "DESC" : "ASC";
                        if ($name) {
                            $this->db->order_by($name, $by);
                        }
                    }
                }
                $data = $this->db->get()->row_array();
                return isset($data[$cols]) ? $data[$cols] : NULL;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function rows_count($table, $condition = [], $group_by = []) {
            try {
                if (count($condition) > 0) {
                    $this->db->where($condition);
                }
                return $this->db->get($table)->num_rows();
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return 0;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

    }

}