<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Curl.php
 *  Path: application/libraries/Curl.php
 *  Description: 
 *  This library used for handle http requests
 * 
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         28/01/2020              Created
 *
 */
if (!class_exists('Curl')) {

    class Curl {

        private $ci;
        private $method;
        private $header;
        private $decode;
        private $content_type;

        function __construct() {
            $this->ci = &get_instance();
        }

        /* Single Request Curl */

        public function get($request, $header = [], $decode = TRUE, $content_type = "json", $curl = TRUE) {
            if ($curl) {
                $this->config("GET", $header, $decode, $content_type);
                return $this->run($request);
            } else {
                if (is_array($request['fields']) && count($request['fields']) > 0) {
                    $request["url"] = sprintf("%s?%s", $request["url"], http_build_query($request['fields']));
                }
                return ($this->decode) ? json_decode(file_get_contents($request["url"])) : file_get_contents($request["url"]);
            }
        }

        public function post($request, $header = [], $decode = TRUE, $content_type = "array") {
            $this->config("POST", $header, $decode, $content_type);
            return $this->run($request);
        }

        public function raw($request, $header = [], $decode = TRUE, $content_type = "array") {
            $this->config("RAW", $header, $decode, $content_type);
            return $this->run($request);
        }

        public function put($request, $header = [], $decode = TRUE, $content_type = "array") {
            $this->config("PUT", $header, $decode, $content_type);
            return $this->run($request);
        }

        public function delete($request, $header = [], $decode = TRUE, $content_type = "array") {
            $this->config("DELETE", $header, $decode, $content_type);
            return $this->run($request);
        }

        public function save($url, $path, $filename) {
            if (!is_dir($path)) {
                mkdir($path, 0777, TRUE);
            }
            $data = $this->get(["url" => $url], [], FALSE);
            return write_file(path($path . $filename), $data);
        }

        public function html($url, $fields = []) {
            $this->config("GET", [], FALSE, "html");
            $request = [
                "url" => $url,
                "fields" => $fields
            ];
            return $this->run($request);
        }

        /* Multiple Request CURL */
        public function multi_get($request, $curl = TRUE) {
            if ($curl) {
                return $this->run($request, TRUE);
            } else {
                if (is_array($request['fields']) && count($request['fields']) > 0) {
                    $request["url"] = sprintf("%s?%s", $request["url"], http_build_query($request['fields']));
                }
                return ($this->decode) ? json_decode(file_get_contents($request["url"])) : file_get_contents($request["url"]);
            }
        }

        public function multi_post($request, $header = [], $decode = TRUE, $content_type = "json") {
            return $this->run($request, TRUE);
        }

        public function config($method = "GET", $header = [], $decode = TRUE, $content_type = "json") {
            $this->content_type = $content_type;
            $default_header = array();
            $header_content_type = $this->get_content_type();
            if (!empty($header_content_type)) {
                array_push($default_header, $header_content_type);
            }
            $this->method = (in_array(strtoupper($method), ["GET", "POST", "PUT", "DELETE", "RAW", "FILE"])) ? strtoupper($method) : "GET";
            $this->decode = (!$decode) ? FALSE : TRUE;
            $this->header = (is_array($header) ? array_merge($default_header, $header) : $header);
        }

        public function run($request, $multi = FALSE) {
            return ($multi) ? $this->curl_multi($request) : $this->curl_single($request);
        }

        /*
          #################################################################################################
          ################################# Private Functions #############################################
          ########################## Only Call Using Public Function ######################################
          #################################################################################################
         */

        /**
         * 
         * @param type $request
         * @return type
         */
        private function curl_single($request) {
            $ch = curl_init();
            $return = FALSE;
            if (!$ch || !isset($request['url'])) {
                $this->ci->logger->error("Couldn't initialize a cURL handle");
            } else {
                $url = $request['url'];
                $data = (isset($request['fields'])) ? $request['fields'] : array();
                switch ($this->method) {
                    case "POST":
                        if (is_array($data) && count($data) > 0) {
                            (in_array($this->content_type, ["blogger_json"])) ? curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST") : "";
                            curl_setopt($ch, CURLOPT_POST, TRUE);
                            $data = $this->content_type_process($data);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        }
                        break;
                    case "RAW":
                        (in_array($this->content_type, ["octet_stream"])) ? curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST") : "";
                        curl_setopt($ch, CURLOPT_POST, TRUE);
                        $data = $this->content_type_process($data);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        break;
                    case "PUT":
                        if (is_array($data) && count($data) > 0) {
                            ($this->content_type == "blogger_json") ? curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT") : "";
                            curl_setopt($ch, CURLOPT_POST, TRUE);
                            $data = $this->content_type_process($data);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        }
                        break;
                    case "DELETE":
                        if (is_array($data) && count($data) > 0) {
                            ($this->content_type == "blogger_json") ? curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE") : "";
                            curl_setopt($ch, CURLOPT_POST, TRUE);
                            $data = $this->content_type_process($data);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        }
                        break;
                    default:
                        if (is_array($data) && count($data) > 0) {
                            $url = sprintf("%s?%s", $url, http_build_query($data));
                        }
                }
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                if ($this->method != "GET" && $this->content_type == "blogger_json") {
                    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                }

                if ($this->method == "GET" && $this->content_type == "html") {
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0');
                    curl_setopt($ch, CURLOPT_COOKIEFILE, asset_path("cookie/cookie.txt")); //set cookie file
                    curl_setopt($ch, CURLOPT_COOKIEJAR, asset_path("cookie/cookie.txt")); //set cookie jar
                    curl_setopt($ch, CURLOPT_HEADER, false); // don't return headers
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); // follow redirects
                    curl_setopt($ch, CURLOPT_ENCODING, ""); // follow redirects
                    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE); // set referer on redirect
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // timeout on connect
                    curl_setopt($ch, CURLOPT_TIMEOUT, 120); // timeout on response
                    curl_setopt($ch, CURLOPT_MAXREDIRS, 10); // stop after 10 redirects
                }

                $response = curl_exec($ch);
                if (curl_errno($ch)) {
                    $curl_info = curl_getinfo($ch);
                    $this->ci->sessions->set_curl_error(array(
                        "error" => curl_error($ch),
                        "code" => curl_errno($ch),
                        "http_code" => isset($curl_info["http_code"]) ? $curl_info["http_code"] : ""
                    ));
                    $this->ci->logger->error(curl_error($ch));
                    $this->ci->logger->info(json_encode($curl_info));
                } else {
                    $curl_info = curl_getinfo($ch);
                    $this->ci->logger->info(json_encode($curl_info));
                    $return = ($this->decode) ? json_decode($response, TRUE) : $response;
                }
                $this->ci->logger->info($response);
            }
            curl_close($ch);
            return $return;
        }

        /**
         * 
         * @param type $multiRequest
         * @return type
         */
        private function curl_multi($multiRequest = array()) {
            $mh = curl_multi_init();
            $response = array();
            $curl = array();
            foreach ($multiRequest as $curl_number => $request) {
                $url = $request['url'];
                $data = (isset($request['fields'])) ? $request['fields'] : array();
                switch ($this->method) {
                    case "POST":
                        curl_setopt($curl[$curl_number], CURLOPT_POST, TRUE);
                        if (is_array($data) && count($data) > 0) {
                            curl_setopt($curl[$curl_number], CURLOPT_POSTFIELDS, $data);
                        }
                        break;
                    case "PUT":
                        curl_setopt($curl[$curl_number], CURLOPT_CUSTOMREQUEST, "PUT");
                        if (is_array($data) && count($data) > 0) {
                            curl_setopt($curl[$curl_number], CURLOPT_POSTFIELDS, $data);
                        }
                        break;
                    default:
                        if (is_array($data) && count($data) > 0) {
                            $url = sprintf("%s?%s", $url, http_build_query($data));
                        }
                }
                $curl[$curl_number] = curl_init($url);
                curl_setopt($curl[$curl_number], CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($curl[$curl_number], CURLOPT_HTTPHEADER, $this->header);
                curl_multi_add_handle($mh, $curl[$curl_number]);
            }
            do {
                $status = curl_multi_exec($mh, $active);
                if ($active) {
                    curl_multi_select($mh, 300);
                }
                usleep(10000);
            } while ($active && $status == CURLM_OK);
            foreach ($multiRequest as $curl_number => $request) {
                $curl_response = curl_multi_getcontent($curl[$curl_number]);
                $response[$request['key']] = ($this->decode == TRUE) ? json_decode($curl_response, TRUE) : $curl_response;
                curl_multi_remove_handle($mh, $curl[$curl_number]);
            }
            curl_multi_close($mh);
            return $response;
        }

        private function get_content_type() {
            $content_types = array(
                "json" => "Content-Type: application/json",
                "urlencode" => "application/x-www-form-urlencoded",
                "octet_stream" => "application/octet-stream",
            );
            return (isset($content_types[$this->content_type])) ? $content_types[$this->content_type] : "";
        }

        private function content_type_process($data) {
            switch ($this->content_type) {
                case 'json':
                    $data = json_encode($data);
                    break;
            }
            return $data;
        }
    }
}
