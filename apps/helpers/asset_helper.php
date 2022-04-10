<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: asset.php
 *  Path: application/helpers/asset.php
 *  Description: This helper add the assets related functions.
 * 
 * Function Added:
 * 
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              --------------
 *  Rahul Siwal         26/01/2020              Created
 *
 */

function path($path) {
    return FCPATH . $path;
}

function filePath($filepath) {
    return path("public/" . $filepath);
}

function asset_path($filepath) {
    return path("assets/" . $filepath);
}

function store_file_path($file_name = "store") {
    return path("storage/current/$file_name.log");
}

function store_move_path() {
    return path("storage/history/" . get_date("Ym") . "/" . get_date("d") . "/");
}

function url($url = "", $return = FALSE) {
    if ($return === FALSE) {
        echo base_url($url);
    } else {
        return base_url($url);
    }
}

function admin_url($url = "", $return = FALSE) {
    return url("admin/$url", $return);
}

function asset_url($filename = "", $return = FALSE) {
    return url("assets/$filename", $return);
}

function category_url($slug = "", $return = FALSE) {
    return url("categories/$slug", $return);
}

function category_group_url($slug = "", $return = FALSE) {
    return url("groups/$slug", $return);
}

function group_url($invite_key = "", $return = FALSE) {
    return url("invite/$invite_key", $return);
}

function source_url($filename = "", $return = FALSE) {
    return url("source/" . $filename, $return);
}

function source_path($filename = "") {
    return path("source/" . $filename);
}

function icon_url($filename = "", $return = FALSE) {
    return asset_url("icons/" . $filename, $return);
}

function font_awesome($filename = "", $return = FALSE) {
    return asset_url("icons/" . $filename, $return);
}

function whatsapp_join_url($invite_key, $return = FALSE) {
    $ci = &get_instance();
    $url = $ci->config->item('whatsapp_base_url') . "/" . ltrim($invite_key, "/");
    if ($return) {
        return $url;
    } else {
        echo $url;
    }
}

function cdn_url($url = "") {
    $ci = &get_instance();
    return $ci->config->item('cdn_url') . "/" . ltrim($url, "/");
}

function next_page($url = "", $in_house = TRUE) {
    redirect(($in_house) ? url($url, TRUE) : $url);
    die();
}

function upload_file($filename, $config) {
    if (!empty($filename)) {
        $ci = &get_instance();
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }
        $ci->load->library('upload', $config);
        if (!$ci->upload->do_upload($filename)) {
            // $ci->upload->display_errors()
            return false;
        } else {
            return $ci->upload->data();
        }
    }
    return false;
}

function minify($assets) {
    $ci = &get_instance();
    return ($ci->config->item(($assets === 'css') ? 'minify_css' : 'minify_js') === true) ? true : false;
}

function get_local_image_by_url($url, $name = "") {
    clog("Calling", __METHOD__);
    $ci = &get_instance();
    if (empty($name)) {
        $name = url_file_name($url);
    }
    $extention = url_file_extention($url);
    $path = upload_image_path_relative();
    $filename = $name . "." . $extention;
    if ($ci->curl->save($url, $path, $filename)) {
        return $path . $filename;
    }
    return FALSE;
}

function upload_image_path_relative($filename = "") {
    $directory_path = date("Y") . "/" . date("m") . "/" . get_date("d");
    return "source/$directory_path/$filename";
}

function url_file_extention($file_url) {
    return pathinfo(
        parse_url($file_url, PHP_URL_PATH),
        PATHINFO_EXTENSION
    );
}

function url_file_name($file_url) {
    return pathinfo(
        parse_url($file_url, PHP_URL_PATH),
        PATHINFO_FILENAME
    );
}

function whatsapp_invite_key($invite_url) {
    $without_query_string = rtrim(strtok($invite_url, '?'), "/");
    return basename($without_query_string);
}

function pagination($base_url, $row_count, $limit = 25) {
    $ci = &get_instance();
    $ci->load->library("pagination");
    $ci->pagination->initialize([
        "base_url" => url($base_url, TRUE),
        "total_rows" => $row_count,
        "per_page" => $limit,
        "full_tag_open" => '<ul class="pagination">',
        "full_tag_close" => '</ul>',
        "first_tag_open" => '<li class="page-link">',
        "first_tag_close" => '</li>',
        "prev_link" => '«',
        "prev_tag_open" => '<li class="page-link prev">',
        "prev_tag_close" => '</li>',
        "next_link" => '»',
        "next_tag_open" => '<li class="page-link">',
        "next_tag_close" => '</li>',
        "last_tag_open" => '<li class="page-link">',
        "last_tag_close" => '</li>',
        "cur_tag_open" => '<li class="page-item active"><a class="page-link" href="#">',
        "cur_tag_close" => '</a></li>',
        "num_tag_open" => '<li class="page-link">',
        "num_tag_close" => '</li>'
    ]);
    return $ci->pagination->create_links();
}

function canvas($width = 250, $height = 250, $return = true) {
    $json = htmlspecialchars(json_encode(array(
        "width" => $width,
        "height" => $height
    )));
    if (!$return) {
        echo $json;
    } else {
        return $json;
    }
}
