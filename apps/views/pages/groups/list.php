<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: groups.php
 *  Path: application/views/pages/category/groups.php
 *  Description: It's a listing of groups of this category in website.
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         05/06/2021              Created
 * 
 */
?>
<main class="main">
    <div class="container-fluid">
        <!-- Block: Group Summery -->
        <?php
        if (is_mobile()) {
            $this->load->view('pages/groups/templates/summery_xs');
        } else {
            $this->load->view('pages/groups/templates/summery_md');
        }
        ?>
        <!-- Block: Groups List -->
        <div class="row pb-5">
            <div class="container">
                <div class="row">
                    <?php
                    if (is_array($groups) && count($groups) > 0) {
                        foreach ($groups as $group) {
                            $this->load->view('pages/widget/group/template/grid', $group);
                        }
                    } else {
                    ?>
                        <div class="col-12 m-auto pt-4">
                            <h5 class="text-center">This category don't have any group yet. Be the first one to submit your group of this category.</h5>
                            <p class="text-center pt-5">
                                <?php $this->load->view('pages/groups/templates/submit_button'); ?>
                            </p>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Block: Next Groups List Button -->
        <?php $this->load->view('pages/groups/templates/next_groups_link'); ?>

        <!-- Block: Trending Groups List -->
        <?php $this->load->view('pages/widget/group/trending'); ?>
    </div>
</main>