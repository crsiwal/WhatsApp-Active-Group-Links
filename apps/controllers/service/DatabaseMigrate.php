<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: DatabaseMigrate.php
 *  Path: apps/controllers/service/DatabaseMigrate.php
 *  Description: Default Database setup
 * 	Database: CREATE DATABASE whatsapp DEFAULT CHARSET = utf8mb4 DEFAULT COLLATE = utf8mb4_unicode_ci;
 *  Syntex: php index.php service/DatabaseMigrate
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         22/09/2020              Created
 */
if (!class_exists('DatabaseMigrate')) {

	class DatabaseMigrate extends CI_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->dbforge();
		}

		public function index() {
			echo "Start Creating Tables....\n\n";
			$this->table_users(true);
			$this->table_configs(true);
			$this->table_access_token(true);
			$this->table_groups(true);
			$this->table_trending_groups(true);
			$this->table_category(true);
			$this->table_tags(true);
			$this->table_group_tags(true);
			$this->table_country(true);
			$this->table_region(true);
			$this->table_city(true);
		}

		public function table_users($drop = false) {
			$this->dbforge->add_field(array(
				'id' => array('type' => 'BIGINT', 'null' => false, 'unsigned' => true, 'auto_increment' => true, 'comment' => 'Unique user id'),
				'gid' => array('type' => 'VARCHAR', 'constraint' => 64, 'default' => '', 'comment' => 'Google user id'),
				'name' => array('type' => 'VARCHAR', 'constraint' => 128, 'null' => false, 'default' => '', 'comment' => 'User Name'),
				'email' => array('type' => 'VARCHAR', 'constraint' => 64, 'null' => false, 'default' => '', 'unique' => true, 'comment' => 'User Email Address'),
				'email_v' => array('type' => 'BOOLEAN', 'null' => false, 'default' => false, 'comment' => 'Is user email address verified.'),
				'username' => array('type' => 'VARCHAR', 'constraint' => 64, 'null' => false, 'default' => '', 'unique' => true, 'comment' => 'User account Username'),
				'password' => array('type' => 'VARCHAR', 'constraint' => 64, 'null' => false, 'comment' => 'User account Password'),
				'reset' => array('type' => 'SMALLINT', 'unsigned' => true, 'default' => 0, 'comment' => 'How much time password has been changed?'),
				'pic_url' => array('type' => 'VARCHAR', 'constraint' => 256, 'null' => false, 'default' => '', 'comment' => 'image url of user'),
				'user_role' => array('type' => 'SMALLINT', 'unsigned' => true, 'null' => false, 'comment' => 'User Role eg: 1->Super User, 2-> Moderator'),
				'status' => array('type' => 'TINYINT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Status of user account. eg: 0->pending, 1->Active, 2-> deactive, 3-> Blocked, 4-> Delete'),
				"reg_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'when user registered'",
				"last_active DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'When user active'",
			));
			$this->dbforge->add_key('id', true);
			$this->_create($drop, "users", $this->users_data());
		}

		public function table_configs($drop = false) {
			$this->dbforge->add_field(array(
				'id' => array('type' => 'BIGINT', 'null' => false, 'unsigned' => true, 'auto_increment' => true, 'comment' => 'Unique id'),
				'user_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'For which user this setting saved. User ID: 0-> For all users.'),
				'setting' => array('type' => 'VARCHAR', 'constraint' => 64, 'null' => false, 'comment' => 'Fixed name of setting in config'),
				'value' => array('type' => 'TEXT', 'null' => false, 'comment' => 'Multiple format will be saved like Integer, Float, String etc'),
				"create_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'When this setting added in database'",
				"update_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'When this setting updated'",
			));
			$this->dbforge->add_key('id', true);
			$this->dbforge->add_key(array('setting', 'user_id'));
			$this->_create($drop, "configs", $this->table_config_data());
		}

		public function table_access_token($drop = false) {
			$this->dbforge->add_field(array(
				'id' => array('type' => 'BIGINT', 'null' => false, 'unsigned' => true, 'auto_increment' => true, 'comment' => 'Unique id'),
				'user_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Which user data is this. Foreign key: prefix_users->id'),
				'access_token' => array('type' => 'VARCHAR', 'constraint' => 512, 'null' => false, 'default' => '', 'comment' => 'This is access token used for access purpose'),
				'refresh_token' => array('type' => 'VARCHAR', 'constraint' => 128, 'null' => false, 'default' => '', 'comment' => 'This is used for update access token'),
				'valid_access' => array('type' => 'BOOLEAN', 'null' => false, 'default' => false, 'comment' => 'Category Status to show group on website.'),
			));
			$this->dbforge->add_key('id', true);
			$this->_create($drop, "access_token");
		}

		public function table_groups($drop = false) {
			$this->dbforge->add_field([
				'id' => array('type' => 'BIGINT', 'null' => false, 'unsigned' => true, 'auto_increment' => true, 'comment' => 'Unique group id'),
				'user_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Which user submitted this group. Foreign key: prefix_city->id'),
				'name' => array('type' => 'VARCHAR', 'constraint' => 256, 'null' => false, 'default' => '', 'comment' => 'Group Name'),
				'invite_key' => array('type' => 'VARCHAR', 'constraint' => 256, 'null' => false, 'default' => '', 'unique' => true, 'comment' => 'Group invite Link Key'),
				'category_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Category of this group Foreign key: prefix_category->id'),
				'subcategory_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Sub Category of this group Foreign key: prefix_category->id'),
				'country_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'From which country this group is created. Foreign key: prefix_country->id'),
				'region_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'From which country region this group is created. Foreign key: prefix_region->id'),
				'city_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'From which country city this group is created. Foreign key: prefix_city->id'),
				'icon_url' => array('type' => 'VARCHAR', 'constraint' => 512, 'null' => false, 'default' => '', 'comment' => 'Group image relative url'),
				'status' => array('type' => 'TINYINT', 'unsigned' => true, 'null' => false, 'default' => 1, 'comment' => 'Status of this group. eg: 0->expire, 1->active, 2-> full, 3-> delete'),
				"create_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'When group created'",
				"update_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'When group updated'",
			]);
			$this->dbforge->add_key('id', true);
			//$this->_create($drop, "groups", $this->table_groups_data());
			$this->_create($drop, "groups");
		}

		public function table_trending_groups($drop = false) {
			$this->dbforge->add_field(array(
				'group_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Unique Group ID. Foreign Key: prefix_groups->id'),
				"create_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'when this group mark trending.'",
			));
			$this->dbforge->add_key('group_id as trending_group_id');
			$this->_create($drop, "trending_groups", $this->table_trending_groups_data());
		}

		public function table_category($drop = false) {
			$this->dbforge->add_field(array(
				'id' => array('type' => 'INT', 'null' => false, 'unsigned' => true, 'auto_increment' => true, 'comment' => 'Unique id'),
				'name' => array('type' => 'VARCHAR', 'constraint' => 64, 'null' => false, 'comment' => 'Category name'),
				'slug' => array('type' => 'VARCHAR', 'constraint' => 64, 'null' => false, 'unique' => true, 'comment' => 'Category slug'),
				'parent_id' => array('type' => 'INT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Catgory id for multi level caegory, Foreign Key: prefix_category->id'),
				'view_state' => array('type' => 'TINYINT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Status of user account. eg: 0->Most Highest, 1->Highest, 2->Middle, 3->Lowest, 4-> Most Lostest'),
				'icon_url' => array('type' => 'VARCHAR', 'constraint' => 512, 'null' => false, 'default' => '', 'comment' => 'Category image relative url'),
				'enabled' => array('type' => 'BOOLEAN', 'null' => false, 'default' => true, 'comment' => 'Category Status to show group on website.'),
				'groups' => array('type' => 'INT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Number of groups added in this category'),
				"create_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time when this category added in database'",
				"update_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Time when this category details updated'",
			));
			$this->dbforge->add_key('id', true);
			$this->_create($drop, "category");
		}

		public function table_tags($drop = false) {
			$this->dbforge->add_field(array(
				'id' => array('type' => 'BIGINT', 'null' => false, 'unsigned' => true, 'auto_increment' => true, 'comment' => 'Tag Unique id'),
				'name' => array('type' => 'VARCHAR', 'constraint' => 128, 'null' => false, 'comment' => 'Tag name'),
				'groups' => array('type' => 'INT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Number of groups added in this tag'),
			));
			$this->dbforge->add_key('id', true);
			$this->_create($drop, "tags");
		}

		public function table_group_tags($drop = false) {
			$this->dbforge->add_field(array(
				'user_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'comment' => 'Who submited group, Foreign Key: prefix_users->id'),
				'group_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'comment' => 'Group Unique ID, Foreign Key: prefix_groups->id'),
				'tag_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'comment' => 'tag Unique Id, Foreign Key: prefix_tags->id'),
				"create_time DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Tag added time'",
			));
			$this->dbforge->add_key(array('user_id', 'group_id', 'tag_id'));
			$this->_create($drop, "group_tags");
		}

		public function table_country($drop = false) {
			$this->dbforge->add_field(array(
				'id' => array('type' => 'TINYINT', 'null' => false, 'unsigned' => true, 'auto_increment' => true, 'comment' => 'Unique Country ID'),
				'name' => array('type' => 'VARCHAR', 'constraint' => 128, 'null' => false, 'comment' => 'Country Name'),
				'iso_two' => array('type' => 'CHAR', 'constraint' => 2, 'null' => false, 'unique' => true, 'comment' => 'Country ISO Two'),
				'iso_three' => array('type' => 'CHAR', 'constraint' => 3, 'null' => false, 'unique' => true, 'comment' => 'Country ISO Three'),
				'num_iso' => array('type' => 'SMALLINT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Country ISO Number'),
				'isd' => array('type' => 'SMALLINT', 'unsigned' => true, 'null' => false, 'default' => 0, 'comment' => 'Country ISD Number'),
				'enabled' => array('type' => 'BOOLEAN', 'null' => false, 'default' => false, 'comment' => 'System Enabled in this country'),
				"create_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'when this country added to system.'",
				"update_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Time when this country details updated'",
			));
			$this->dbforge->add_key('id', true);
			$this->_create($drop, "country", $this->table_country_data());
		}

		public function table_region($drop = false) {
			$this->dbforge->add_field(array(
				'id' => array('type' => 'TINYINT', 'null' => false, 'unsigned' => true, 'auto_increment' => true, 'comment' => 'Unique Region ID'),
				'country_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'comment' => 'Which country region is this?, Foreign Key: prefix_country->id'),
				'name' => array('type' => 'VARCHAR', 'constraint' => 128, 'null' => false, 'comment' => 'Region Name'),
				'enabled' => array('type' => 'BOOLEAN', 'null' => false, 'default' => false, 'comment' => 'System Enabled in this region'),
				"create_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'when this region added to system.'",
				"update_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Time when this region details updated'",
			));
			$this->dbforge->add_key('id', true);
			$this->_create($drop, "region");
		}

		public function table_city($drop = false) {
			$this->dbforge->add_field(array(
				'id' => array('type' => 'TINYINT', 'null' => false, 'unsigned' => true, 'auto_increment' => true, 'comment' => 'Unique City ID'),
				'country_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'comment' => 'Which country city is this?, Foreign Key: prefix_country->id'),
				'region_id' => array('type' => 'BIGINT', 'unsigned' => true, 'null' => false, 'comment' => 'Which Region city is this?, Foreign Key: prefix_region->id'),
				'name' => array('type' => 'VARCHAR', 'constraint' => 128, 'null' => false, 'comment' => 'City Name'),
				'enabled' => array('type' => 'BOOLEAN', 'null' => false, 'default' => false, 'comment' => 'System Enabled in this city'),
				"create_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'when this city added to system.'",
				"update_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Time when this city details updated'",
			));
			$this->dbforge->add_key('id', true);
			$this->_create($drop, "city");
		}

		/** =========== Intial Table Data Functions ===================== */

		public function users_data() {
			return [
				["name" => "Rahul Siwal", "email" => "rahulsiwal62@gmail.com", "username" => "rsiwal", "password" => $this->user->password("rsiwal"), "user_role" => 1, "status" => 1],
			];
		}

		public function table_config_data() {
			return [
				["setting" => "_seo_title", "value" => "Whatsapp Group Links"]
			];
		}

		public function table_trending_groups_data() {
			return [
				["group_id" => 1],
				["group_id" => 4],
				["group_id" => 6],
				["group_id" => 7],
				["group_id" => 9],
				["group_id" => 10],
				["group_id" => 11],
				["group_id" => 12],
				["group_id" => 13],
				["group_id" => 14],
			];
		}

		public function table_groups_data() {
			return [
				["name" => "हिंदू राष्ट्र", "invite_key" => "AlANWYMbBUJ2UDtAyIPT6u", "icon_url" => "202106/06/AlANWYMbBUJ2UDtAyIPT6u.jpg", "category_id" => 2],
				["name" => "Hindu Rastra", "invite_key" => "AlANWYMbBUJ2UDtAyIPT6a", "icon_url" => "202106/06/AlANWYMbBUJ2UDtAyIPT6u.jpg", "category_id" => 3],
				["name" => "Testing Group", "invite_key" => "AlANWYMbBUJ2UDtAyIPT6b", "icon_url" => "202106/06/AlANWYMbBUJ2UDtAyIPT6u.jpg", "category_id" => 10],
				["name" => "Radha Krishna", "invite_key" => "AlANWYMbBUJ2UDtAyIPT6c", "icon_url" => "202106/06/AlANWYMbBUJ2UDtAyIPT6u.jpg", "category_id" => 15],
				["name" => "Krishna Kanhaiya", "invite_key" => "AlANWYMbBUJ2UDtAyIPT6d", "icon_url" => "202106/06/AlANWYMbBUJ2UDtAyIPT6u.jpg", "category_id" => 1],
				["name" => "Vasudev Shri Krishna", "invite_key" => "AlANWYMbBUJ2UDtAyIPT6e", "icon_url" => "202106/06/AlANWYMbBUJ2UDtAyIPT6u.jpg", "category_id" => 19],
				["name" => "Devki Nandan", "invite_key" => "AlANWYMbBUJ2UDtAyIPT6f", "icon_url" => "202106/06/AlANWYMbBUJ2UDtAyIPT6u.jpg", "category_id" => 25],
				["name" => "Runchore", "invite_key" => "AlANWYMbBUJ2UDtAyIPT6g", "icon_url" => "202106/06/AlANWYMbBUJ2UDtAyIPT6u.jpg", "category_id" => 45],
				["name" => "Kanhiya", "invite_key" => "AlANWYMbBUJ2UDtAyIPT6h", "icon_url" => "202106/06/AlANWYMbBUJ2UDtAyIPT6u.jpg", "category_id" => 3],
				["name" => "Makhan Chor", "invite_key" => "AlANWYMbBUJ2UDtAyIPT6i", "icon_url" => "202106/06/AlANWYMbBUJ2UDtAyIPT6u.jpg", "category_id" => 1],
				["name" => "Vashudev", "invite_key" => "AlANWYMbBUJ2UDtAyIPT6j", "icon_url" => "202106/06/AlANWYMbBUJ2UDtAyIPT6u.jpg", "category_id" => 5],
				["name" => "Devki Nandan", "invite_key" => "AlANWYMbBUJ2UDtAyIPT6k", "icon_url" => "202106/06/AlANWYMbBUJ2UDtAyIPT6u.jpg", "category_id" => 5],
				["name" => "Murlidhar", "invite_key" => "AlANWYMbBUJ2UDtAyIPT6l", "icon_url" => "202106/06/AlANWYMbBUJ2UDtAyIPT6u.jpg", "category_id" => 5],
			];
		}

		public function table_category_data() {
			return [
				// Top Main Categories
				["name" => "Local Business or Place", "slug" => "local-business-or-place", "parent_id" => 0, "icon_url" => "category/entertainment.jpg"],
				["name" => "Company, Organization or Institution", "slug" => "company-organization-or-institution", "parent_id" => 0, "icon_url" => "category/entertainment.jpg"],
				["name" => "Health, Beauty & Personal Care", "slug" => "health-beauty-personal-care", "parent_id" => 0, "icon_url" => "category/health-and-personal-care.jpg"],
				["name" => "Brand or Product", "slug" => "brand-or-product", "parent_id" => 0, "icon_url" => "category/entertainment.jpg"],
				["name" => "Artist, Band or Public Figure", "slug" => "artist-band-or-public-figure", "parent_id" => 0, "icon_url" => "category/entertainment.jpg"],
				["name" => "Entertainment", "slug" => "entertainment", "parent_id" => 0, "icon_url" => "category/entertainment.jpg"],
				["name" => "Jobs & Education", "slug" => "jobs-education", "parent_id" => 0, "icon_url" => "category/job-education.jpg"],
				["name" => "Cause or Community", "slug" => "cause-or-community", "parent_id" => 0, "icon_url" => "category/community.jpg"],
				["name" => "Autos & Vehicles", "slug" => "autos-vehicles", "parent_id" => 0, "icon_url" => "category/entertainment.jpg"],
				["name" => "News, Books & Publications", "slug" => "news-books-publications", "parent_id" => 0, "icon_url" => "category/news.jpg"],
				["name" => "Home & Garden", "slug" => "home-garden", "parent_id" => 0, "icon_url" => "category/entertainment.jpg"],
				["name" => "Occasions & Gifts", "slug" => "occasions-gifts", "parent_id" => 0, "icon_url" => "category/entertainment.jpg"],
				["name" => "Real Estate", "slug" => "real-estate", "parent_id" => 0, "icon_url" => "category/real-state.jpg"],
				["name" => "Games (Casual & Online)", "slug" => "games-casual-online", "parent_id" => 0, "icon_url" => "category/gaming.jpg"],
				["name" => "Sports & Fitness", "slug" => "sports-fitness", "parent_id" => 0, "icon_url" => "category/sport-fitness.png"],
				["name" => "Software / Apps / Internet", "slug" => "softeare-apps", "parent_id" => 0, "icon_url" => "category/software-apps.png"],
				["name" => "Love & Romance", "slug" => "love-romance", "parent_id" => 0, "icon_url" => "category/love-romance.jpeg"],
				["name" => "Adult (18+)", "slug" => "adults", "parent_id" => 0, "icon_url" => "category/adult.jpg"],
				["name" => "Others", "slug" => "others", "parent_id" => 0, "icon_url" => "category/others.png"],

				// Parent Category: Local Business or Place : parent_id = 1
				["name" => "Art-Gallery", "slug" => "art-gallery", "parent_id" => 1, "icon_url" => "category/albumb.jpg"],
				["name" => "Book Store", "slug" => "book-store", "parent_id" => 1, "icon_url" => "category/albumb.jpg"],
				["name" => "Food/Grocery", "slug" => "food-grocery", "parent_id" => 1, "icon_url" => "category/albumb.jpg"],
				["name" => "Hotel", "slug" => "hotel", "parent_id" => 1, "icon_url" => "category/albumb.jpg"],
				["name" => "Movie Theatre", "slug" => "movie-theatre", "parent_id" => 1, "icon_url" => "category/albumb.jpg"],
				["name" => "Nightclubs, Bars & Music Clubs", "slug" => "nightclubs-bars-music-clubs", "parent_id" => 1, "icon_url" => "category/albumb.jpg"],
				["name" => "Outdoor Gear/Sporting Goods", "slug" => "outdoor-gear-sporting-goods", "parent_id" => 1, "icon_url" => "category/albumb.jpg"],
				["name" => "Real Estate", "slug" => "bussiness-real-estate", "parent_id" => 1, "icon_url" => "category/albumb.jpg"],
				["name" => "Restaurant/Cafe", "slug" => "restaurant-cafe", "parent_id" => 1, "icon_url" => "category/albumb.jpg"],
				["name" => "School", "slug" => "school", "parent_id" => 1, "icon_url" => "category/albumb.jpg"],
				["name" => "Shopping/Retail", "slug" => "shopping-retail", "parent_id" => 1, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Company, Organization or Institution : parent_id = 2
				["name" => "Automobiles and Parts", "slug" => "automobiles-and-parts", "parent_id" => 2, "icon_url" => "category/albumb.jpg"],
				["name" => "Company", "slug" => "company", "parent_id" => 2, "icon_url" => "category/albumb.jpg"],
				["name" => "Computers/Technology", "slug" => "computers-technology", "parent_id" => 2, "icon_url" => "category/albumb.jpg"],
				["name" => "Consulting/Business Services", "slug" => "consulting-business-services", "parent_id" => 2, "icon_url" => "category/albumb.jpg"],
				["name" => "Food/Beverages", "slug" => "food-beverages-org", "parent_id" => 2, "icon_url" => "category/albumb.jpg"],
				["name" => "Health/Beauty", "slug" => "health-beauty-org", "parent_id" => 2, "icon_url" => "category/albumb.jpg"],
				["name" => "Insurance Company", "slug" => "insurance-company", "parent_id" => 2, "icon_url" => "category/albumb.jpg"],
				["name" => "Internet/Software", "slug" => "internet-software", "parent_id" => 2, "icon_url" => "category/albumb.jpg"],
				["name" => "Non-Profit Organization", "slug" => "non-profit-organization", "parent_id" => 2, "icon_url" => "category/albumb.jpg"],
				["name" => "Retail and Consumer Merchandise", "slug" => "retail-and-consumer-merchandise", "parent_id" => 2, "icon_url" => "category/albumb.jpg"],
				["name" => "Media/News/Publishing", "slug" => "media-news-publishing", "parent_id" => 2, "icon_url" => "category/albumb.jpg"],
				["name" => "Travel & Tourism", "slug" => "travel-tourism", "parent_id" => 2, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Health, Beauty & Personal Care : parent_id = 3
				["name" => "Anti-Aging", "slug" => "anti-aging", "parent_id" => 3, "icon_url" => "category/albumb.jpg"],
				["name" => "Body Art", "slug" => "body-art", "parent_id" => 3, "icon_url" => "category/albumb.jpg"],
				["name" => "Baby Care & Hygiene", "slug" => "baby-care-hygiene", "parent_id" => 3, "icon_url" => "category/albumb.jpg"],
				["name" => "Hair Care", "slug" => "hair-care", "parent_id" => 3, "icon_url" => "category/albumb.jpg"],
				["name" => "Make-Up & Cosmetics", "slug" => "make-up-cosmetics", "parent_id" => 3, "icon_url" => "category/albumb.jpg"],
				["name" => "Maternity & New Parent", "slug" => "maternity-new-parent", "parent_id" => 3, "icon_url" => "category/albumb.jpg"],
				["name" => "Oral Care", "slug" => "oral-care", "parent_id" => 3, "icon_url" => "category/albumb.jpg"],
				["name" => "Perfumes & Fragrances", "slug" => "perfumes-fragrances", "parent_id" => 3, "icon_url" => "category/albumb.jpg"],
				["name" => "Shaving & Grooming", "slug" => "shaving-grooming", "parent_id" => 3, "icon_url" => "category/albumb.jpg"],
				["name" => "Skin Care", "slug" => "skin-care", "parent_id" => 3, "icon_url" => "category/albumb.jpg"],
				["name" => "Medical Store", "slug" => "medical-store", "parent_id" => 3, "icon_url" => "category/albumb.jpg"],
				["name" => "Tanning & Sun Care", "slug" => "tanning-sun-care", "parent_id" => 3, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Brand or Product : parent_id = 4
				["name" => "Appliance", "slug" => "appliance", "parent_id" => 4, "icon_url" => "category/albumb.jpg"],
				["name" => "Baby Goods", "slug" => "baby-goods", "parent_id" => 4, "icon_url" => "category/albumb.jpg"],
				["name" => "Clothing", "slug" => "clothing", "parent_id" => 4, "icon_url" => "category/albumb.jpg"],
				["name" => "Electronics", "slug" => "electronics", "parent_id" => 4, "icon_url" => "category/albumb.jpg"],
				["name" => "Food/Beverages", "slug" => "food-beverages", "parent_id" => 4, "icon_url" => "category/albumb.jpg"],
				["name" => "Furniture", "slug" => "furniture", "parent_id" => 4, "icon_url" => "category/albumb.jpg"],
				["name" => "Games/Toys", "slug" => "games-toys", "parent_id" => 4, "icon_url" => "category/albumb.jpg"],
				["name" => "Health/Beauty", "slug" => "health-beauty", "parent_id" => 4, "icon_url" => "category/albumb.jpg"],
				["name" => "Jewelry/Watches", "slug" => "jewelry-watches", "parent_id" => 4, "icon_url" => "category/albumb.jpg"],
				["name" => "Kitchen/Cooking", "slug" => "kitchen-cooking", "parent_id" => 4, "icon_url" => "category/albumb.jpg"],
				["name" => "Pet Supplies", "slug" => "pet-supplies", "parent_id" => 4, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Artist, Band or Public Figure : parent_id = 5
				["name" => "Actor", "slug" => "actor", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Artist", "slug" => "artist", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Athlete", "slug" => "athlete", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Author", "slug" => "author", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Business Person", "slug" => "business-person", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Chef", "slug" => "chef", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Coach", "slug" => "coach", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Doctor", "slug" => "doctor", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Entertainer", "slug" => "entertainer", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Journalist", "slug" => "journalist", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Lawyer", "slug" => "lawyer", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Musician/Band", "slug" => "musician-band", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Politician", "slug" => "politician", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Teacher", "slug" => "teacher", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],
				["name" => "Writer", "slug" => "writer", "parent_id" => 5, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Entertainment : parent_id = 6
				["name" => "Album", "slug" => "album", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "Book", "slug" => "book", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "Concert Tour", "slug" => "concert-tour", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "Fantasy Sports", "slug" => "fantasy-sports", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "Fun & Trivia", "slug" => "fun-trivia", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "Humor & Jokes", "slug" => "humor-jokes", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "Library", "slug" => "library", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "Magazine", "slug" => "magazine", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "Movies & Films", "slug" => "movies-films", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "Music & Audio", "slug" => "music-audio", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "Radio Station", "slug" => "radio-station", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "Record Label", "slug" => "record-label", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "Sports Venue", "slug" => "sports-venue", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "TV Channel", "slug" => "tv-channel", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "TV Show", "slug" => "tv-show", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],
				["name" => "Visual Art & Design", "slug" => "visual-art-design", "parent_id" => 6, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Jobs & Education : parent_id = 7
				["name" => "Colleges, Universities & Post-Secondary Education", "slug" => "colleges-universities-post-secondary-education", "parent_id" => 7, "icon_url" => "category/albumb.jpg"],
				["name" => "Education and Training", "slug" => "education-and-training", "parent_id" => 7, "icon_url" => "category/albumb.jpg"],
				["name" => "Job Listings", "slug" => "job-listings", "parent_id" => 7, "icon_url" => "category/albumb.jpg"],
				["name" => "Language Education", "slug" => "language-education", "parent_id" => 7, "icon_url" => "category/albumb.jpg"],
				["name" => "Music Education & Instruction", "slug" => "music-education-instruction", "parent_id" => 7, "icon_url" => "category/albumb.jpg"],
				["name" => "Online Education & Degree Programs", "slug" => "online-education-degree-programs", "parent_id" => 7, "icon_url" => "category/albumb.jpg"],
				["name" => "Standardized & Admissions Tests", "slug" => "standardized-admissions-tests", "parent_id" => 7, "icon_url" => "category/albumb.jpg"],
				["name" => "Training & Certification", "slug" => "training-certification", "parent_id" => 7, "icon_url" => "category/albumb.jpg"],
				["name" => "Tutoring Services", "slug" => "tutoring-services", "parent_id" => 7, "icon_url" => "category/albumb.jpg"],
				["name" => "Vocational Training & Trade Schools", "slug" => "vocational-training-trade-schools", "parent_id" => 7, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Cause or Community : parent_id = 8
				["name" => "Animal Rights Activism", "slug" => "animal-rights-activism", "parent_id" => 8, "icon_url" => "category/albumb.jpg"],
				["name" => "Astrology & Horoscopes", "slug" => "astrology-horoscopes", "parent_id" => 8, "icon_url" => "category/albumb.jpg"],
				["name" => "Buddhism", "slug" => "buddhism", "parent_id" => 8, "icon_url" => "category/albumb.jpg"],
				["name" => "Christianity", "slug" => "christianity", "parent_id" => 8, "icon_url" => "category/albumb.jpg"],
				["name" => "Environmental Activism", "slug" => "environmental-activism", "parent_id" => 8, "icon_url" => "category/albumb.jpg"],
				["name" => "Hinduism", "slug" => "hinduism", "parent_id" => 8, "icon_url" => "category/albumb.jpg"],
				["name" => "Islam", "slug" => "islam", "parent_id" => 8, "icon_url" => "category/albumb.jpg"],
				["name" => "Judaism", "slug" => "judaism", "parent_id" => 8, "icon_url" => "category/albumb.jpg"],
				["name" => "Scientology", "slug" => "scientology", "parent_id" => 8, "icon_url" => "category/albumb.jpg"],
				["name" => "Reproductive Rights", "slug" => "reproductive-rights", "parent_id" => 8, "icon_url" => "category/albumb.jpg"],
				["name" => "Work & Labor Issues", "slug" => "work-labor-issues", "parent_id" => 8, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Autos & Vehicles : parent_id = 9
				["name" => "Boats & Watercraft", "slug" => "boats-watercraft", "parent_id" => 9, "icon_url" => "category/albumb.jpg"],
				["name" => "Used Motor Vehicles", "slug" => "used-motor-vehicles", "parent_id" => 9, "icon_url" => "category/albumb.jpg"],
				["name" => "Driving Instruction & Driver Education", "slug" => "driving-instruction-driver-education", "parent_id" => 9, "icon_url" => "category/albumb.jpg"],
				["name" => "Vehicle Dealers", "slug" => "vehicle-dealers", "parent_id" => 9, "icon_url" => "category/albumb.jpg"],
				["name" => "Vehicle Repair & Maintenance", "slug" => "vehicle-repair-maintenance", "parent_id" => 9, "icon_url" => "category/albumb.jpg"],
				["name" => "Vehicle Parts & Accessories", "slug" => "vehicle-parts-accessories", "parent_id" => 9, "icon_url" => "category/albumb.jpg"],

				// Parent Category: News, Books & Publications : parent_id = 10
				["name" => "Newspapers", "slug" => "newspapers", "parent_id" => 10, "icon_url" => "category/albumb.jpg"],
				["name" => "Celebrities & Entertainment News", "slug" => "celebrities-entertainment-news", "parent_id" => 10, "icon_url" => "category/albumb.jpg"],
				["name" => "Audio Books", "slug" => "audio-books", "parent_id" => 10, "icon_url" => "category/albumb.jpg"],
				["name" => "Book Retailers", "slug" => "book-retailers", "parent_id" => 10, "icon_url" => "category/albumb.jpg"],
				["name" => "E-Books", "slug" => "e-books", "parent_id" => 10, "icon_url" => "category/albumb.jpg"],
				["name" => "Public Records", "slug" => "public-records", "parent_id" => 10, "icon_url" => "category/albumb.jpg"],
				["name" => "Translation", "slug" => "translation", "parent_id" => 10, "icon_url" => "category/albumb.jpg"],
				["name" => "Weather", "slug" => "weather", "parent_id" => 10, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Home & Garden : parent_id = 11
				["name" => "Bedding & Linens", "slug" => "bedding-linens", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Home Appliance Service & Repair", "slug" => "home-appliance-service-repair", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Major Kitchen Appliances", "slug" => "major-kitchen-appliances", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Vacuum Cleaners & Accessories", "slug" => "vacuum-cleaners-accessories", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Washers & Dryers", "slug" => "washers-dryers", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Home Decor & Interior Decorating", "slug" => "home-decor-interior-decorating", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Home Furniture", "slug" => "home-furniture", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Home Heating & Cooling", "slug" => "home-heating-cooling", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Home Safety & Security", "slug" => "home-safety-security", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Home Improvement & Maintenance", "slug" => "home-improvement-maintenance", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Kitchen & Dining", "slug" => "kitchen-dining", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Lights & Lighting", "slug" => "lights-lighting", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Residential Cleaning", "slug" => "residential-cleaning", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Water Filters", "slug" => "water-filters", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Pest & Weed Control", "slug" => "pest-weed-control", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],
				["name" => "Outdoor Cooking Equipment & Accessories", "slug" => "outdoor-cooking-equipment-accessories", "parent_id" => 11, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Occasions & Gifts : parent_id = 12
				["name" => "Cards & Greetings", "slug" => "cards-greetings", "parent_id" => 12, "icon_url" => "category/albumb.jpg"],
				["name" => "Flower Arrangements", "slug" => "flower-arrangements", "parent_id" => 12, "icon_url" => "category/albumb.jpg"],
				["name" => "Funerals & Bereavement", "slug" => "funerals-bereavement", "parent_id" => 12, "icon_url" => "category/albumb.jpg"],
				["name" => "Gifts Suggestions", "slug" => "gifts-suggestions", "parent_id" => 12, "icon_url" => "category/albumb.jpg"],
				["name" => "Parties & Party Supplies", "slug" => "parties-party-supplies", "parent_id" => 12, "icon_url" => "category/albumb.jpg"],
				["name" => "Weddings", "slug" => "weddings", "parent_id" => 12, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Real Estate : parent_id = 13
				["name" => "Property Development", "slug" => "property-development", "parent_id" => 13, "icon_url" => "category/albumb.jpg"],
				["name" => "Real Estate Agents & Brokerages", "slug" => "real-estate-agents-brokerages", "parent_id" => 13, "icon_url" => "category/albumb.jpg"],
				["name" => "Real Estate Listings ", "slug" => "real-estate-listings", "parent_id" => 13, "icon_url" => "category/albumb.jpg"],
				["name" => "Rental Listings", "slug" => "rental-listings", "parent_id" => 13, "icon_url" => "category/albumb.jpg"],
				["name" => "Relocation & Household Moving", "slug" => "relocation-household-moving", "parent_id" => 13, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Games (Casual & Online) : parent_id = 14
				["name" => "Among Us", "slug" => "among-us", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Angry Birds", "slug" => "angry-birds", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Angry Birds 2", "slug" => "angry-birds-2", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Angry Birds Friends", "slug" => "angry-birds-friends", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Arena of Valor", "slug" => "arena-of-valor", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Arknights", "slug" => "arknights", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "BanG Dream! Girls Band Party!", "slug" => "bang-dream-girls-band-party", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Bleach: Brave Souls", "slug" => "bleach-brave-souls", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Brave Frontier", "slug" => "brave-frontier", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Brawl Stars", "slug" => "brawl-stars", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Call of Duty: Mobile", "slug" => "call-of-duty-mobile", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Candy Crush Saga", "slug" => "candy-crush-saga", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Captain Tsubasa: Dream Team", "slug" => "captain-tsubasa-dream-team", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Chain Chronicle", "slug" => "chain-chronicle", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Clash Royale", "slug" => "clash-royale", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Clash of Clans", "slug" => "clash-of-clans", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Disney Crossy Road", "slug" => "disney-crossy-road", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Disney Tsum Tsum", "slug" => "disney-tsum-tsum", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "DomiNations", "slug" => "dominations", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Dragon Ball Z", "slug" => "dragon-ball-z", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Dragon Quest Monsters: Super Light", "slug" => "dragon-quest-monsters-super-light", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "FIFA Mobile", "slug" => "fifa-mobile", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Final Fantasy Brave Exvius", "slug" => "final-fantasy-brave-exvius", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Final Fantasy XV: A New Empire", "slug" => "final-fantasy-xv-a-new-empire", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Fishdom", "slug" => "fishdom", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Flappy Bird", "slug" => "flappy-bird", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Fortnite", "slug" => "fortnite", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Fruit Ninja", "slug" => "fruit-ninja", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Gardenscapes", "slug" => "gardenscapes", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Garena Free Fire", "slug" => "garena-free-fire", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Genshin Impact", "slug" => "genshin-impact", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Granblue Fantasy", "slug" => "granblue-fantasy", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Hearthstone", "slug" => "hearthstone", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Homescapes", "slug" => "homescapes", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Honor of Kings", "slug" => "honor-of-kings", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Ice Age Village", "slug" => "ice-age-village", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Ingress", "slug" => "ingress", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Jikkyō Powerful Pro Yakyū", "slug" => "jikky-powerful-pro-yaky", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "League of Legends", "slug" => "league-of-legends", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Lineage 2 Revolution", "slug" => "lineage-2-revolution", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Love Live! School Idol Festival", "slug" => "love-live-school-idol-festival", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Ludo King", "slug" => "ludo-king", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Mario Kart Tour", "slug" => "mario-kart-tour", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Marvel: Avengers Alliance", "slug" => "marvel-avengers-alliance", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Minecraft", "slug" => "minecraft", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Mobile Legends: Bang Bang", "slug" => "mobile-legends-bang-bang", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Monster Strike", "slug" => "monster-strike", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Monument Valley", "slug" => "monument-valley", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Mr Love: Queen's Choice", "slug" => "mr-love-queen-s-choice", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Nitro Nation Online", "slug" => "nitro-nation-online", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "One Piece Bounty Rush", "slug" => "one-piece-bounty-rush", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "One Piece Treasure Cruise", "slug" => "one-piece-treasure-cruise", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "PES 2018 Mobile", "slug" => "pes-2018-mobile", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "PES Club Manager", "slug" => "pes-club-manager", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "PUBG MOBILE", "slug" => "pubg-mobile", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "PlayerUnknown's Battlegrounds", "slug" => "playerunknown-s-battlegrounds", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Pokémon Duel", "slug" => "pok-mon-duel", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Pokémon GO", "slug" => "pok-mon-go", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Pro Yakyū Sprits A", "slug" => "pro-yaky-sprits-a", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Punishing: Gray Raven", "slug" => "punishing-gray-raven", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Puyopuyo!! Quest", "slug" => "puyopuyo-quest", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Puzzle & Dragons", "slug" => "puzzle-dragons", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "QQ Speed Mobile", "slug" => "qq-speed-mobile", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Quiz RPG: The World of Mystic Wiz", "slug" => "quiz-rpg-the-world-of-mystic-wiz", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Rage of Bahamut", "slug" => "rage-of-bahamut", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Roblox", "slug" => "roblox", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Sonic Dash", "slug" => "sonic-dash", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Subway Surfers", "slug" => "subway-surfers", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Super Mario Run", "slug" => "super-mario-run", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "The Battle Cats", "slug" => "the-battle-cats", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "The Idolmaster Cinderella Girls: Starlight Stage", "slug" => "the-idolmaster-cinderella-girls-starlight-stage", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "The Simpsons: Tapped Out", "slug" => "the-simpsons-tapped-out", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Township", "slug" => "township", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "War Robots", "slug" => "war-robots", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "White Cat Project", "slug" => "white-cat-project", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "World of Tanks", "slug" => "world-of-tanks", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],
				["name" => "Yu-Gi-Oh! Duel Links", "slug" => "yu-gi-oh-duel-links", "parent_id" => 14, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Sports & Fitness : parent_id = 15
				["name" => "Boating & Water Recreation", "slug" => "boating-water-recreation", "parent_id" => 15, "icon_url" => "category/albumb.jpg"],
				["name" => "Fitness Classes & Instruction", "slug" => "fitness-classes-instruction", "parent_id" => 15, "icon_url" => "category/albumb.jpg"],
				["name" => "Gyms & Gym Memberships", "slug" => "gyms-gym-memberships", "parent_id" => 15, "icon_url" => "category/albumb.jpg"],
				["name" => "Personal Training", "slug" => "personal-training", "parent_id" => 15, "icon_url" => "category/albumb.jpg"],
				["name" => "Airsoft Equipment", "slug" => "airsoft-equipment", "parent_id" => 15, "icon_url" => "category/albumb.jpg"],
				["name" => "Bicycles & Accessories", "slug" => "bicycles-accessories", "parent_id" => 15, "icon_url" => "category/albumb.jpg"],
				["name" => "Golf Equipment", "slug" => "golf-equipment", "parent_id" => 15, "icon_url" => "category/albumb.jpg"],
				["name" => "Sports Equipment Rental Services", "slug" => "sports-equipment-rental-services", "parent_id" => 15, "icon_url" => "category/albumb.jpg"],

				// Parent Category: Internet : parent_id = 16
				["name" => "Downloadable Utilities", "slug" => "downloadable-utilities", "parent_id" => 16, "icon_url" => "category/albumb.jpg"],
				["name" => "Mobile App Utilities", "slug" => "mobile-app-utilities", "parent_id" => 16, "icon_url" => "category/albumb.jpg"],
				["name" => "Software Programming", "slug" => "software-programming", "parent_id" => 16, "icon_url" => "category/albumb.jpg"],
				["name" => "Website Development", "slug" => "website-development", "parent_id" => 16, "icon_url" => "category/albumb.jpg"],
				["name" => "Tips and Tricks", "slug" => "tips-and-tricks", "parent_id" => 16, "icon_url" => "category/albumb.jpg"],
			];
		}

		public function table_country_data() {
			return [
				["name" => "Afghanistan", "iso_two" => "AF", "iso_three" => "AFG", "num_iso" => "4", "isd" => "+93"],
				["name" => "Albania", "iso_two" => "AL", "iso_three" => "ALB", "num_iso" => "8", "isd" => "+355"],
				["name" => "Algeria", "iso_two" => "DZ", "iso_three" => "DZA", "num_iso" => "12", "isd" => "+213"],
				["name" => "American Samoa", "iso_two" => "AS", "iso_three" => "ASM", "num_iso" => "16", "isd" => "+1684"],
				["name" => "Andorra", "iso_two" => "AD", "iso_three" => "AND", "num_iso" => "20", "isd" => "+376"],
				["name" => "Angola", "iso_two" => "AO", "iso_three" => "AGO", "num_iso" => "24", "isd" => "+244"],
				["name" => "Anguilla", "iso_two" => "AI", "iso_three" => "AIA", "num_iso" => "660", "isd" => "+1264"],
				["name" => "Antarctica", "iso_two" => "AQ", "iso_three" => "ATA", "num_iso" => "010", "isd" => "+672"],
				["name" => "Antigua and Barbuda", "iso_two" => "AG", "iso_three" => "ATG", "num_iso" => "28", "isd" => "+1268"],
				["name" => "Argentina", "iso_two" => "AR", "iso_three" => "ARG", "num_iso" => "32", "isd" => "+54"],
				["name" => "Armenia", "iso_two" => "AM", "iso_three" => "ARM", "num_iso" => "51", "isd" => "+374"],
				["name" => "Aruba", "iso_two" => "AW", "iso_three" => "ABW", "num_iso" => "533", "isd" => "+297"],
				["name" => "Australia", "iso_two" => "AU", "iso_three" => "AUS", "num_iso" => "36", "isd" => "+61"],
				["name" => "Austria", "iso_two" => "AT", "iso_three" => "AUT", "num_iso" => "40", "isd" => "+43"],
				["name" => "Azerbaijan", "iso_two" => "AZ", "iso_three" => "AZE", "num_iso" => "31", "isd" => "+994"],
				["name" => "Bahamas", "iso_two" => "BS", "iso_three" => "BHS", "num_iso" => "44", "isd" => "+1242"],
				["name" => "Bahrain", "iso_two" => "BH", "iso_three" => "BHR", "num_iso" => "48", "isd" => "+973"],
				["name" => "Bangladesh", "iso_two" => "BD", "iso_three" => "BGD", "num_iso" => "50", "isd" => "+880"],
				["name" => "Barbados", "iso_two" => "BB", "iso_three" => "BRB", "num_iso" => "52", "isd" => "+1246"],
				["name" => "Belarus", "iso_two" => "BY", "iso_three" => "BLR", "num_iso" => "112", "isd" => "+375"],
				["name" => "Belgium", "iso_two" => "BE", "iso_three" => "BEL", "num_iso" => "56", "isd" => "+32"],
				["name" => "Belize", "iso_two" => "BZ", "iso_three" => "BLZ", "num_iso" => "84", "isd" => "+501"],
				["name" => "Benin", "iso_two" => "BJ", "iso_three" => "BEN", "num_iso" => "204", "isd" => "+229"],
				["name" => "Bermuda", "iso_two" => "BM", "iso_three" => "BMU", "num_iso" => "60", "isd" => "+1441"],
				["name" => "Bhutan", "iso_two" => "BT", "iso_three" => "BTN", "num_iso" => "64", "isd" => "+975"],
				["name" => "Bolivia", "iso_two" => "BO", "iso_three" => "BOL", "num_iso" => "68", "isd" => "+591"],
				["name" => "Bosnia and Herzegovina", "iso_two" => "BA", "iso_three" => "BIH", "num_iso" => "70", "isd" => "+387"],
				["name" => "Botswana", "iso_two" => "BW", "iso_three" => "BWA", "num_iso" => "72", "isd" => "+267"],
				["name" => "Bouvet Island", "iso_two" => "BV", "iso_three" => "BVT", "num_iso" => "74", "isd" => "+0"],
				["name" => "Brazil", "iso_two" => "BR", "iso_three" => "BRA", "num_iso" => "76", "isd" => "+55"],
				["name" => "British Indian Ocean Territory", "iso_two" => "IO", "iso_three" => "IOT", "num_iso" => "86", "isd" => "+246"],
				["name" => "Brunei Darussalam", "iso_two" => "BN", "iso_three" => "BRN", "num_iso" => "96", "isd" => "+673"],
				["name" => "Bulgaria", "iso_two" => "BG", "iso_three" => "BGR", "num_iso" => "100", "isd" => "+359"],
				["name" => "Burkina Faso", "iso_two" => "BF", "iso_three" => "BFA", "num_iso" => "854", "isd" => "+226"],
				["name" => "Burundi", "iso_two" => "BI", "iso_three" => "BDI", "num_iso" => "108", "isd" => "+257"],
				["name" => "Cambodia", "iso_two" => "KH", "iso_three" => "KHM", "num_iso" => "116", "isd" => "+855"],
				["name" => "Cameroon", "iso_two" => "CM", "iso_three" => "CMR", "num_iso" => "120", "isd" => "+237"],
				["name" => "Canada", "iso_two" => "CA", "iso_three" => "CAN", "num_iso" => "124", "isd" => "+1"],
				["name" => "Cape Verde", "iso_two" => "CV", "iso_three" => "CPV", "num_iso" => "132", "isd" => "+238"],
				["name" => "Cayman Islands", "iso_two" => "KY", "iso_three" => "CYM", "num_iso" => "136", "isd" => "+1345"],
				["name" => "Central African Republic", "iso_two" => "CF", "iso_three" => "CAF", "num_iso" => "140", "isd" => "+236"],
				["name" => "Chad", "iso_two" => "TD", "iso_three" => "TCD", "num_iso" => "148", "isd" => "+235"],
				["name" => "Chile", "iso_two" => "CL", "iso_three" => "CHL", "num_iso" => "152", "isd" => "+56"],
				["name" => "China", "iso_two" => "CN", "iso_three" => "CHN", "num_iso" => "156", "isd" => "+86"],
				["name" => "Christmas Island", "iso_two" => "CX", "iso_three" => "CXR", "num_iso" => "162", "isd" => "+61"],
				["name" => "Cocos (Keeling) Islands", "iso_two" => "CC", "iso_three" => "CCK", "num_iso" => "166", "isd" => "+672"],
				["name" => "Colombia", "iso_two" => "CO", "iso_three" => "COL", "num_iso" => "170", "isd" => "+57"],
				["name" => "Comoros", "iso_two" => "KM", "iso_three" => "COM", "num_iso" => "174", "isd" => "+269"],
				["name" => "Congo", "iso_two" => "CG", "iso_three" => "COG", "num_iso" => "178", "isd" => "+242"],
				["name" => "Congo, the Democratic Republic of the", "iso_two" => "CD", "iso_three" => "COD", "num_iso" => "180", "isd" => "+242"],
				["name" => "Cook Islands", "iso_two" => "CK", "iso_three" => "COK", "num_iso" => "184", "isd" => "+682"],
				["name" => "Costa Rica", "iso_two" => "CR", "iso_three" => "CRI", "num_iso" => "188", "isd" => "+506"],
				["name" => "Cote D'Ivoire", "iso_two" => "CI", "iso_three" => "CIV", "num_iso" => "384", "isd" => "+225"],
				["name" => "Croatia", "iso_two" => "HR", "iso_three" => "HRV", "num_iso" => "191", "isd" => "+385"],
				["name" => "Cuba", "iso_two" => "CU", "iso_three" => "CUB", "num_iso" => "192", "isd" => "+53"],
				["name" => "Cyprus", "iso_two" => "CY", "iso_three" => "CYP", "num_iso" => "196", "isd" => "+357"],
				["name" => "Czech Republic", "iso_two" => "CZ", "iso_three" => "CZE", "num_iso" => "203", "isd" => "+420"],
				["name" => "Denmark", "iso_two" => "DK", "iso_three" => "DNK", "num_iso" => "208", "isd" => "+45"],
				["name" => "Djibouti", "iso_two" => "DJ", "iso_three" => "DJI", "num_iso" => "262", "isd" => "+253"],
				["name" => "Dominica", "iso_two" => "DM", "iso_three" => "DMA", "num_iso" => "212", "isd" => "+1767"],
				["name" => "Dominican Republic", "iso_two" => "DO", "iso_three" => "DOM", "num_iso" => "214", "isd" => "+1809"],
				["name" => "Ecuador", "iso_two" => "EC", "iso_three" => "ECU", "num_iso" => "218", "isd" => "+593"],
				["name" => "Egypt", "iso_two" => "EG", "iso_three" => "EGY", "num_iso" => "818", "isd" => "+20"],
				["name" => "El Salvador", "iso_two" => "SV", "iso_three" => "SLV", "num_iso" => "222", "isd" => "+503"],
				["name" => "Equatorial Guinea", "iso_two" => "GQ", "iso_three" => "GNQ", "num_iso" => "226", "isd" => "+240"],
				["name" => "Eritrea", "iso_two" => "ER", "iso_three" => "ERI", "num_iso" => "232", "isd" => "+291"],
				["name" => "Estonia", "iso_two" => "EE", "iso_three" => "EST", "num_iso" => "233", "isd" => "+372"],
				["name" => "Ethiopia", "iso_two" => "ET", "iso_three" => "ETH", "num_iso" => "231", "isd" => "+251"],
				["name" => "Falkland Islands (Malvinas)", "iso_two" => "FK", "iso_three" => "FLK", "num_iso" => "238", "isd" => "+500"],
				["name" => "Faroe Islands", "iso_two" => "FO", "iso_three" => "FRO", "num_iso" => "234", "isd" => "+298"],
				["name" => "Fiji", "iso_two" => "FJ", "iso_three" => "FJI", "num_iso" => "242", "isd" => "+679"],
				["name" => "Finland", "iso_two" => "FI", "iso_three" => "FIN", "num_iso" => "246", "isd" => "+358"],
				["name" => "France", "iso_two" => "FR", "iso_three" => "FRA", "num_iso" => "250", "isd" => "+33"],
				["name" => "French Guiana", "iso_two" => "GF", "iso_three" => "GUF", "num_iso" => "254", "isd" => "+594"],
				["name" => "French Polynesia", "iso_two" => "PF", "iso_three" => "PYF", "num_iso" => "258", "isd" => "+689"],
				["name" => "French Southern Territories", "iso_two" => "TF", "iso_three" => "ATF", "num_iso" => "260", "isd" => "+0"],
				["name" => "Gabon", "iso_two" => "GA", "iso_three" => "GAB", "num_iso" => "266", "isd" => "+241"],
				["name" => "Gambia", "iso_two" => "GM", "iso_three" => "GMB", "num_iso" => "270", "isd" => "+220"],
				["name" => "Georgia", "iso_two" => "GE", "iso_three" => "GEO", "num_iso" => "268", "isd" => "+995"],
				["name" => "Germany", "iso_two" => "DE", "iso_three" => "DEU", "num_iso" => "276", "isd" => "+49"],
				["name" => "Ghana", "iso_two" => "GH", "iso_three" => "GHA", "num_iso" => "288", "isd" => "+233"],
				["name" => "Gibraltar", "iso_two" => "GI", "iso_three" => "GIB", "num_iso" => "292", "isd" => "+350"],
				["name" => "Greece", "iso_two" => "GR", "iso_three" => "GRC", "num_iso" => "300", "isd" => "+30"],
				["name" => "Greenland", "iso_two" => "GL", "iso_three" => "GRL", "num_iso" => "304", "isd" => "+299"],
				["name" => "Grenada", "iso_two" => "GD", "iso_three" => "GRD", "num_iso" => "308", "isd" => "+1473"],
				["name" => "Guadeloupe", "iso_two" => "GP", "iso_three" => "GLP", "num_iso" => "312", "isd" => "+590"],
				["name" => "Guam", "iso_two" => "GU", "iso_three" => "GUM", "num_iso" => "316", "isd" => "+1671"],
				["name" => "Guatemala", "iso_two" => "GT", "iso_three" => "GTM", "num_iso" => "320", "isd" => "+502"],
				["name" => "Guinea", "iso_two" => "GN", "iso_three" => "GIN", "num_iso" => "324", "isd" => "+224"],
				["name" => "Guinea-Bissau", "iso_two" => "GW", "iso_three" => "GNB", "num_iso" => "624", "isd" => "+245"],
				["name" => "Guyana", "iso_two" => "GY", "iso_three" => "GUY", "num_iso" => "328", "isd" => "+592"],
				["name" => "Haiti", "iso_two" => "HT", "iso_three" => "HTI", "num_iso" => "332", "isd" => "+509"],
				["name" => "Heard Island and Mcdonald Islands", "iso_two" => "HM", "iso_three" => "HMD", "num_iso" => "334", "isd" => "+0"],
				["name" => "Holy See (Vatican City State)", "iso_two" => "VA", "iso_three" => "VAT", "num_iso" => "336", "isd" => "+39"],
				["name" => "Honduras", "iso_two" => "HN", "iso_three" => "HND", "num_iso" => "340", "isd" => "+504"],
				["name" => "Hong Kong", "iso_two" => "HK", "iso_three" => "HKG", "num_iso" => "344", "isd" => "+852"],
				["name" => "Hungary", "iso_two" => "HU", "iso_three" => "HUN", "num_iso" => "348", "isd" => "+36"],
				["name" => "Iceland", "iso_two" => "IS", "iso_three" => "ISL", "num_iso" => "352", "isd" => "+354"],
				["name" => "India", "iso_two" => "IN", "iso_three" => "IND", "num_iso" => "356", "isd" => "+91"],
				["name" => "Indonesia", "iso_two" => "ID", "iso_three" => "IDN", "num_iso" => "360", "isd" => "+62"],
				["name" => "Iran, Islamic Republic of", "iso_two" => "IR", "iso_three" => "IRN", "num_iso" => "364", "isd" => "+98"],
				["name" => "Iraq", "iso_two" => "IQ", "iso_three" => "IRQ", "num_iso" => "368", "isd" => "+964"],
				["name" => "Ireland", "iso_two" => "IE", "iso_three" => "IRL", "num_iso" => "372", "isd" => "+353"],
				["name" => "Israel", "iso_two" => "IL", "iso_three" => "ISR", "num_iso" => "376", "isd" => "+972"],
				["name" => "Italy", "iso_two" => "IT", "iso_three" => "ITA", "num_iso" => "380", "isd" => "+39"],
				["name" => "Jamaica", "iso_two" => "JM", "iso_three" => "JAM", "num_iso" => "388", "isd" => "+1876"],
				["name" => "Japan", "iso_two" => "JP", "iso_three" => "JPN", "num_iso" => "392", "isd" => "+81"],
				["name" => "Jordan", "iso_two" => "JO", "iso_three" => "JOR", "num_iso" => "400", "isd" => "+962"],
				["name" => "Kazakhstan", "iso_two" => "KZ", "iso_three" => "KAZ", "num_iso" => "398", "isd" => "+7"],
				["name" => "Kenya", "iso_two" => "KE", "iso_three" => "KEN", "num_iso" => "404", "isd" => "+254"],
				["name" => "Kiribati", "iso_two" => "KI", "iso_three" => "KIR", "num_iso" => "296", "isd" => "+686"],
				["name" => "Korea, Democratic People's Republic of", "iso_two" => "KP", "iso_three" => "PRK", "num_iso" => "408", "isd" => "+850"],
				["name" => "Korea, Republic of", "iso_two" => "KR", "iso_three" => "KOR", "num_iso" => "410", "isd" => "+82"],
				["name" => "Kuwait", "iso_two" => "KW", "iso_three" => "KWT", "num_iso" => "414", "isd" => "+965"],
				["name" => "Kyrgyzstan", "iso_two" => "KG", "iso_three" => "KGZ", "num_iso" => "417", "isd" => "+996"],
				["name" => "Lao People's Democratic Republic", "iso_two" => "LA", "iso_three" => "LAO", "num_iso" => "418", "isd" => "+856"],
				["name" => "Latvia", "iso_two" => "LV", "iso_three" => "LVA", "num_iso" => "428", "isd" => "+371"],
				["name" => "Lebanon", "iso_two" => "LB", "iso_three" => "LBN", "num_iso" => "422", "isd" => "+961"],
				["name" => "Lesotho", "iso_two" => "LS", "iso_three" => "LSO", "num_iso" => "426", "isd" => "+266"],
				["name" => "Liberia", "iso_two" => "LR", "iso_three" => "LBR", "num_iso" => "430", "isd" => "+231"],
				["name" => "Libyan Arab Jamahiriya", "iso_two" => "LY", "iso_three" => "LBY", "num_iso" => "434", "isd" => "+218"],
				["name" => "Liechtenstein", "iso_two" => "LI", "iso_three" => "LIE", "num_iso" => "438", "isd" => "+423"],
				["name" => "Lithuania", "iso_two" => "LT", "iso_three" => "LTU", "num_iso" => "440", "isd" => "+370"],
				["name" => "Luxembourg", "iso_two" => "LU", "iso_three" => "LUX", "num_iso" => "442", "isd" => "+352"],
				["name" => "Macao", "iso_two" => "MO", "iso_three" => "MAC", "num_iso" => "446", "isd" => "+853"],
				["name" => "Macedonia, the Former Yugoslav Republic of", "iso_two" => "MK", "iso_three" => "MKD", "num_iso" => "807", "isd" => "+389"],
				["name" => "Madagascar", "iso_two" => "MG", "iso_three" => "MDG", "num_iso" => "450", "isd" => "+261"],
				["name" => "Malawi", "iso_two" => "MW", "iso_three" => "MWI", "num_iso" => "454", "isd" => "+265"],
				["name" => "Malaysia", "iso_two" => "MY", "iso_three" => "MYS", "num_iso" => "458", "isd" => "+60"],
				["name" => "Maldives", "iso_two" => "MV", "iso_three" => "MDV", "num_iso" => "462", "isd" => "+960"],
				["name" => "Mali", "iso_two" => "ML", "iso_three" => "MLI", "num_iso" => "466", "isd" => "+223"],
				["name" => "Malta", "iso_two" => "MT", "iso_three" => "MLT", "num_iso" => "470", "isd" => "+356"],
				["name" => "Marshall Islands", "iso_two" => "MH", "iso_three" => "MHL", "num_iso" => "584", "isd" => "+692"],
				["name" => "Martinique", "iso_two" => "MQ", "iso_three" => "MTQ", "num_iso" => "474", "isd" => "+596"],
				["name" => "Mauritania", "iso_two" => "MR", "iso_three" => "MRT", "num_iso" => "478", "isd" => "+222"],
				["name" => "Mauritius", "iso_two" => "MU", "iso_three" => "MUS", "num_iso" => "480", "isd" => "+230"],
				["name" => "Mayotte", "iso_two" => "YT", "iso_three" => "MYT", "num_iso" => "175", "isd" => "+269"],
				["name" => "Mexico", "iso_two" => "MX", "iso_three" => "MEX", "num_iso" => "484", "isd" => "+52"],
				["name" => "Micronesia, Federated States of", "iso_two" => "FM", "iso_three" => "FSM", "num_iso" => "583", "isd" => "+691"],
				["name" => "Moldova, Republic of", "iso_two" => "MD", "iso_three" => "MDA", "num_iso" => "498", "isd" => "+373"],
				["name" => "Monaco", "iso_two" => "MC", "iso_three" => "MCO", "num_iso" => "492", "isd" => "+377"],
				["name" => "Mongolia", "iso_two" => "MN", "iso_three" => "MNG", "num_iso" => "496", "isd" => "+976"],
				["name" => "Montserrat", "iso_two" => "MS", "iso_three" => "MSR", "num_iso" => "500", "isd" => "+1664"],
				["name" => "Morocco", "iso_two" => "MA", "iso_three" => "MAR", "num_iso" => "504", "isd" => "+212"],
				["name" => "Mozambique", "iso_two" => "MZ", "iso_three" => "MOZ", "num_iso" => "508", "isd" => "+258"],
				["name" => "Myanmar", "iso_two" => "MM", "iso_three" => "MMR", "num_iso" => "104", "isd" => "+95"],
				["name" => "Namibia", "iso_two" => "NA", "iso_three" => "NAM", "num_iso" => "516", "isd" => "+264"],
				["name" => "Nauru", "iso_two" => "NR", "iso_three" => "NRU", "num_iso" => "520", "isd" => "+674"],
				["name" => "Nepal", "iso_two" => "NP", "iso_three" => "NPL", "num_iso" => "524", "isd" => "+977"],
				["name" => "Netherlands", "iso_two" => "NL", "iso_three" => "NLD", "num_iso" => "528", "isd" => "+31"],
				["name" => "Netherlands Antilles", "iso_two" => "AN", "iso_three" => "ANT", "num_iso" => "530", "isd" => "+599"],
				["name" => "New Caledonia", "iso_two" => "NC", "iso_three" => "NCL", "num_iso" => "540", "isd" => "+687"],
				["name" => "New Zealand", "iso_two" => "NZ", "iso_three" => "NZL", "num_iso" => "554", "isd" => "+64"],
				["name" => "Nicaragua", "iso_two" => "NI", "iso_three" => "NIC", "num_iso" => "558", "isd" => "+505"],
				["name" => "Niger", "iso_two" => "NE", "iso_three" => "NER", "num_iso" => "562", "isd" => "+227"],
				["name" => "Nigeria", "iso_two" => "NG", "iso_three" => "NGA", "num_iso" => "566", "isd" => "+234"],
				["name" => "Niue", "iso_two" => "NU", "iso_three" => "NIU", "num_iso" => "570", "isd" => "+683"],
				["name" => "Norfolk Island", "iso_two" => "NF", "iso_three" => "NFK", "num_iso" => "574", "isd" => "+672"],
				["name" => "Northern Mariana Islands", "iso_two" => "MP", "iso_three" => "MNP", "num_iso" => "580", "isd" => "+1670"],
				["name" => "Norway", "iso_two" => "NO", "iso_three" => "NOR", "num_iso" => "578", "isd" => "+47"],
				["name" => "Oman", "iso_two" => "OM", "iso_three" => "OMN", "num_iso" => "512", "isd" => "+968"],
				["name" => "Pakistan", "iso_two" => "PK", "iso_three" => "PAK", "num_iso" => "586", "isd" => "+92"],
				["name" => "Palau", "iso_two" => "PW", "iso_three" => "PLW", "num_iso" => "585", "isd" => "+680"],
				["name" => "Palestinian Territory, Occupied", "iso_two" => "PS", "iso_three" => "PSE", "num_iso" => "275", "isd" => "+970"],
				["name" => "Panama", "iso_two" => "PA", "iso_three" => "PAN", "num_iso" => "591", "isd" => "+507"],
				["name" => "Papua New Guinea", "iso_two" => "PG", "iso_three" => "PNG", "num_iso" => "598", "isd" => "+675"],
				["name" => "Paraguay", "iso_two" => "PY", "iso_three" => "PRY", "num_iso" => "600", "isd" => "+595"],
				["name" => "Peru", "iso_two" => "PE", "iso_three" => "PER", "num_iso" => "604", "isd" => "+51"],
				["name" => "Philippines", "iso_two" => "PH", "iso_three" => "PHL", "num_iso" => "608", "isd" => "+63"],
				["name" => "Pitcairn", "iso_two" => "PN", "iso_three" => "PCN", "num_iso" => "612", "isd" => "+0"],
				["name" => "Poland", "iso_two" => "PL", "iso_three" => "POL", "num_iso" => "616", "isd" => "+48"],
				["name" => "Portugal", "iso_two" => "PT", "iso_three" => "PRT", "num_iso" => "620", "isd" => "+351"],
				["name" => "Puerto Rico", "iso_two" => "PR", "iso_three" => "PRI", "num_iso" => "630", "isd" => "+1787"],
				["name" => "Qatar", "iso_two" => "QA", "iso_three" => "QAT", "num_iso" => "634", "isd" => "+974"],
				["name" => "Reunion", "iso_two" => "RE", "iso_three" => "REU", "num_iso" => "638", "isd" => "+262"],
				["name" => "Romania", "iso_two" => "RO", "iso_three" => "ROM", "num_iso" => "642", "isd" => "+40"],
				["name" => "Russian Federation", "iso_two" => "RU", "iso_three" => "RUS", "num_iso" => "643", "isd" => "+70"],
				["name" => "Rwanda", "iso_two" => "RW", "iso_three" => "RWA", "num_iso" => "646", "isd" => "+250"],
				["name" => "Saint Helena", "iso_two" => "SH", "iso_three" => "SHN", "num_iso" => "654", "isd" => "+290"],
				["name" => "Saint Kitts and Nevis", "iso_two" => "KN", "iso_three" => "KNA", "num_iso" => "659", "isd" => "+1869"],
				["name" => "Saint Lucia", "iso_two" => "LC", "iso_three" => "LCA", "num_iso" => "662", "isd" => "+1758"],
				["name" => "Saint Pierre and Miquelon", "iso_two" => "PM", "iso_three" => "SPM", "num_iso" => "666", "isd" => "+508"],
				["name" => "Saint Vincent and the Grenadines", "iso_two" => "VC", "iso_three" => "VCT", "num_iso" => "670", "isd" => "+1784"],
				["name" => "Samoa", "iso_two" => "WS", "iso_three" => "WSM", "num_iso" => "882", "isd" => "+684"],
				["name" => "San Marino", "iso_two" => "SM", "iso_three" => "SMR", "num_iso" => "674", "isd" => "+378"],
				["name" => "Sao Tome and Principe", "iso_two" => "ST", "iso_three" => "STP", "num_iso" => "678", "isd" => "+239"],
				["name" => "Saudi Arabia", "iso_two" => "SA", "iso_three" => "SAU", "num_iso" => "682", "isd" => "+966"],
				["name" => "Senegal", "iso_two" => "SN", "iso_three" => "SEN", "num_iso" => "686", "isd" => "+221"],
				["name" => "Serbia and Montenegro", "iso_two" => "CS", "iso_three" => "SCG", "num_iso" => "891", "isd" => "+381"],
				["name" => "Seychelles", "iso_two" => "SC", "iso_three" => "SYC", "num_iso" => "690", "isd" => "+248"],
				["name" => "Sierra Leone", "iso_two" => "SL", "iso_three" => "SLE", "num_iso" => "694", "isd" => "+232"],
				["name" => "Singapore", "iso_two" => "SG", "iso_three" => "SGP", "num_iso" => "702", "isd" => "+65"],
				["name" => "Slovakia", "iso_two" => "SK", "iso_three" => "SVK", "num_iso" => "703", "isd" => "+421"],
				["name" => "Slovenia", "iso_two" => "SI", "iso_three" => "SVN", "num_iso" => "705", "isd" => "+386"],
				["name" => "Solomon Islands", "iso_two" => "SB", "iso_three" => "SLB", "num_iso" => "90", "isd" => "+677"],
				["name" => "Somalia", "iso_two" => "SO", "iso_three" => "SOM", "num_iso" => "706", "isd" => "+252"],
				["name" => "South Africa", "iso_two" => "ZA", "iso_three" => "ZAF", "num_iso" => "710", "isd" => "+27"],
				["name" => "South Georgia and the South Sandwich Islands", "iso_two" => "GS", "iso_three" => "SGS", "num_iso" => "239", "isd" => "+0"],
				["name" => "Spain", "iso_two" => "ES", "iso_three" => "ESP", "num_iso" => "724", "isd" => "+34"],
				["name" => "Sri Lanka", "iso_two" => "LK", "iso_three" => "LKA", "num_iso" => "144", "isd" => "+94"],
				["name" => "Sudan", "iso_two" => "SD", "iso_three" => "SDN", "num_iso" => "736", "isd" => "+249"],
				["name" => "Suriname", "iso_two" => "SR", "iso_three" => "SUR", "num_iso" => "740", "isd" => "+597"],
				["name" => "Svalbard and Jan Mayen", "iso_two" => "SJ", "iso_three" => "SJM", "num_iso" => "744", "isd" => "+47"],
				["name" => "Swaziland", "iso_two" => "SZ", "iso_three" => "SWZ", "num_iso" => "748", "isd" => "+268"],
				["name" => "Sweden", "iso_two" => "SE", "iso_three" => "SWE", "num_iso" => "752", "isd" => "+46"],
				["name" => "Switzerland", "iso_two" => "CH", "iso_three" => "CHE", "num_iso" => "756", "isd" => "+41"],
				["name" => "Syrian Arab Republic", "iso_two" => "SY", "iso_three" => "SYR", "num_iso" => "760", "isd" => "+963"],
				["name" => "Taiwan, Province of China", "iso_two" => "TW", "iso_three" => "TWN", "num_iso" => "158", "isd" => "+886"],
				["name" => "Tajikistan", "iso_two" => "TJ", "iso_three" => "TJK", "num_iso" => "762", "isd" => "+992"],
				["name" => "Tanzania, United Republic of", "iso_two" => "TZ", "iso_three" => "TZA", "num_iso" => "834", "isd" => "+255"],
				["name" => "Thailand", "iso_two" => "TH", "iso_three" => "THA", "num_iso" => "764", "isd" => "+66"],
				["name" => "Timor-Leste", "iso_two" => "TL", "iso_three" => "TLS", "num_iso" => "626", "isd" => "+670"],
				["name" => "Togo", "iso_two" => "TG", "iso_three" => "TGO", "num_iso" => "768", "isd" => "+228"],
				["name" => "Tokelau", "iso_two" => "TK", "iso_three" => "TKL", "num_iso" => "772", "isd" => "+690"],
				["name" => "Tonga", "iso_two" => "TO", "iso_three" => "TON", "num_iso" => "776", "isd" => "+676"],
				["name" => "Trinidad and Tobago", "iso_two" => "TT", "iso_three" => "TTO", "num_iso" => "780", "isd" => "+1868"],
				["name" => "Tunisia", "iso_two" => "TN", "iso_three" => "TUN", "num_iso" => "788", "isd" => "+216"],
				["name" => "Turkey", "iso_two" => "TR", "iso_three" => "TUR", "num_iso" => "792", "isd" => "+90"],
				["name" => "Turkmenistan", "iso_two" => "TM", "iso_three" => "TKM", "num_iso" => "795", "isd" => "+7370"],
				["name" => "Turks and Caicos Islands", "iso_two" => "TC", "iso_three" => "TCA", "num_iso" => "796", "isd" => "+1649"],
				["name" => "Tuvalu", "iso_two" => "TV", "iso_three" => "TUV", "num_iso" => "798", "isd" => "+688"],
				["name" => "Uganda", "iso_two" => "UG", "iso_three" => "UGA", "num_iso" => "800", "isd" => "+256"],
				["name" => "Ukraine", "iso_two" => "UA", "iso_three" => "UKR", "num_iso" => "804", "isd" => "+380"],
				["name" => "United Arab Emirates", "iso_two" => "AE", "iso_three" => "ARE", "num_iso" => "784", "isd" => "+971"],
				["name" => "United Kingdom", "iso_two" => "GB", "iso_three" => "GBR", "num_iso" => "826", "isd" => "+44"],
				["name" => "United States", "iso_two" => "US", "iso_three" => "USA", "num_iso" => "840", "isd" => "+1"],
				["name" => "United States Minor Outlying Islands", "iso_two" => "UM", "iso_three" => "UMI", "num_iso" => "581", "isd" => "+1"],
				["name" => "Uruguay", "iso_two" => "UY", "iso_three" => "URY", "num_iso" => "858", "isd" => "+598"],
				["name" => "Uzbekistan", "iso_two" => "UZ", "iso_three" => "UZB", "num_iso" => "860", "isd" => "+998"],
				["name" => "Vanuatu", "iso_two" => "VU", "iso_three" => "VUT", "num_iso" => "548", "isd" => "+678"],
				["name" => "Venezuela", "iso_two" => "VE", "iso_three" => "VEN", "num_iso" => "862", "isd" => "+58"],
				["name" => "Viet Nam", "iso_two" => "VN", "iso_three" => "VNM", "num_iso" => "704", "isd" => "+84"],
				["name" => "Virgin Islands, British", "iso_two" => "VG", "iso_three" => "VGB", "num_iso" => "92", "isd" => "+1284"],
				["name" => "Virgin Islands, U.s.", "iso_two" => "VI", "iso_three" => "VIR", "num_iso" => "850", "isd" => "+1340"],
				["name" => "Wallis and Futuna", "iso_two" => "WF", "iso_three" => "WLF", "num_iso" => "876", "isd" => "+681"],
				["name" => "Western Sahara", "iso_two" => "EH", "iso_three" => "ESH", "num_iso" => "732", "isd" => "+212"],
				["name" => "Yemen", "iso_two" => "YE", "iso_three" => "YEM", "num_iso" => "887", "isd" => "+967"],
				["name" => "Zambia", "iso_two" => "ZM", "iso_three" => "ZMB", "num_iso" => "894", "isd" => "+260"],
				["name" => "Zimbabwe", "iso_two" => "ZW", "iso_three" => "ZWE", "num_iso" => "716", "isd" => "+263"],
			];
		}

		private function _create($drop, $table, $data = []) {
			if ($drop) {
				echo "Deleting $table....\n";
				$this->dbforge->drop_table($table, true);
			}
			echo "Creating $table....\n";
			$this->dbforge->create_table($table, true);
			if (is_array($data) && count($data) > 0) {
				echo "Inserting data in $table....\n";
				(is_array(reset($data))) ? $this->db->insert_batch($table, $data) : $this->db->insert($table, $data);
			}
		}
	}
}
