<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ************************************************************
 *  File: summery.php
 *  Path: application/views/admin/category/templates/update.php
 *  Description: HTML form for update the category
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         28/02/2022              Created
 *
 */
?>
<div id="catrupdatesection" class="mt-3 hidden">
    <form id="catrupdate">
        <div class="form-row">
            <div class="col-12 mb-3">
                <label for="catname">Name</label>
                <input type="text" class="form-control" id="catname" name="name" placeholder="Category Name" value="" required>
            </div>
            <div class="col-12 mb-3">
                <label for="catslug">Slug</label>
                <input type="text" class="form-control" id="catslug" name="slug" placeholder="Category Slug" value="" required>
            </div>
            <div class="col-12 mb-3">
                <label for="catslug">Banner URL</label>
                <div class="d-flex justify-content-between">
                    <div class="container-fluid p-0">
                        <input type="text" class="form-control" id="catbanner" name="banner" placeholder="Category Banner URL" value="" required>
                        <a href="#" target="_blank" class="btn btn-link p-0 font-12" id="catbannerurl"></a>
                    </div>
                    <div class="pl-2">
                        <input type="file" class="hidden imguploads" id="caticonfile" data-callback="admin_after_upload_category_banner">
                        <label class="fa fa-cloud-upload icloud-upload pointer" for="caticonfile"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div id="caticonurlimg" class="col-md-6 mb-3 ratio ratio-16x9">
                <input type="hidden" id="caticonurl">
                <input type="hidden" name="_ctid" id="_ctrid">
            </div>
            <div class="col-md-6">
                <div class="col-12 mb-3">
                    <select id="catparent" name="parentid" class="custom-select">
                        <option value="0" selected>Select Parent Category</option>
                        <?php
                        foreach ($categories as $category) {
                        ?>
                            <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="col-12 mb-3">
                    <label class="bold" for="catviewstate">View State</label>
                    <input name="viewstate" id="catviewstate" type="range" class="custom-range" min="0" max="4" value="1">
                    <div class="row m-0">
                        <div class="col-3 p-0">Top</div>
                        <div class="col-3 p-0">High</div>
                        <div class="col-3 p-0">Middle</div>
                        <div class="col-2 p-0">Low</div>
                        <div class="col-1 p-0 text-right">Lowest</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-6 mb-3">
                <label class="bold mb-3">Is this category active?</label>
                <div class="custom-control custom-switch custom-switch-lg">
                    <input type="checkbox" name="status" class="custom-control-input" id="catrstatus">
                    <label class="custom-control-label pointer" for="catrstatus"></label>
                </div>
            </div>
            <div class="col-6 pt-4">
                <button id="ctr_upd_btn" class="btn wpbtn slbtn mt-3 btn-lg pl-4 pr-4" type="button">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                    <span>Save</span>
                </button>
            </div>
        </div>
    </form>
</div>