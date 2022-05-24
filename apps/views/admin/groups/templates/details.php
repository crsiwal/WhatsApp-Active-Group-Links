<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ************************************************************
 *  File: summery.php
 *  Path: application/views/admin/category/templates/details.php
 *  Description: All the details of category will be shown here
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         28/02/2022              Created
 *
 */
?>
<div id="catrdetailsection" class="mt-3 hidden position-relative">
    <div class="row">
        <div class="col-12">
            <div id="textimgsrc" class="ctrbnrimg ratio ratio-16x9"></div>
        </div>
    </div>
    <div class="row catrinfo position-absolute fixed-top ratio ratio-16x9 m-0">
        <div class="col-12">
            <div class="position-absolute text-right fixed-top mt-2 mr-2">
                <div id="editcatr" class="d-block pointer">
                    <span class="font-14">Edit</span>
                    <i class="font-12 fa fa-pencil-square-o" aria-hidden="true"></i>
                </div>
                <a id="textbannerurl" class="d-block" href="#" target="_blank">
                    <span class="font-14">Banner</span>
                    <i class="font-12 fa fa-external-link" aria-hidden="true"></i>
                </a>
                <a id="textcategoryurl" class="d-block" href="#" target="_blank">
                    <span class="font-14">Category</span>
                    <i class="font-12 fa fa-external-link" aria-hidden="true"></i>
                </a>
                <a id="textgroupsurl" class="d-block" href="#" target="_blank">
                    <span class="font-14">Groups</span>
                    <i class="font-12 fa fa-external-link" aria-hidden="true"></i>
                </a>
            </div>
            <div class="position-absolute fixed-bottom pl-3 pr-3">
                <div class="ctrtxttitle" id="textcatname"></div>
                <div>
                    <span class="ctrtxtslug" id="textcatslug"></span>
                    <span class="font-14">(<span class="ctrtxtslug bold font-14" id="textcatgroups">0</span>)</span>
                </div>
                <div class="d-flex justify-content-between mt-3 mb-3 font-20">
                    <div>
                        <i class="fa fa-tags" aria-hidden="true"></i> <span id="textparentcategory"></span>
                    </div>
                    <div>
                        <i class="fa fa-filter" aria-hidden="true"></i> <span id="textviewstate"></span> View
                    </div>
                    <div>
                        <i class="fa fa-check" aria-hidden="true"></i> <span id="textisactive"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>