<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: assets.php
 *  Path: app/config/assets.php
 *  Description: This is collection of Javascript And Css Files.
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         30/05/2021              Created
 *
 */

$config['assets_css'] =  array(
	'bootstrap-css' => 'vendor/bootstrap.min',
	'toast-css' => 'vendor/toast.min',
	'fontawesome-link-css' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
	'custom-css' => 'style',
);

$config['assets_js'] =  array(
	"jquery" => "vendor/jquery-3.4.1.min",
	"popper" => "vendor/popper.min",
	"bootstrap" => "vendor/bootstrap.min",
	"toast" => "vendor/toast.min",
	"config" => "",
	"page" => "pages",
	"app" => "app",
	"sidebar" => "sidebar",
	"event" => "events",
);
