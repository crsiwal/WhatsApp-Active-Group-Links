<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Minify.php
 *  Path: application/libraries/Minify.php
 *  Description: This will minify the used css and javascript and create a cache of it.
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         24/05/2022              Created
 *
 */

if (!class_exists('Minify')) {

    class Minify {

        private $ci;
        private $css_minifier;
        private $js_minifier;
        private $assets;
        private $bundle;

        public function __construct() {
            $this->ci = &get_instance();
            $this->css_minifier = $this->ci->config->item("css_minifier");
            $this->js_minifier = $this->ci->config->item("js_minifier");
            $this->assets = asset_path("");
            $this->bundle = "";
        }

        public function reCache() {
            $status = true;
            $status = ($status) ? $this->minify("merge_css", $this->css_minifier, "css", "merge", true) : false;
            $status = ($status) ? $this->minify("merge_minify_css", $this->css_minifier, "css", "minify") : false;
            $status = ($status) ? $this->minify("merge_js", $this->js_minifier, "js", "merge", true) : false;
            $status = ($status) ? $this->minify("merge_minify_js", $this->js_minifier, "js", "minify") : false;
            if ($status) {
                $content = "<?php " . PHP_EOL . $this->bundle;
                file_put_contents(app_path("config/assets_bundle.php"), $content);
            }
            return $status;
        }

        public function minify($file_list_key, $serviceUrl, $fileType, $nameKey, $onlyMerge = FALSE) {
            $file_list = $this->ci->config->item($file_list_key);
            if (count($file_list) == 0) {
                return true;
            } else {
                $rawData = $this->joinRawData($file_list, $this->assets, $fileType);
                $minifiedContent = ($onlyMerge) ? $rawData : $this->getMinifiedContent($rawData, $serviceUrl);
                if (strlen($minifiedContent) > 100) {
                    $this->saveMinifiedOutput($this->assets, $fileType, $rawData, $minifiedContent, $nameKey);
                    return true;
                } else {
                    return false;
                }
            }
        }

        private function joinRawData($file_list, $basePath, $fileType) {
            $rawdata = "";
            foreach ($file_list as $file) {
                $rawdata .= file_get_contents($basePath . $fileType . "/" . $file . "." . $fileType);
            }
            return $rawdata;
        }

        private function getMinifiedContent($rawdata, $serviceUrl) {
            if ($rawdata != "") {
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $serviceUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
                    CURLOPT_POSTFIELDS => http_build_query(["input" => $rawdata]),
                    CURLOPT_SSL_VERIFYHOST => FALSE,
                    CURLOPT_SSL_VERIFYPEER => FALSE
                ]);
                $minified = curl_exec($ch);
                if (curl_errno($ch)) {
                    $error_msg = curl_error($ch);
                    die($error_msg);
                }
                curl_close($ch);
                return $minified;
            }
            return FALSE;
        }

        private function saveMinifiedOutput($outputPath, $fileType, $rawData, $minifiedContent, $nameKey) {
            $randomfilename = unique_key();
            $rawOutputFilePath = $outputPath . $fileType . "/cache/" . $randomfilename . "." . $fileType;
            file_put_contents($rawOutputFilePath, $rawData);

            $outputFilePath = $outputPath . $fileType . "/cache/" . $randomfilename . ".min." . $fileType;
            file_put_contents($outputFilePath, $minifiedContent);
            $type = "";
            switch ($fileType) {
                case 'js':
                    $type = '$config["minified_js_files"]["' . $nameKey . '"] = "cache/' . $randomfilename . '.min";' . PHP_EOL;
                    break;
                case 'css':
                    $type = '$config["minified_css_files"]["' . $nameKey . '"] = "cache/' . $randomfilename . '.min";' . PHP_EOL;
                    break;
            }
            $this->bundle .= $type;
            echo $type;
        }
    }
}
