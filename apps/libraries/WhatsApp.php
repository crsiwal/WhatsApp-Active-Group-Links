<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: WhatsApp.php
 *  Path: application/libraries/WhatsApp.php
 *  Description: This is WhatsApp Group management library
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         04/07/2021              Created
 *
 */

if (!class_exists('WhatsApp')) {

    class WhatsApp {

        private $ci;

        public function __construct() {
            $this->ci = &get_instance();
        }

        public function group_by_invite_link($invite_link) {
            $invite_key = whatsapp_invite_key($invite_link);
            $cache_key = cache_key(["whatsapp", $invite_key]);
            clog("WhatsApp Group Cache Key : " . $cache_key);

            if (!$response = $this->ci->cache->get($cache_key)) {
                clog("Getting Group data from WhatsApp");
                if ($response = $this->_group_by_invite_link($invite_key)) {
                    clog("Found a Valid Group. Saving details in cache.");
                    $this->ci->cache->save($cache_key, $response, cache_time("short"));
                }
            }
            return $response;
        }

        private function _group_by_invite_link($invite_key) {
            $this->ci->load->library("Html");
            $url = whatsapp_join_url($invite_key, true);
            $html = $this->ci->curl->html($url, ["lang" => "en"]);
            $meta = $this->ci->html->get($html, "meta", false, true);
            $group = $this->ci->html->metatag($meta, ["og:title", "og:image"]);

            if (isset($group["og:title"]) && !empty($group["og:title"]) && isset($group["og:image"]) && !empty($group["og:image"])) {
                $active = false;
                if (similar_text(strtolower($group["og:title"]), 'whatsappgroupinvite') < 17) {
                    $active = true;
                }
                if ($active) {
                    return [
                        "name" => $group["og:title"],
                        "icon_url" => $group["og:image"],
                        "invite_key" => $invite_key
                    ];
                }
            }
            return FALSE;
        }
    }
}
