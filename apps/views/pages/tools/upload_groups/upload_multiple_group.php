<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: upload_multiple_group.php
 *  Path: application/views/tools/upload_groups/upload_multiple_group.php
 *  Description: Html Form For get multiple groups links line by line
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
                        <h2 class="mt-4 mb-3 ttlbefore">Upload WhatsApp Groups</h2>
                        <h5 class="text-center">Uplaod multiple WhatsApp group links line by line.</h5>
                        <h6 class="text-center text-danger"><?= isset($error) ? $error : ""; ?></h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-1 pb-4">
            <div class="col-11 pt-3 pb-3">
                <form action="<?= url("tools/multigroups"); ?>" method="post" accept-charset="utf-8">
                    <div class="input-group mb-3">
                        <textarea rows="5" name="groups" id="groups_link" type="text" class="form-control slinp" placeholder="WhatsApp Groups Link line by line..." aria-label="WhatsApp Groups Link line by line" aria-describedby="basic-addon2"></textarea>
                    </div>
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
                        <input name="tags" id="group_tags" maxlength="100" type="text" class="form-control slinp sltxt" placeholder="Enter Tags by Comma (,) Separated (Optional) " aria-label="Enter Tags by Comma (,) Separated (Optional) " aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button id="get_groups_btn" class="btn wpbtn slinp slbtn" type="submit">Uplaod Groups</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>