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
if (is_mobile()) {
?>
    <div class="row pt-4 pb-4">
        <div class="col-12">
            <h2 class="h5 d-flex justify-content-center mt-4 mb-2"><?= $category; ?></h2>
            <h5 class="text-center ttlbefore"></h5>
        </div>
    </div>
<?php
} else {
?>
    <div class="row pt-4 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-12 m-auto">
                    <h2 class="mt-4 mb-3 ttlbefore"><?= $category; ?> Groups</h2>
                    <h5 class="text-center"></h5>
                </div>
            </div>
        </div>
    </div>
<?php
} ?>