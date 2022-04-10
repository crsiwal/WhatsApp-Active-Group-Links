<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ************************************************************
 *  File: add_group.php
 *  Path: application/views/dashboard/blocks/homepage/add_group.php
 *  Description: It's a homepage Text box for add group.
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         30/05/2021              Created
 *
 */
$mobile = isset($isMobile) ? $isMobile : false;
$mobile = true;
?>
<div class="card-group">
    <?php
    for ($i = 0; $i < 5; $i++) {
    ?>
        <div class="card text-center border-0">
            <div class="pt-3">
                <a href="">
                    <img class="card-img-top ct-logo" src="<?= icon_url("category/news.png"); ?>" alt="Card image cap">
                </a>
            </div>
            <div class="card-body">
                <h5 class="card-title">
                    <a href="">News Groups</a>
                </h5>
                <p class="card-text">Groups who are related to News</p>
            </div>
        </div>
    <?php
    }
    ?>
</div>