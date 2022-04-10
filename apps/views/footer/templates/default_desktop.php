<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: desktop.php
 *  Path: apps/views/block/footer/desktop.php
 *  Description: it's a default footer of website.
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         18/08/2021              Created
 *
 */
?>
<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <span><?= date("Y"); ?> © Whatsapp Groups</span>
            </div>
            <!-- End Col -->
            <div class="col-md-6">
                <div class="copyright-menu">
                    <ul>
                        <?php
                        addmenu("public", "Term Of Service", "terms", "", is_active_page("termofservice"));
                        addmenu("public", "Privacy Policy", "privacy", "", is_active_page("privacypolicy"));
                        addmenu("public", "Disclaimer", "disclaimer", "", is_active_page("disclaimer"));
                        ?>
                    </ul>
                </div>
            </div>
            <!-- End col -->
        </div>
        <!-- End Row -->
    </div>
    <!-- End Copyright Container -->
</div>
<!-- End Copyright -->
<!-- Back to top -->
<div id="back-to-top" class="back-to-top">
    <button class="btn btn-dark" title="Back to Top" style="display: block;">
        <i class="fa fa-angle-up"></i>
    </button>
</div>
<!-- End Back to top -->