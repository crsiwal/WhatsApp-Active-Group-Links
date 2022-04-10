<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: sub_categories.php
 *  Path: application/views/pages/sub_categories.php
 *  Description: It's a sub categories of any category.
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         25/02/2022              Created
 *
 */
?>
<main class="main">
    <div class="container-fluid">
        <?php
        if (is_mobile()) {
            $this->load->view('pages/sub_category/templates/summery_xs');
        } else {
            $this->load->view('pages/sub_category/templates/summery_md');
        }
        $this->load->view('pages/widget/category/list');
        $this->load->view('pages/widget/group/trending');
        ?>
    </div>
</main>