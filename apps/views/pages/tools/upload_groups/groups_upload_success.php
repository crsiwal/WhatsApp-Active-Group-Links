<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: group_uploaded_from_url.php
 *  Path: application/views/tools/group_from_url/group_uploaded_from_url.php
 *  Description: List Of Groups Added to system in Store file.
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         08/07/2021              Created
 *
 */
?>
<main class="main">
    <div class="container-fluid">
        <div class="row pt-4 pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-12 m-auto">
                        <h2 class="mt-4 mb-3 ttlbefore">Groups Uplaoded from Url</h2>
                        <h5 class="text-center"><?= isset($count) ? $count : 0; ?> Groups Uploaded from website page Url.</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-4 pb-4">
            <div class="col-11 pt-3 pb-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Group URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($groups as $i => $group) {
                        ?>
                            <tr>
                                <th scope="row"><?= ($i + 1); ?></th>
                                <td><?= $group; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>