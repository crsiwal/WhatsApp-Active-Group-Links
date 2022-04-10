<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: submit.php
 *  Path: apps/views/popup/groups/submit.php
 *  Description: 
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         16/06/2021              Created
 *  
 */
?>
<div id="group_submit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Share Your Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="access_addform" method="post" action="#" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-9 col">
                            <div class="form-label h5">Group Category</div>
                            <div id="group_category_name" class="form-label font-12">---</div>
                        </div>
                        <div class="col-9 col mt-3">
                            <div class="form-label h5">Sub Category</div>
                            <div class="form-label font-12">Define your Group best match sub category.</div>
                            <select id="group_subcategory" name="group" class="form-control form-field mt-2">
                                <option value="0" selected="selected">Select Sub Category</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cirbtn btn-lg" data-dismiss="modal">Skip Share</button>
                <button type="button" id="submit_group_btn" class="btn wpbtn slbtn btn-lg">Share Group</button>
            </div>
        </div>
    </div>
</div>