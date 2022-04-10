<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: sidebar_left_default.php
 *  Path: application/views/sidebar/sidebar_left_default.php
 *  Description: It's a default left sidebar.
 * 
 * Function Added:
 * 
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         26/01/2020              Created
 *
 */
?>
<aside class="sidenav sidenav-expanded-button">
    <div class="sidenav-links-container">
        <div class="sidenav-links">
            <ul>
                <?php
                addmenu("admin_overview", "Dashboard", "admin", "fa-home", is_active_page("overview"));
                addmenu("admin_overview", "Groups", "admin/groups", "fa-bell", is_active_page("newcampaign"));
                addmenu("admin_overview", "Category", "admin/category", "fa-bar-chart", is_active_page("category"));
                addmenu("admin_overview", "Users", "admin/users", "fa-user", is_active_page(["users", "createusers"]));
                addgroup("admin_overview", "Settings", "fa-cog", array(
                    ["MANAGE_USERS", "General", "admin/settings"],
                    ["MANAGE_USERS", "Users", "admin/users"],
                ), is_active_page(["users", "general"]));
                ?>
            </ul>
        </div>
    </div>
</aside>