<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: header_db_default.php
 *  Path: application/views/header/header_db_default.php
 *  Description: This is default Header of dashboard.
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
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
 */
?>

<header>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-wp static-top">
        <div class="container">
            <a class="navbar-brand" href="<?= url(); ?>">
                <img src="<?= icon_url('logo.svg') ?>" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <?php
                    addmenu("public", "About Us", "about", "", is_active_page("aboutus"), true);
                    addmenu("public", "Categories", "categories", "", is_active_page("categories"), true);
                    addmenu("public", "Contact Us", "contact", "", is_active_page("contactus"), true);
                    //addmenu("public", "Login", "login", "", is_active_page("login"), true);
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>