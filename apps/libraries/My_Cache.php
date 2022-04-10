<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: My_Cache.php
 *  Path: application/libraries/My_Cache.php
 *  Description: This is Autoload Cache library
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         05/06/2021              Created
 *
 */

if (!class_exists('My_Cache')) {

    class My_Cache {
        protected $ci;

        public function __construct() {
            $this->ci = &get_instance();
            $this->initiate_cache();
        }

        public function initiate_cache() {
            $this->ci->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        }
    }
}
