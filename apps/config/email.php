<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: email.php
 *  Path: application/config/email.php
 *  Description: These are email configurations which used for send email.
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         09/05/2019              Created
 */
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.gmail.com';
$config['smtp_port'] = 587;
$config['smtp_user'] = 'xxx.xxx.xxx';
$config['smtp_pass'] = 'xxx.xxx.xxx';
$config['smtp_crypto'] = 'tls';
$config['mailtype'] = 'html';
$config['smtp_timeout'] = '10';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['wordwrap'] = TRUE;