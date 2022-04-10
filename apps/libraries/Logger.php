<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Logger.php
 *  Path: application/libraries/Logger.php
 *  Description: This class is used for write logs based on their levels.
 * 
 * Function Added:
 * 1. debug($message)    -- Used for write only debug log
 * 2. info($message)     -- Used for write info log
 * 3. error($message)    -- Used for write error log
 * 4. start()               -- This is used when want to know time take by process. This is used with end function
 * 5. end($object = "")     -- This is called after start function start.
 * 
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         16/04/2019              Created

 *
 */
if (!class_exists('Logger')) {

    class Logger {

        private $start;

        public function __construct() {
            $this->ci = & get_instance();
            $this->start = array();
        }

        public function debug($method, $message = "") {
            $this->insertLog('debug', "$method - $message");
        }

        public function info($method = "", $message = "") {
            $this->insertLog('info', "$method - $message");
        }

        public function error($method = "", $message = "") {
            $this->insertLog('error', "$method - $message");
        }

        public function queryLog($method = "", $query = "") {
            $this->info($method, $query);
        }

        /**
         * Script Running Time Debug
         * @param type $key
         */
        public function start($key = "log_start") {
            $this->start[$key] = $this->time();
        }

        public function end($object = "", $key = "log_start") {
            if (isset($this->start[$key])) {
                $start = $this->start[$key];
            } else {
                $start = $this->time();
                $this->ci->logger->error("No Logger key found. Key name: $key");
            }
            $now = $this->time();
            $timeTaken = (int) $now - (int) $start;
            $message = "Time taken by $object --> $timeTaken Seconds ---- Start : " . $start . " --- End : $now";
            $this->debug($message);
        }

        /**
         * 
         * @param type $type
         * @param type $message
         */
        private function insertLog($type, $message) {
            log_message($type, $this->oneline($message));
        }

        /**
         * Start Private Functions
         * @return type
         */
        private function time() {
            return microtime(true);
        }

        /**
         * This will remove multi line text for save logs
         * @param type $text
         * @return type
         */
        private function oneline($message) {
            return trim(preg_replace('/\s+/', ' ', $message));
        }

    }

}