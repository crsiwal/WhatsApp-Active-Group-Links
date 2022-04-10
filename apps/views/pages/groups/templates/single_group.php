<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ************************************************************
 *  File: single.php
 *  Path: application/views/dashboard/blocks/group/single.php
 *  Description: It's a whatsapp group single page.
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         05/06/2021              Created
 *
 */
$name = empty($name) ? "Whatsapp Group Invite" : $name;
?>
<div class="row">
    <div class="container pt-5">
        <div class="row pt-5">
            <div class="card col-12 text-center border-0 bg-transparent">
                <div class="pt-3">
                    <img class="card-img-top gp-logo rounded-circle" src="<?= $icon; ?>" alt="<?= $name; ?>">
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?= $name; ?></h5>
                    <p class="card-text">Jon WhatsApp Group Invite</p>
                    <?php $this->load->view('pages/groups/templates/whatsapp_button'); ?>
                </div>
            </div>
        </div>
    </div>
</div>