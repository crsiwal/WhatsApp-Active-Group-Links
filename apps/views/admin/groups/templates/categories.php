<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ************************************************************
 *  File: summery.php
 *  Path: application/views/admin/category/templates/categories.php
 *  Description: All the categories will be shown here
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
        <input id="search_category" type="text" class="form-control" placeholder="Search Categories..." aria-label="Search Categories..." aria-describedby="basic-addon1">
    </div>
    <ul class="list-group list-group-flush ldgrplist collist">
        <?php
        foreach ($categories as $category) {
            $cssclass = ($category->enb == 1) ? "catenable" : "catdisable";
        ?>
            <li id="ctlist_<?= $category->id; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center pointer <?= $cssclass; ?>" data-cid="<?= $category->id; ?>">
                <span class="ellipsis pr-4"><?= $category->name; ?></span>
                <?php
                if (!empty($category->icon)) {
                ?>
                    <img class="img-fluid" src="<?= $category->icon; ?>" />
                <?php
                }
                ?>
            </li>
        <?php
        }
        ?>
    </ul>
</div>