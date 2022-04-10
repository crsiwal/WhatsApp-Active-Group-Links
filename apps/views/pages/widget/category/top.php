<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ************************************************************
 *  File: top_categories.php
 *  Path: application/views/dashboard/blocks/category/top_categories.php
 *  Description: It's a listing of top catgories.
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
        <div class="col-12 pb-4">
            <h2 class="mt-4 mb-3 ttlbefore">Categories</h2>
            <h5 class="text-center">Select any specific category to view the groups.</h5>
        </div>
        <div class="col-12">
            <div class="row">
                <?php
                if (is_array($categories)) {
                    foreach ($categories as $index => $category) {
                        $category->colview = ($index % 2 == 0) ? "pr-1" : "pl-1";
                        $this->load->view('pages/widget/category/template/grid', $category);
                    }
                }
                ?>
            </div>
        </div>
    </div>
<?php
} else {
?>
    <div class="row pt-4 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-12 m-auto pb-4">
                    <h2 class="mt-4 mb-3 ttlbefore">Categories</h2>
                    <h5 class="text-center">Select any specific category to view the groups.</h5>
                </div>
                <?php
                if (is_array($categories)) {
                    foreach ($categories as $category) {
                        $this->load->view('pages/widget/category/template/grid', $category);
                    }
                }
                ?>
            </div>
        </div>
    </div>
<?php
}
?>