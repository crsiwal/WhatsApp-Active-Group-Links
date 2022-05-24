<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: GroupModel.php
 *  Path: application/models/GroupModel.php
 *  Description: 
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         06/06/2021              Created
 *
 */
if (!class_exists('GroupModel')) {

    class GroupModel extends CI_Model {

        public function __construct() {
            parent::__construct();
        }

        public function groups($filter, $limit, $offset, $fields = []) {
            try {
                if (count($fields) == 0) {
                    $group_url = isset($filter["single"]) ? whatsapp_join_url("", true) : group_url("", true);
                    $thumbnail_url = url("", true);
                    $category_url = category_url("", true);
                    $this->db->select("a.name, b.name as category, CONCAT('$category_url', b.slug) as curl, a.invite_key, CONCAT('$group_url', a.invite_key) as url, CONCAT('$thumbnail_url', a.icon_url) as icon");
                    $this->db->from("groups as a");
                    $this->db->join("category b", "b.id = a.category_id");
                    $this->db->where("a.status", 1);
                } else {
                    $this->db->select($fields);
                    $this->db->from("groups as a");
                }

                if (isset($filter["id"])) {
                    $this->db->where("a.id", $filter["id"]);
                }

                if (isset($filter["invite_key"])) {
                    $this->db->where("a.invite_key", $filter["invite_key"]);
                }

                if (isset($filter["user_id"])) {
                    $this->db->where("a.user_id", $filter["user_id"]);
                }

                if (isset($filter["status"])) {
                    $this->db->where_in("a.status", $filter["status"]);
                }

                if (isset($filter["category_id"])) {
                    $this->db->group_start();
                    $this->db->where("a.category_id", $filter["category_id"]);
                    $this->db->or_where("a.subcategory_id", $filter["category_id"]);
                    $this->db->group_end();
                }

                if (isset($filter["subcategory_id"])) {
                    $this->db->where("a.subcategory_id", $filter["subcategory_id"]);
                }

                if (isset($filter["country_id"])) {
                    $this->db->where("a.country_id", $filter["country_id"]);
                }

                if (isset($filter["city_id"])) {
                    $this->db->where("a.city_id", $filter["city_id"]);
                }

                if (!isset($filter["single"])) {
                    $this->db->order_by('a.id');
                    $this->db->limit($limit, $offset);
                }

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


        public function trending_groups($limit, $offset) {
            try {
                $group_url = group_url("", true);
                $thumbnail_url = url("", true);
                $category_url = category_url("", true);
                $this->db->select("b.name, b.invite_key, CONCAT('$group_url', b.invite_key) as url, CONCAT('$thumbnail_url', b.icon_url) as icon, c.name as category, CONCAT('$category_url', c.slug) as curl");
                $this->db->from("trending_groups as a");
                $this->db->join("groups as b", "a.group_id=b.id");
                $this->db->join("category as c", "b.category_id=c.id");
                $this->db->where("b.status", 1);
                $this->db->order_by('b.id');
                $this->db->limit($limit, $offset);
                $query = $this->db->get();
                return ($query->num_rows() > 0) ? $query->result() : [];
            } catch (Exception $e) {
                $this->logger->errorLog(__METHOD__, $e->getMessage());
                return [];
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function create($deatils) {
            try {
                $data = array(
                    "user_id" => $deatils["user_id"],
                    "name" => $deatils["name"],
                    "invite_key" => $deatils["invite_key"],
                    "category_id" => $deatils["category_id"],
                    "subcategory_id" => $deatils["subcategory_id"],
                    "country_id" => $deatils["country_id"],
                    "region_id" => $deatils["region_id"],
                    "city_id" => $deatils["city_id"],
                    "icon_url" => $deatils["icon_url"],
                    "status" => 1,
                    "create_time" => get_time(),
                    "update_time" => get_time(),
                );
                $this->db->insert('groups', $data);
                return ($this->db->affected_rows() == 1) ? $this->db->insert_id() : FALSE;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }
    }
}
