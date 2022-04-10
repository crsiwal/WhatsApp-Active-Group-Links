<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: TagsModel.php
 *  Path: application/models/TagsModel.php
 *  Description: 
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         26/06/2021              Created

 *
 */
if (!class_exists('TagsModel')) {

    class TagsModel extends CI_Model {

        public function __construct() {
            parent::__construct();
        }

        public function search($tags = [], $field = "name", $by_field = "name") {
            if (is_array($tags) && count($tags) > 0) {
                $query = $this->db->where_in($by_field, $tags)->get("tags");
                return ($query->num_rows() > 0) ? array_column($query->result_array(), $field) : [];
            }
            return [];
        }

        public function add_multiple($multiple_tags) {
            return $this->db->insert_batch("tags", $multiple_tags);
        }

        public function map_these_tags($group_id, $group_tags) {
            try {
                $tag_ids = $this->search($group_tags, "id");
                if (is_array($tag_ids) && count($tag_ids) > 0) {
                    $batch_data = [];
                    $user_id = get_logged_in_user_id();
                    foreach ($tag_ids as $tag_id) {
                        array_push($batch_data, [
                            "user_id" => $user_id,
                            "group_id" => $group_id,
                            "tag_id" => $tag_id,
                        ]);
                    }

                    /** Map new tags with user and group */
                    if ($status = $this->db->insert_batch("group_tags", $batch_data)) {
                        $this->logger->queryLog(__METHOD__, $this->db->last_query());

                        /** increase number of groups to all associated tag's */
                        $this->groups($tag_ids, TRUE);
                    }
                    return $status;
                }
                return FALSE;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function clear_existing_mapping($group_id) {
            try {
                /* Get Tag Id's associated with group previously */
                $tag_ids = $this->group_tags_ids($group_id);
                if (is_array($tag_ids) && count($tag_ids) > 0) {
                    /** Decrease number of groups to all previously associated tag's */
                    $this->groups($tag_ids, FALSE);

                    /** Delete Tag Id's associated with group previously */
                    $this->db->where_in("tag_id", $tag_ids);
                    $this->db->delete("group_tags");
                    return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
                }
                return TRUE;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function group_tags_ids($group_id) {
            try {
                $where = [
                    "user_id" => get_logged_in_user_id(),
                    "group_id" => $group_id
                ];
                $this->db->select("tag_id");
                $this->db->from("group_tags");
                $this->db->where($where);
                $query = $this->db->get();
                return ($query->num_rows() > 0) ? array_column($query->result_array(), "tag_id") : [];
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function groups($tag_ids, $increase = TRUE) {
            try {
                if (is_array($tag_ids) && count($tag_ids) > 0) {
                    $this->db->where_in("id", $tag_ids);
                    $this->db->set('groups', (($increase) ? "groups+1" : "groups-1"), FALSE);
                    $this->db->update('tags');
                    return ($this->db->affected_rows() <= 1) ? TRUE : FALSE;
                }
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }
    }
}
