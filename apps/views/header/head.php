<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: header_db_head.php
 *  Path: application/views/header/header_db_head.php
 *  Description: This is a main header file which create html head of all pages.
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              --------------
 *  Rahul Siwal         29/05/2021              Created
 *
 *  Copyright (c) 2018 - AdGyde Solutions Private Limited
 *  All Rights Reserved.
 *
 *  NOTICE:  All information contained herein is, and remains
 *  the property of AdGyde Solutions Private Limited.
 *  The intellectual and technical concepts contained herein
 *  are proprietary to AdGyde Solutions Private Limited and
 *  are considered trade secrets and/or confidential under Law
 *  Dissemination of this information or reproduction of this material,
 *  in whole or in part, is strictly forbidden unless prior written
 *  permission is obtained from AdGyde Solutions Private Limited.
 *
 */
$css = isset($css) ? $css : array();
$js = isset($js) ? $js : array();
$sidebar_left = isset($sidebar_left) ? $sidebar_left : "default";
$header_end = (is_string($header) && $tmp = explode("_", $header)) ? end($tmp) : "";
$page_name = get_page_name();
?>
<!DOCTYPE html>
<html lang="en-US" xmlns:fb="https://www.facebook.com/2008/fbml">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noodp,noydir" />
    <meta name="description" content="<?= $description; ?>" />
    <link rel="shortcut icon" href="<?= url("favicon.ico"); ?>" type="image/x-icon">
    <link rel="icon" href="<?= url("favicon.ico"); ?>" type="image/x-icon">
    <noscript>
        <meta http-equiv="refresh" content="0; URL=/?_fb_noscript=1" />
    </noscript>
    <title><?php echo $title; ?></title>
    <?php
    foreach ($css as $cssId => $cssPath) {
        echo "<link rel='stylesheet' id='$cssId'  href='$cssPath'/>\n";
    }
    foreach ($js as $jsId => $jsPath) {
        echo "<script id='$jsId' type='text/javascript' src='$jsPath'></script>\n";
    }
    ?>
</head>

<body id="app" class="" data-page="<?= $page_name; ?>">
    <div class="min-vh-100 <?= $header_end; ?>">