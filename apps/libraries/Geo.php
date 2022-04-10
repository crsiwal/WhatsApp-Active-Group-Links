<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Geo.php
 *  Path: application/libraries/Geo.php
 *  Description: This is country and city data management library
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         26/06/2021              Created
 * 
 */

if (!class_exists('Geo')) {

    class Geo {

        private $ci;

        public function __construct() {
            $this->ci = &get_instance();
            $this->ci->load->model('GeoModel', 'mod_geo');
        }

        public function ip_ids($info) {
            clog("Calling", __METHOD__);
            $info["country_id"] = empty($info["country"]) ? 0 : $this->country(
                [
                    "name" => ($info["country"])
                ],
                "id"
            );

            $info["region_id"] = (empty($info["country_id"]) || empty($info["region"])) ? 0 : $this->region(
                [
                    "country_id" => $info["country_id"],
                    "name" => $info["region"]
                ],
                "id"
            );

            $info["city_id"] = (empty($info["country_id"]) || empty($info["region_id"]) || empty($info["city"])) ? 0 : $this->city(
                [
                    "country_id" => $info["country_id"],
                    "region_id" => $info["region_id"],
                    "name" => $info["city"]
                ],
                "id"
            );
            return $info;
        }

        /** Get Country Data */
        public function country($filter, $field = null, $single = true) {
            clog("Calling", __METHOD__);
            if ($single) {
                $filter = array_merge($filter, ["single" => true]);
            }
            $country = $this->_country($filter);
            return $single ? $this->_fields($country, $field) : $country;
        }

        /** Get Region Data */
        public function region($filter, $field = null, $single = true) {
            clog("Calling", __METHOD__);
            if ($single) {
                $filter = array_merge($filter, ["single" => true]);
            }
            $region = $this->_region($filter);
            return $single ? $this->_fields($region, $field) : $region;
        }

        /** Get City Data */
        public function city($filter, $field = null, $single = true) {
            clog("Calling", __METHOD__);
            if ($single) {
                $filter = array_merge($filter, ["single" => true]);
            }
            $city = $this->_city($filter);
            return $single ? $this->_fields($city, $field) : $city;
        }

        private function _country($filter, $fields = [], $limit = 500, $offset = 0) {
            clog("Calling", __METHOD__);
            $fields = is_array($fields) ? $fields : [$fields];
            $cache_key = cache_key(array_merge(["country"], $filter, $fields, [$limit, $offset]));
            if (!$response = $this->ci->cache->get($cache_key)) {
                if ($response = $this->ci->mod_geo->country($filter, $fields)) {
                    $this->ci->cache->save($cache_key, $response, cache_time("year"));
                } else {
                    if ($response = $this->ci->mod_geo->add_country($filter, $fields, true)) {
                        $this->ci->cache->save($cache_key, $response, cache_time("year"));
                    }
                }
            } else {
                clog("Got Country Details From Cache", __METHOD__);
            }
            return $response;
        }

        private function _region($filter, $fields = [], $limit = 500, $offset = 0) {
            clog("Calling", __METHOD__);
            $fields = is_array($fields) ? $fields : [$fields];
            $cache_key = cache_key(array_merge(["region"], $filter, $fields, [$limit, $offset]));
            if (!$response = $this->ci->cache->get($cache_key)) {
                if ($response = $this->ci->mod_geo->region($filter, $fields)) {
                    $this->ci->cache->save($cache_key, $response, cache_time("year"));
                } else {
                    if ($response = $this->ci->mod_geo->add_region($filter, $fields, true)) {
                        $this->ci->cache->save($cache_key, $response, cache_time("year"));
                    }
                }
            } else {
                clog("Got Region Details From Cache", __METHOD__);
            }
            return $response;
        }

        private function _city($filter, $fields = [], $limit = 500, $offset = 0) {
            clog("Calling", __METHOD__);
            $fields = is_array($fields) ? $fields : [$fields];
            $cache_key = cache_key(array_merge(["city"], $filter, $fields, [$limit, $offset]));
            if (!$response = $this->ci->cache->get($cache_key)) {
                if ($response = $this->ci->mod_geo->city($filter, $fields)) {
                    $this->ci->cache->save($cache_key, $response, cache_time("year"));
                } else {
                    if ($response = $this->ci->mod_geo->add_city($filter, $fields, true)) {
                        $this->ci->cache->save($cache_key, $response, cache_time("year"));
                    }
                }
            } else {
                clog("Got City Details From Cache", __METHOD__);
            }
            return $response;
        }

        private function _fields($data, $fields) {
            clog("Calling", __METHOD__);
            if (is_array($fields)) {
                $response = [];
                if (count($fields) == 0) {
                    foreach ($data as $key => $value) {
                        $response[$key] = $value;
                    }
                } else {
                    foreach ($fields as $field) {
                        $response[$field] = isset($data->$field) ? $data->$field : null;
                    }
                }
            } else {
                $response = isset($data->$fields) ? $data->$fields : null;
            }
            return $response;
        }
    }
}
