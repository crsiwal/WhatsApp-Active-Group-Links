<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: group_link_from_url.php
 *  Path: application/views/tools/group_from_url/group_link_from_url.php
 *  Description: Html Form For get Data
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         08/07/2021              Created
 *
 */
?>
<main class="main">
    <div class="container-fluid">
        <div class="row pt-4 pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-12 m-auto">
                        <h2 class="mt-4 mb-3 ttlbefore">Groups From Urls</h2>
                        <h5 class="text-center">Will extract Groups Url from existing website pages.</h5>
                        <h6 class="text-center text-danger"><?= isset($error) ? $error : ""; ?></h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-4 pb-4">
            <div class="col-11 pt-3 pb-3">
                <form action="<?= url("tools/urlgroups"); ?>" method="post" accept-charset="utf-8">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <select name="category" id="group_category_target" data-subcategory="group_subcategory" class="custom-select pointer slinp slsel">
                                <option disabled selected>Select Category</option>
                                <?php
                                if (isset($select) && is_array($select)) {
                                    foreach ($select as $option) {
                                        echo '<option value="' . $option->id . '">' . $option->name . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <select name="subcategory" id="group_subcategory" class="custom-select pointer slinp rounded-0">
                                <option disabled selected>Select Sub Category</option>
                            </select>
                        </div>
                        <input name="websitelink" id="website_link" type="text" class="form-control slinp sltxt" placeholder="Website page link ..." aria-label="Website page link ..." aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button id="get_groups_btn" class="btn wpbtn slinp slbtn" type="submit">Get Groups</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>