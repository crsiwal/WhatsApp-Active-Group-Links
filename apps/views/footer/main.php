<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: main.php
 *  Path: apps/views/footer/main.php
 *  Description: Default html footer page of dashboard.
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         17/04/2019              Created
 *
 */
?>
<footer>
    <?php
    if (is_mobile()) {
        $this->load->view('footer/templates/default_mobile');
    } else {
        $this->load->view('footer/templates/default_desktop');
    }
    ?>
</footer>