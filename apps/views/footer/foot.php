<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: foot.php
 *  Path: application/views/footer/foot.php
 *  Description: This is a foot of the site.
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         30/05/2021              Created
 *
 */
$js = isset($js) ? $js : array();
$popup = isset($popup) ? $popup : array();

foreach ($js as $jsId => $jsPath) {
    echo "<script id='$jsId' type='text/javascript' src='$jsPath'></script>\n";
}

foreach ($popup as $filePath) {
    include_once($filePath);
}
?>
<div id="notice_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<form id="blankform"></form>
</body>

</html>