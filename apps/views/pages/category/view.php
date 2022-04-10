<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: categories.php
 *  Path: application/views/pages/categories.php
 *  Description: It's a categories of website.
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         29/05/2021              Created
 *
 */
?>
<main class="main">
    <div class="container-fluid">
        <!-- Block: Add Groups -->
        <?php
        if (is_mobile()) {
            $this->load->view('pages/category/templates/summery_xs');
        } else {
            $this->load->view('pages/category/templates/summery_md');
        }
        $this->load->view('pages/widget/category/list');
        $this->load->view('pages/widget/group/trending');
        ?>
    </div>
</main>