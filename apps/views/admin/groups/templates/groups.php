<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ************************************************************
 *  File: groups.php
 *  Path: application/views/admin/groups/templates/groups.php
 *  Description: Groups list of specific category will be shown here
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         04/03/2022              Created
 *
 */
?>
<div class="mt-3 collist-container">
    <div class="input-group mb-3">
        <input id="search_groups" type="text" class="form-control" placeholder="Type group name or key..." aria-label="ype group name or key..." aria-describedby="basic-addon1">
    </div>
    <ul id="groupslist" class="list-group list-group-flush ldgrpdtl collist blank"></ul>
</div>