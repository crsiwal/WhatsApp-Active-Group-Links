<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: ConfigsModel.php
 *  Path: application/models/ConfigsModel.php
 *  Description: 
 * 
 * Function List
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         09/03/2020              Created

 *
 */
if (!class_exists('ConfigsModel')) {

    class ConfigsModel extends CI_Model {

        Public function __construct() {
            parent::__construct();
        }

        public function insert($config) {
            try {
                $data = array(
                    "blog_id" => isset($config["blog_id"]) ? $config["blog_id"] : get_active_blog_id(),
                    "user_id" => isset($config["user_id"]) ? $config["user_id"] : get_logged_in_user_id(),
                    "setting" => isset($config["setting"]) ? $config["setting"] : "",
                    "value" => isset($config["value"]) ? $config["value"] : "",
                    "update_time" => get_time()
                );
                $this->db->insert('system_config', $data);
                return ($this->db->affected_rows() == 1) ? TRUE : FALSE;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function update($id, $value) {
            try {
                $data = array(
                    "value" => $value,
                    "update_time" => get_time()
                );
                $this->db->where('id', $id)->update('system_config', $data);
                return ($this->db->affected_rows() <= 1) ? TRUE : FALSE;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function delete($id, $setting) {
            try {
                $this->db->delete('system_config', array("id" => $id, "setting" => $setting));
                return ($this->db->affected_rows() == 1) ? TRUE : FALSE;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function countChange($id, $count, $increase = TRUE) {
            try {
                $data = array(
                    "update_time" => get_time()
                );
                $set = ($increase) ? "value+$count" : "value-$count";
                $this->db->where('id', $id)->set('value', $set, FALSE)->update('system_config', $data);
                return ($this->db->affected_rows() <= 1) ? TRUE : FALSE;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

    }

}