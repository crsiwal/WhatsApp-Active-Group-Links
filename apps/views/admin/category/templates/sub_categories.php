<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ************************************************************
 *  File: sub_categories.php
 *  Path: application/views/admin/category/templates/sub_categories.php
 *  Description: All sub-categories of specific category will be shown here
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         28/02/2022              Created
 *
 */
?>
<div class="mt-3 collist-container">
    <div class="input-group mb-3">
        <input id="search_subctr" type="text" class="form-control" placeholder="Search Sub Category..." aria-label="Search Sub Category..." aria-describedby="basic-addon1">
    </div>
    <ul id="subctrlist" class="list-group list-group-flush ldcatlist collist blank"></ul>
</div>