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
        <!-- Section: Group Join Details -->
        <?php $this->load->view('pages/groups/templates/single_group', $group); ?>

        <!-- Section: Advertisement -->
        <?php $this->load->view("pages/widget/ads/google/text_picture"); ?>

        <!-- Section: Trending Groups List -->
        <?php $this->load->view('pages/widget/group/trending'); ?>
    </div>
</main>