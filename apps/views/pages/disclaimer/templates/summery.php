<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ************************************************************
 *  File: summery.php
 *  Path: application/views/blocks/contact/summery.php
 *  Description: It's a homepage Text box for add group.
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         30/05/2021              Created
 *
 */
$domain_name = $this->config->item("domain_name");
?>
<div class="row pt-4 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-12 m-auto">
                <div>
                    <h1>Disclaimer</h1>
                </div>
                <p><strong>We are not associated with WhatsApp messenger by any means. WhatsApp is a registered trademark of WhatsApp Inc.</strong></p>
                <div class="mt-4">
                    <dl>
                        <p>Your shared WhatsApp group link will be shared as public by website.</p>
                        <p>This website is a free platform to share group links only. We are not affiliated with WhatsApp or any of its brands.</p>
                        <p>All logos, product names, brands are property of their respective owners.</p>
                        <p>WhatsApp&trade; is a trademark of WhatsApp Inc.</p>
                        <p>Facebook&trade; is a trademark of Facebook Inc.</p>
                        <p><?= $domain_name; ?> is not affiliated, sponsored, or endorsed by, WhatsApp Inc. or Facebook Inc.</p>
                        <p>We do not have any direct collaboration with Whatsapp or any other social network.</p>
                        <p>This website is developed for connecting people all over the world via public WhatsApp groups.</p>
                        <p>The information contained within this website is strictly for educational purposes.</p>
                        <p>If you wish to apply the ideas contained on this website, you are taking full responsibility for your actions.</p>
                        <p>Please report any violations of these <a href="<?= url("terms"); ?>"><strong><u>Term Of Service</u></strong></a> by using our &nbsp;<a href="<?= url("contact"); ?>"><strong><u>Contact Us</u></strong></a> form.</p>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>