<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: header_db_blank.php
 *  Path: application/views/header/header_db_blank.php
 *  Description: This is blank Header of dashboard.
 * 
 * Function Added:
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         19/01/2020              Created
 *
 */
?>
<header class="header d-flex flex-wrap">
    <div class="header-logo d-flex justify-content-center align-items-center">
        <div class="image-container d-flex justify-content-around align-items-center">
            <a href="<?php url("/"); ?>">
                <img class="mobile-navbar-button" src="<?php icon_url('menu.png') ?>" alt="">
                <img src="<?php icon_url('sabnews.png') ?>">
            </a>
        </div>
    </div>
    <div class="header-action d-flex align-items-center ml-auto">
        <div class="header-action-download header-action-item d-flex justify-content-center">
            <img id="waiting" class="hidden" src="<?php icon_url('wait.gif') ?>" alt="">
        </div>
    </div>
</header>