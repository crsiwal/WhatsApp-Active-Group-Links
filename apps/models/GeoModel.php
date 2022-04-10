<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: GeoModel.php
 *  Path: application/models/GeoModel.php
 *  Description: 
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         04/07/2021              Created

 *
 */
if (!class_exists('GeoModel')) {

    class GeoModel extends CI_Model {

        public function __construct() {
            parent::__construct();
        }

        public function country($filter, $fields = [], $limit = 500, $offset = 0) {
            try {
                if (count($fields) == 0) {
                    $fields = ["id", "name", "iso_two", "iso_three", "num_iso", "isd", "enabled"];
                }
                $this->db->select($fields)->from("country");

                if (isset($filter["id"])) {
                    $this->db->where("id", $filter["id"]);
                } elseif (isset($filter["ids"])) {
                    $this->db->where_in("id", $filter["ids"]);
                }

                if (isset($filter["name"])) {
                    $this->db->where("LOWER(name)", strtolower($filter["name"]));
                }

                if (isset($filter["iso_two"])) {
                    $this->db->where("LOWER(iso_two)", strtolower($filter["iso_two"]));
                }

                if (isset($filter["iso_three"])) {
                    $this->db->where("LOWER(iso_three)", strtolower($filter["iso_three"]));
                }

                if (isset($filter["num_iso"])) {
                    $this->db->where("num_iso", $filter["num_iso"]);
                }

                if (isset($filter["isd"])) {
                    $this->db->where("isd", $filter["isd"]);
                }

                if (!isset($filter["single"])) {
                    $this->db->order_by('id');
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

        public function add_country($filter, $fields, $return = FALSE) {
            try {
                $data = array(
                    "name" => isset($filter["name"]) ? $filter["name"] : "",
                    "enabled" => false,
                );
                $this->db->where([
                    "LOWER(name)" => strtolower($data["name"])
                ]);
                $result = $this->db->get('country');

                if ($result->num_rows() > 0) {
                    return $this->country($filter, $fields);
                } else {
                    $this->db->insert('country', $data);
                    if ($return) {
                        $response = $this->country($filter, $fields);
                    } else {
                        return ($this->db->affected_rows() == 1) ? TRUE : FALSE;
                    }
                }
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function region($filter, $fields = [], $limit = 500, $offset = 0) {
            try {
                if (count($fields) == 0) {
                    $fields = ["id", "country_id", "name", "enabled"];
                }
                $this->db->select($fields)->from("region");

                if (isset($filter["id"])) {
                    $this->db->where("id", $filter["id"]);
                } elseif (isset($filter["ids"])) {
                    $this->db->where_in("id", $filter["ids"]);
                }

                if (isset($filter["name"])) {
                    $this->db->where("LOWER(name)", strtolower($filter["name"]));
                }

                if (isset($filter["country_id"])) {
                    $this->db->where("country_id", $filter["country_id"]);
                }

                if (!isset($filter["single"])) {
                    $this->db->order_by('id');
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

        public function add_region($filter, $fields, $return = FALSE) {
            try {
                $data = array(
                    "country_id" => isset($filter["country_id"]) ? $filter["country_id"] : 0,
                    "name" => isset($filter["name"]) ? $filter["name"] : "",
                    "enabled" => true,
                );
                $this->db->where([
                    "country_id" => $data["country_id"],
                    "LOWER(name)" => strtolower($data["name"])
                ]);
                $result = $this->db->get('region');

                if ($result->num_rows() > 0) {
                    return $this->region($filter, $fields);
                } else {
                    $this->db->insert('region', $data);
                    if ($return) {
                        return $this->region($filter, $fields);
                    } else {
                        return ($this->db->affected_rows() == 1) ? TRUE : FALSE;
                    }
                }
            } catch (Exception $e) {
                $this->logger->error(__METHOD__, $e->getMessage());
                return FALSE;
            } finally {
                $this->logger->queryLog(__METHOD__, $this->db->last_query());
            }
        }

        public function city($filter, $fields = [], $limit = 500, $offset = 0) {
            try {
                if (count($fields) == 0) {
                    $fields = ["id", "country_id", "region_id", "name", "enabled"];
                }
                $this->db->select($fields)->from("city");

                if (isset($filter["id"])) {
                    $this->db->where("id", $filter["id"]);
                } elseif (isset($filter["ids"])) {
                    $this->db->where_in("id", $filter["ids"]);
                }

                if (isset($filter["name"])) {
                    $this->db->where("LOWER(name)", strtolower($filter["name"]));
                }

                if (isset($filter["country_id"])) {
                    $this->db->where("country_id", $filter["country_id"]);
                }

                if (isset($filter["region_id"])) {
                    $this->db->where("region_id", $filter["region_id"]);
                }

                if (!isset($filter["single"])) {
                    $this->db->order_by('id');
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

        public function add_city($filter, $fields, $return = FALSE) {
            try {
                $data = array(
                    "country_id" => isset($filter["country_id"]) ? $filter["country_id"] : 0,
                    "region_id" => isset($filter["region_id"]) ? $filter["region_id"] : 0,
                    "name" => isset($filter["name"]) ? $filter["name"] : "",
                    "enabled" => true,
                );
                $this->db->where([
                    "country_id" => $data["country_id"],
                    "region_id" => $data["region_id"],
                    "LOWER(name)" => strtolower($data["name"])
                ]);
                $result = $this->db->get('city');

                if ($result->num_rows() > 0) {
                    return $this->city($filter, $fields);
                } else {
                    $this->db->insert('city', $data);
                    if ($return) {
                        return $this->city($filter, $fields);
                    } else {
                        return ($this->db->affected_rows() == 1) ? TRUE : FALSE;
                    }
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
