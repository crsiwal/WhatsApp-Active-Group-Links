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
        <div class="row mb-3">
            <div class="col col-3">
                <!-- Section: Categories List -->
                <?php $this->load->view('admin/category/templates/categories'); ?>
            </div>
            <div class="col col-3">
                <!-- Section: Sub Categories List -->
                <?php $this->load->view('admin/category/templates/sub_categories'); ?>
            </div>
            <div class="col col-6">
                <!-- Section: Category Details -->
                <?php $this->load->view('admin/category/templates/details'); ?>
                <!-- Section: Category Update -->
                <?php $this->load->view('admin/category/templates/update'); ?>
            </div>
        </div>
    </div>
</main>