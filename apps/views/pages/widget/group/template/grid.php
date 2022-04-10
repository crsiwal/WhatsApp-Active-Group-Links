<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ************************************************************
 *  File: grid.php
 *  Path: apps/views/blocks/group/template/grid.php
 *  Description: It's a group grid layout.
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         31/05/2021              Created
 *
 */
$name = empty($name) ? "Whatsapp Group Invite" : $name;
?>
<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 col-6 pl-2 pr-2 pl-sm-1 pr-sm-1">
    <div class="card text-center border-0 groupcard">
        <div class="pt-3">
            <a target="_blank" href="<?= $url; ?>">
                <img class="card-img-top gp-logo rounded-circle" src="<?= $icon; ?>" alt="<?= $name; ?>">
            </a>
        </div>
        <div class="card-body">
            <h5 class="card-title">
                <a target="_blank" href="<?= $url; ?>"><?= $name; ?></a>
            </h5>
            <p class="card-text ellipsis">
                <a href="<?= $curl; ?>"><?= $category; ?></a>
            </p>
        </div>
    </div>
</div>