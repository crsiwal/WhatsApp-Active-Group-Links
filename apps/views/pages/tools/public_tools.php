<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: public_tools.php
 *  Path: application/views/tools/public_tools.php
 *  Description: List of tools available publickly
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         25/02/2022              Created
 *
 */
?>
<main class="main">
    <div class="container-fluid">
        <div class="row pt-4 pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-12 m-auto">
                        <h2 class="mt-4 mb-3 ttlbefore">Public Tools for Manage Groups</h2>
                        <h5 class="text-center">Anyone can use these tools for specific purpose on this website.</h5>
                        <h6 class="text-center text-danger"><?= isset($error) ? $error : ""; ?></h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-1 pb-4">
            <div class="col-11 pt-3 pb-3">

            </div>
        </div>
    </div>
</main>