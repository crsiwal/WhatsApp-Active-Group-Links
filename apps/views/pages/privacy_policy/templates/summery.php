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
                    <h1>Privacy Policy</h1>
                </div>
                <p><strong>We are not associated with WhatsApp messenger by any means. WhatsApp is a registered trademark of WhatsApp Inc.</strong></p>
                <div>
                    <dl>
                        <dt><label>Collection of Information</label></dt>
                        <dd><strong><?= $domain_name; ?></strong> collects information from you to provide you with a customized experience.&nbsp;<strong><?= $domain_name; ?></strong> requires you to provide information such as name, and email address at the time of registration. We automatically collect certain non-personal information based on your behavior on our website. This information may include the URL that you come from, what browser you are using, and other such information.&nbsp;<strong><?= $domain_name; ?></strong> also collects your IP address, this information is required so that&nbsp;<strong><?= $domain_name; ?></strong> may better understand its users.</dd>

                        <dt><label>Use of Information</label></dt>
                        <dd>We will not sell or rent your information to third parties for marketing purposes and will only disclose your information in accordance with our Privacy Policy and/or with your permission. If you engage in illegal activity while using&nbsp;<strong><?= $domain_name; ?></strong> or any of its services, we may provide your personal information to authorities in an effort to enforce our terms and conditions.</dd>

                        <dt><label>Cookies</label></dt>
                        <dd>&quot;Cookies&quot; are small files placed on your hard drive that assist us in providing customized services.&nbsp;<strong><?= $domain_name; ?></strong> uses cookies to gather information. Cookies can also help us provide information that is targeted to your interests. You are always free to decline our cookies if your browser permits, although in that case you may not be able to use certain features on our website. We do not control the use of cookies by third parties.</dd>

                        <dt><label>Legal Disclaimer</label></dt>
                        <dd>We reserve the right to disclose your personally identifiable information as required by law and when we believe that disclosure is necessary to protect our rights and/or to comply with a judicial proceeding, court order, or legal process served on our Web site.</dd>

                        <dt><label>Changes to our Privacy Policy</label></dt>
                        <dd><strong><?= $domain_name; ?></strong> will occasionally update this privacy statement. The current version will always be posted here.</dd>
                        <dd>Please report any violations of these <a href="<?= url("terms"); ?>"><strong><u>Term Of Service</u></strong></a> by using our &nbsp;<a href="<?= url("contact"); ?>"><strong><u>Contact Us</u></strong></a> form.</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>