<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: UserModel.php
 *  Path: application/models/UserModel.php
 *  Description: This is a user model.
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         26/06/2021              Created

 *
 */
if (!class_exists('UserModel')) {

    class UserModel extends CI_Model {

        public function __construct() {
            parent::__construct();
        }

        public function user_count() {
            try {
                return $this->db->count_all("users");
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return 0;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function add_user($userData) {
            try {
                $this->db->insert('users', $userData);
                return ($this->db->affected_rows() == 1) ? $this->db->insert_id() : FALSE;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function update_user($user_id, $userData) {
            try {
                if (!empty($user_id)) {
                    $this->db->where('id', $user_id);
                    $this->db->update('users', $userData);
                    return ($this->db->affected_rows() <= 1) ? $user_id : FALSE;
                }
                return FALSE;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function change_password($dbPassword, $user_id) {
            try {
                $this->db->where('id', $user_id);
                $this->db->set('reset', 'reset+1', FALSE);
                $this->db->update('users', array("password" => $dbPassword));
                return ($this->db->affected_rows() === 1) ? TRUE : FALSE;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function get_user_by_namekey($user_namekey = FALSE) {
            return $this->get_user("username", $user_namekey);
        }

        public function get_user_by_id($user_id = FALSE) {
            return $this->get_user("id", $user_id);
        }

        public function get_user_by_email($user_email = FALSE) {
            return $this->get_user("email", $user_email);
        }

        public function add_user_access_token($access_data) {
            try {
                $this->db->insert('access_token', $access_data);
                return ($this->db->affected_rows() == 1) ? $this->db->insert_id() : FALSE;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function get_access_token($user_id) {
            try {
                $this->db->select('id, access_token, refresh_token');
                $this->db->from('access_token');
                $this->db->where("user_id", $user_id);
                $query = $this->db->get();
                return $query->row();
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function update_user_access_token($user_id, $access_token, $refresh_token = NULL) {
            try {
                $data = array(
                    "access_token" => $access_token
                );
                if (!empty($refresh_token)) {
                    $data["refresh_token"] = $refresh_token;
                }
                $this->db->where('user_id', $user_id);
                $this->db->update('access_token', $data);
                return ($this->db->affected_rows() == 1) ? TRUE : FALSE;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function user_exist($value, $field, $exclude = "") {
            try {
                $this->db->where($field, $value);
                if (!empty($exclude)) {
                    $this->db->where_not_in("id", [$exclude]);
                }
                $query = $this->db->get("users");
                return $query->num_rows() > 0 ? true : false;
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function get_users($limit, $start) {
            $tmp = "";
            foreach ($this->config->item("user_roles") as $role => $name) {
                $tmp .= "when '$role' then '$name' ";
            }
            $case = trim($tmp);

            $tmp = "";
            foreach ($this->config->item("user_status") as $role => $name) {
                $tmp .= "when '$role' then '$name' ";
            }
            $status_case = trim($tmp);

            try {
                $this->db->select("id, name, username, email, password, case user_role $case else user_role end as userrole, user_role, case status $status_case else status end as user_status, status, reg_date, last_active");
                $this->db->limit($limit, $start);
                $this->db->order_by("id", "desc");
                $query = $this->db->get("users");
                return $query->num_rows() > 0 ? $query->result() : [];
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        private function get_user($key, $value) {
            $tmp = "";
            foreach ($this->config->item("user_roles") as $role => $name) {
                $tmp .= "when '$role' then '$name' ";
            }
            $case = trim($tmp);
            try {
                $this->db->select("id, name, username, email, password, case user_role $case else user_role end as userrole, user_role, status, reg_date, last_active");
                $this->db->from('users');
                $this->db->where($key, $this->db->escape_like_str($value));
                $query = $this->db->get();
                return $query->row();
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }
    }
}
