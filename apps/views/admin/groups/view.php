<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: view.php
 *  Path: application/views/admin/groups/view.php
 *  Description: It's a groups list manage view
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         04/03/2022              Created
 *
 */
?>
<main class="main">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col col-3">
                <!-- Section: Categories List -->
                <?php $this->load->view('admin/groups/templates/categories'); ?>
            </div>
            <div class="col col-3">
                <!-- Section: Ajax Load Groups List -->
                <?php $this->load->view('admin/groups/templates/groups'); ?>
            </div>
            <div class="col col-6">
                <!-- Section: Group Details -->
                <?php $this->load->view('admin/groups/templates/details'); ?>
                <!-- Section: Group Update -->
                <?php $this->load->view('admin/groups/templates/update'); ?>
            </div>
        </div>
    </div>
</main>