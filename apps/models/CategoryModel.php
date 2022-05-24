<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: CategoryModel.php
 *  Path: application/models/CategoryModel.php
 *  Description: 
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         05/06/2021              Created

 *
 */
if (!class_exists('CategoryModel')) {

    class CategoryModel extends CI_Model {

        public function __construct() {
            parent::__construct();
        }

        public function categories($filter, $limit = 1000, $offset = 0, $fields = []) {
            try {
                if (count($fields) == 0) {
                    $default_category_url = url($this->config->item("category_default_banner"), true);
                    $icon_url = url("", true);
                    $category_url = category_url("", true);
                    $category_group_url = category_group_url("", true);
                    $fields = "id, name, slug, 
                    parent_id as pid,
                    view_state as vst,
                    CONCAT('$category_url', slug) as url, 
                    CONCAT('$category_group_url', slug) as gurl, 
                    case when icon_url IS NULL or icon_url = '' then '$default_category_url' else CONCAT('$icon_url', icon_url) end as icon,
                    groups, enabled as enb";
                }
                $this->db->select($fields);
                $this->db->from('category');

                if (isset($filter["enabled"])) {
                    $this->db->where("enabled", $filter["enabled"]);
                }
                if (isset($filter["id"])) {
                    $this->db->where("id", $filter["id"]);
                } elseif (isset($filter["ids"])) {
                    $this->db->where_in("id", $filter["ids"]);
                }

                if (isset($filter["parent_id"])) {
                    $this->db->where("parent_id", $filter["parent_id"]);
                }

                if (isset($filter["slug"])) {
                    $this->db->where("slug", $filter["slug"]);
                }

                if (isset($filter["view_state"])) {
                    $this->db->where("view_state <=", $filter["view_state"]);
                }

                $this->db->limit($limit, $offset);
                $query = $this->db->get();
                if (isset($filter["single"])) {
                    return ($query->num_rows() > 0) ? $query->row() : [];
                } else {
                    return ($query->num_rows() > 0) ? $query->result() : [];
                }
            } catch (Exception $e) {
                $this->logger->errorLog(__METHOD__, $e->getMessage());
                return [];
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function groups($category_id, $sub_category_id, $increase = TRUE) {
            try {
                $this->db->where_in("id", [$category_id, $sub_category_id]);
                $this->db->set('groups', (($increase) ? "groups+1" : "groups-1"), FALSE);
                $this->db->update('category');
                return ($this->db->affected_rows() <= 1) ? TRUE : FALSE;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function category_exist_by($field, $value, $skip_ids = []) {
            try {
                $this->db->select("id");
                $this->db->from('category');
                $this->db->where($field, $value);
                if (count($skip_ids) > 0) {
                    $this->db->where_not_in("id", $skip_ids);
                }
                $query = $this->db->get();
                return ($query->num_rows() > 0) ? true : false;
            } catch (Exception $e) {
                $this->logger->errorLog(__METHOD__, $e->getMessage());
                return false;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function get_id_by($field, $value) {
            try {
                $this->db->select("id");
                $this->db->from('category');
                $this->db->where($field, $value);
                $query = $this->db->get();
                return ($query->num_rows() > 0) ? $query->row()->id : false;
            } catch (Exception $e) {
                $this->logger->errorLog(__METHOD__, $e->getMessage());
                return false;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function insert($deatils) {
            try {
                $data = array(
                    "name" => $deatils["name"],
                    "slug" => $deatils["slug"],
                    "parent_id" => $deatils["parent_id"],
                    "view_state" => isset($deatils["view_state"]) ? $deatils["view_state"] : 2,
                    "icon_url" => "",
                    "enabled" => 1,
                    "groups" => 0,
                    "create_time" => get_time(),
                    "update_time" => get_time(),
                );
                $this->db->insert('category', $data);
                return ($this->db->affected_rows() == 1) ? $this->db->insert_id() : false;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return false;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function update($id, $data) {
            try {
                if (!isset($data["update_time"])) {
                    $data["update_time"] = get_time();
                }
                $this->db->where('id', $id)->update('category', $data);
                return ($this->db->affected_rows() > 0) ? true : false;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return false;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }
    }
}
