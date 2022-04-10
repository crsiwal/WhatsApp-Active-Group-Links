<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: IpInfo.php
 *  Path: application/libraries/IpInfo.php
 *  Description: This is IP Services management library
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         04/07/2021              Created
 *
 */

if (!class_exists('IpInfo')) {

    class IpInfo {

        private $ci;
        private $ip;
        private $url;
        private $fields;
        private $cache_prefix;

        public function __construct() {
            $this->ci = &get_instance();
            $this->ci->load->library("Geo");
        }

        public function get($ip, $db_ids = FALSE) {
            clog("Calling", __METHOD__);
            $this->ip = $ip;
            if ($info = $this->_geoplugin($ip))
                null;
            elseif ($info = $this->_IpInfo($ip))
                null;
            else
                $info = [];
            return ($db_ids) ? $this->ci->geo->ip_ids($info) : $info;
        }

        private function _IpInfo($ip) {
            clog("Calling", __METHOD__);
            $this->url = "https://www.ipinfo.io/$ip";
            $this->fields = ["token" => "6721d009b03fc3"];
            $this->cache_prefix = "ipinfo";
            $info = $this->_curl();
            if (is_array($info) && isset($info["ip"])) {
                return [
                    "country" => isset($info["country"]) ? $this->ci->geo->country(["iso_two" => $info["country"]], "name") : "",
                    "region" => isset($info["region"]) ? $info["region"] : "",
                    "city" => isset($info["city"]) ? $info["city"] : "",
                    "timezone" => isset($info["timezone"]) ? $info["timezone"] : "",
                    "loc" => isset($info["loc"]) ? $info["loc"] : "",
                ];
            }
            return false;
        }

        private function _geoplugin($ip) {
            clog("Calling", __METHOD__);
            $this->url = "http://www.geoplugin.net/php.gp";
            $this->fields = ["ip" => $ip];
            $this->cache_prefix = "geoplugin";
            $response = $this->_curl(FALSE);
            if (!empty($response)) {
                $info = unserialize($response);
                if (is_array($info) && isset($info["geoplugin_countryCode"])) {
                    $location = [
                        isset($info["geoplugin_latitude"]) ? $info["geoplugin_latitude"] : "",
                        isset($info["geoplugin_longitude"]) ? $info["geoplugin_longitude"] : "",
                    ];

                    return [
                        "country" => isset($info["geoplugin_countryName"]) ? $info["geoplugin_countryName"] : "",
                        "region" => isset($info["geoplugin_region"]) ? $info["geoplugin_region"] : "",
                        "city" => isset($info["geoplugin_city"]) ? $info["geoplugin_city"] : "",
                        "timezone" => isset($info["geoplugin_timezone"]) ? $info["geoplugin_timezone"] : "",
                        "loc" => implode(",", $location),
                    ];
                }
            }
            return false;
        }

        private function _curl($decode = TRUE) {
            clog("Calling", __METHOD__);
            $cache_key = cache_key([$this->cache_prefix, $this->ip]);
            if (!$response = $this->ci->cache->get($cache_key)) {
                $request = [
                    "url" => $this->url,
                    "fields" => $this->fields
                ];
                $response = $this->ci->curl->get($request, [], $decode);
                if ($response) {
                    $this->ci->cache->save($cache_key, $response, cache_time("ip"));
                }
            } else {
                clog("Get IP Details From Cache", __METHOD__);
            }
            return $response;
        }
    }
}
