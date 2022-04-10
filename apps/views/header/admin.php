<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: admin.php
 *  Path: application/views/header/admin.php
 *  Description: This is admin dashboard Header of dashboard.
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         26/02/2022              Created
 */
?>

<header>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-wp static-top">
        <div class="container">
            <a class="navbar-brand" href="<?= admin_url(); ?>">
                <img src="<?= icon_url('logo.svg') ?>" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <?php
                    addmenu("admin_overview", "Dashboard", "admin", "", is_active_page("dashboard"));
                    addmenu("admin_overview", "Groups", "admin/groups", "", is_active_page("groups"));
                    addmenu("admin_overview", "Accounts", "admin/accounts", "", is_active_page("accounts"));
                    addmenu("admin_overview", "Category", "admin/category", "", is_active_page("category"));
                    addmenu("admin_overview", "Settings", "admin/settings", "", is_active_page("settings"));
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>