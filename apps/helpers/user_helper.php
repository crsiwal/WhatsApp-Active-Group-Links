<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: user_helper.php
 *  Path: application/helpers/user_helper.php
 *  Description: This helper add multiple common functions for user.
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              --------------
 *  Rahul Siwal         09/02/2020              Created
 *
 */

function email_exists($email) {
    $ci = &get_instance();
    $user = $ci->user->get_user_by("email", $email);
    if ($user) {
        return $user;
    }
    return FALSE;
}

function checkLogin() {
    if (!is_login()) {
        next_page("login");
    }
}

function logout() {
    $ci = &get_instance();
    $ci->user->logged_out_user();
    next_page("login");
}

function get_logged_in_user_id() {
    $ci = &get_instance();
    return $ci->user->get_logged_in_user_id();
}

function is_login() {
    $ci = &get_instance();
    return $ci->user->user_logged_in();
}

function user_checks($checks, $false_redirect, $true_redirect = FALSE) {
    $ci = &get_instance();
    $flag = TRUE;
    foreach ($checks as $check) {
        switch ($check) {
            case "is_admin":
                $flag = is_logged_in_user_type("admin") ? TRUE : FALSE;
                break;
        }
    }
    (!$flag) ? next_page($false_redirect) : (($true_redirect) ? next_page($true_redirect) : FALSE);
}

function is_logged_in_user_type($user_type) {
    $user_type_map = user_type_mapping($user_type, TRUE);
    $user_type_id = get_user_meta("user_role");
    return $user_type_map === $user_type_id ? TRUE : FALSE;
}

function get_user_type_id($user_type) {
    return user_type_mapping($user_type, TRUE);
}

function logged_in_user_type() {
    return user_type_mapping(get_user_meta("user_role"));
}

function user_type_mapping($user_type_name = NULL, $flip = FALSE) {
    $ci = &get_instance();
    $map = array_map('strtolower', $ci->config->item("user_roles"));
    if ($flip) {
        $map = array_flip($map);
    }
    if (!empty($user_type_name)) {
        $user_type = strtolower($user_type_name);
        return isset($map[$user_type]) ? $map[$user_type] : FALSE;
    } else {
        return $map;
    }
}

function get_user_meta($meta) {
    $ci = &get_instance();
    return $ci->user->get_user_meta($meta);
}
