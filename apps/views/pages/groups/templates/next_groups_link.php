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
if (!empty($next_url)) {
?>
    <div class="row pb-5">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <a href="<?= $next_url; ?>" class="btn btn-success pl-5 pr-5 wpbtn slbtn btn-lg">
                    <i class="fa fa-forward pr-2" aria-hidden="true"></i>
                    <span>Load more group..</span>
                </a>
            </div>
        </div>
    </div>
<?php
}
?>