<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ************************************************************
 *  File: add_group.php
 *  Path: application/views/dashboard/blocks/homepage/add_group.php
 *  Description: It's a homepage Text box for add group.
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         30/05/2021              Created
 *
 */

if (is_mobile()) {
?>
    <div class="row bg-wpl">
        <div class="col-12">
            <h1 class="mt-4 mb-4 h2">Are you searching for WhatsApp Groups?</h1>
            <p>Millions of users share there active WhatsApp groups. Also, you can join or share any WhatsApp group. Groups are categorized for you to join easily.</p>
            <div class="input-group input-group-sm mb-3">
                <select id="share_group_category" class="custom-select pointer slinp slsel">
                    <option disabled selected>Select Category</option>
                    <?php
                    if (isset($select) && is_array($select)) {
                        foreach ($select as $option) {
                            echo '<option value="' . $option->id . '">' . $option->name . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="input-group input-group-sm mb-3">
                <input id="share_invite_link" type="text" class="form-control slinp sltxt" placeholder="Share your WhatsApp group join link..." aria-label="Whatsapp Group invite link" aria-describedby="basic-addon2">
            </div>
            <div class="input-group d-flex justify-content-center mb-3">
                <button id="share_group_btn" class="btn btn-success" type="button">Share Your Group</button>
            </div>
        </div>
    </div>
<?php
} else {
?>
    <div class="row bg-wpl pt-5 pb-5">
        <div class="container pt-5 pb-5">
            <div class="row p-2">
                <div class="col-12 pt-4 pb-4">
                    <h1 class="mt-4 mb-3">Are you searching for WhatsApp Groups?</h1>
                    <h5>Millions of users share there active WhatsApp groups. Also, you can join or share any WhatsApp group. Groups are categorized for you to join easily.</h5>
                </div>
                <div class="col-lg-10 col-md-8 col-sm-11 col-xs-11 col-12 pt-3 pb-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <select id="share_group_category" class="custom-select pointer slinp slsel">
                                <option disabled selected>Select Category</option>
                                <?php
                                if (isset($select) && is_array($select)) {
                                    foreach ($select as $option) {
                                        echo '<option value="' . $option->id . '">' . $option->name . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <input id="share_invite_link" type="text" class="form-control slinp sltxt" placeholder="Share your WhatsApp group join link..." aria-label="Whatsapp Group invite link" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button id="share_group_btn" class="btn wpbtn slinp slbtn pr-4 pl-3" type="button">Share Your Group</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>