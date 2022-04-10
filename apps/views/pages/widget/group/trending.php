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
?>
<div class="row bg-wpl pb-5">
    <div class="container pb-5">
        <div class="row">
            <div class="col-12 m-auto pt-4 pb-4">
                <h2 class="mt-4 mb-3 ttlbefore">Trending Groups</h2>
                <h5 class="text-center">You can findout the top trending groups whose are active and genuine.</h5>
            </div>
            <?php
            if (is_array($trending) && count($trending) > 0) {
                foreach ($trending as $group) {
                    $this->load->view('pages/widget/group/template/grid', $group);
                }
            } else {
            ?>
                <div class="col-12 m-auto pt-4 pb-4">
                    <h5 class="text-center">We do not have any trending group now. Please try again lator.</h5>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>