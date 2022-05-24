<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: user_email.php
 *  Path: application/config/user_email.php
 *  Description: This is a list of user email id's which will be used to send email.
 * 
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         18/01/2020              Created
 */
$config['default_bcc'] = array();
$config['emails'] = array(
    "sales" => array("name" => "Sales", "email" => "rahulsiwal62@gmail.com"),
    "support" => array("name" => "Support", "email" => "rahulsiwal62@gmail.com"),
    "no-reply" => array("name" => "No Reply", "email" => "rahulsiwal62@gmail.com"),
);
switch (ENVIRONMENT) {
    case 'production':
        $config['emails']["sales"]["email"] = "rahulsiwal62@gmail.com";
        /** Default BCC Email list * */
        array_push($config['default_bcc'], $config['emails']['rahul']['email']);
        break;
    case 'testing':
        foreach ($config['emails'] as $object => $email) {
            if (!in_array($object, array("support", "no-reply"))) {
                $config['emails'][$object]["email"] = $config["emails"]["rahul"]["email"];
            }
        }
        array_push($config['default_bcc'], $config['emails']['rahul']['email']);
        break;
    case 'development':
        foreach ($config['emails'] as $object => $email) {
            if (!in_array($object, array("support", "no-reply"))) {
                $config['emails'][$object]["email"] = $config["emails"]["rahul"]["email"];
            }
        }
        array_push($config['default_bcc'], $config['emails']['rahul']['email']);
        break;
}