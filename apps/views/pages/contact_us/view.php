<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: categories.php
 *  Path: application/views/pages/static/contact.php
 *  Description: It's a contact us details page.
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
        <!-- Block: Add Contact Us Summery -->
        <?php $this->load->view('pages/about_us/templates/summery'); ?>

        <!-- Block: Trending Groups List -->
        <?php $this->load->view('pages/widget/group/trending'); ?>
    </div>
</main>