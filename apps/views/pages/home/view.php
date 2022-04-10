<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: access.php
 *  Path: application/views/pages/homepage.php
 *  Description: It's a homepage of website.
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
        $this->load->view('pages/home/templates/add_group');
        $this->load->view('pages/widget/category/top');
        $this->load->view('pages/widget/group/trending');
        ?>
    </div>
</main>