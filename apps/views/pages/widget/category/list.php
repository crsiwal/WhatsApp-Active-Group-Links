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
<div class="row pt-4 pb-4">
    <div class="container">
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