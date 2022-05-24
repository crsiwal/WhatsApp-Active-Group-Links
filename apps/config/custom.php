<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: custom.php
 *  Path: app/config/custom.php
 *  Description: These are configurations which used in project for diffrent purposes.
 *
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         29/05/2021              Created
 *
 */

$config['version'] = '1.0.0';
$config['minify_css'] = $config['minify_js'] = true;

/** Website Meta Data */
$config['site_name'] = 'Whatsapp Active Groups Invite Link';
$config['domain_name'] = 'activelinks.in';

/** Category Default Assets */
$config['category_default_banner'] = 'source/2022/03/03/bdb2652525fd4b9e38c8760cb8fe9606.jpg';

/** Cache Timeout details for sepecific Object */
$config['year_cache_time'] = 8760; // One year Hours, Data saved in cache for this time.
$config['halfyear_cache_time'] = 4380; // Six Months Hours, Data saved in cache for this time.
$config['quarterly_cache_time'] = 2190; // Three Months Hours, Data saved in cache for this time.
$config['monthly_cache_time'] = 720; // One Month Hours, Data saved in cache for this time.
$config['weekly_cache_time'] = 168; // One Week Hours, Data saved in cache for this time.
$config['daily_cache_time'] = 24; // One day Hours, Data saved in cache for this time.
$config['long_cache_time'] = 12; // Hours, Data saved in cache for this time.
$config['medium_cache_time'] = 6; // Hours, Data saved in cache for this time.
$config['short_cache_time'] = 3; // Hours, Data saved in cache for this time.
$config['small_cache_time'] = 1; // Hours, Data saved in cache for this time.

/* Group Related Configurations */
$config['show_groups_per_page'] = 12;

/** Whatsapp Options */
$config['whatsapp_base_url'] = "https://chat.whatsapp.com/invite";

/* Browser Local Cache configs */
// Number of minutes in which local cache data will be expire (integer value)
$config['js_config']['cache_time'] = 60;
$config['js_config']['debuging'] = false;
$config['js_config']['jstrace'] = false;
// This enable local storage of data in user system (TRUE / FALSE)
$config['js_config']['cache_enable'] = true;


/* User Types List */
$config['user_roles'] = [
	1 => "Administrator",
	2 => "Manager",
	3 => "Moderator",
	4 => "Publisher",
	5 => "Subscriber",
];

/* User Status List */
$config['user_status'] = [
	0 => "Pending",
	1 => "Active",
	2 => "Deactive",
	3 => "Blocked",
	4 => "Deleted",
];

/* Google Console Credentils */
$config['g-project'] = $config['site_name'];
$config['g-apikey'] = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
$config['g-client'] = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com";
$config['g-secret'] = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";

$config['g-oauth'] = "https://www.googleapis.com/oauth2/v4/token";
$config['g-authaccess'] = "https://accounts.google.com/o/oauth2/auth";
$config['g-authtoken'] = "https://accounts.google.com/o/oauth2/token";
$config['g-infotoken'] = "https://www.googleapis.com/oauth2/v1/tokeninfo";
$config['g-infouser'] = "https://www.googleapis.com/oauth2/v1/userinfo";
$config['g-apiaccess'] = array(
	"profile" => "https://www.googleapis.com/auth/userinfo.profile",
	"useremail" => "https://www.googleapis.com/auth/userinfo.email",
	//"blogger" => "https://www.googleapis.com/auth/blogger",
	//"googleplus" => "https://www.googleapis.com/auth/plus.me",
	//"googlephoto" => "https://www.googleapis.com/auth/photoslibrary",
);
$config['g-grantaccess'] = implode(' ', $config['g-apiaccess']);
$config['gb-endpoint'] = "https://www.googleapis.com/blogger/v3/";
$config['gp-endpoint'] = "https://photoslibrary.googleapis.com/v1/";
$config['yt-endpoint'] = "https://www.googleapis.com/youtube/v3/";

switch (ENVIRONMENT) {
	case 'production':
		$config['base_url'] = 'https://www.activelinks.in';
		$config['google_UA'] = 'UA-12266-1';
		break;
	case 'testing':
		$config['base_url'] = 'http://localhost.whatsapp.in';
		$config['google_UA'] = 'UA-12266-1';
		break;
	case 'development':
		$config['base_url'] = 'http://localhost.whatsapp.in';
		$config['google_UA'] = 'UA-12266-1';
		$config['minify_css'] = $config['minify_js'] = false;
		break;
}
