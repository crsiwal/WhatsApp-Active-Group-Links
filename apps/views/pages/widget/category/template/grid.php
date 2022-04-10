<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ************************************************************
 *  File: grid.php
 *  Path: apps/views/blocks/category/template/grid.php
 *  Description: It's a categories grid layout.
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         30/05/2021              Created
 *
 */
$childs = isset($childs) ? $childs : false;
if (is_mobile()) {
    $colview = isset($colview) ? $colview : "";
?>
    <div class="col-6 <?= $colview; ?>">
        <div class="catcard">
            <a href="<?= ($childs) ? $url : $gurl; ?>">
                <div class="thumbnail" style="background: url(<?= $icon; ?>);">
                    <div class="catcardtitle">
                        <div class="title"><?= $name; ?></div>
                        <div class="summery"><?= $groups; ?> Groups</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
<?php
} else {
?>
    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 col-6 col-6 pr-1 pl-0">
        <div class="catcard">
            <a href="<?= ($childs) ? $url : $gurl; ?>">
                <div class="thumbnail" style="background: url(<?= $icon; ?>);">
                    <div class="catcardtitle">
                        <div class="title"><?= $name; ?></div>
                        <div class="summery"><?= $groups; ?> Groups</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
<?php
}
?>