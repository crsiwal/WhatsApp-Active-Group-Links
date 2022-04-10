<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: login.php
 *  Path: application/views/dashboard/user/login.php
 *  Description: It's a login page of dashboard.
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         26/06/2021              Created
 *
 */
?>
<main class="login container-fluid bg-wpl">
    <div class="row pt-5 pb-5">
        <div class="col col-12 pb-5">
            <div class="login-form-container rounded">
                <form class="custom-login-form" action="<?php echo $loginurl; ?>" method="post">
                    <div class="loginform input-field-container-outer">
                        <div class="input-field-container">
                            <input type="text" name="username" id="username" class="form-control input-field" required placeholder="Email address or username" aria-label="Email id or Username" aria-describedby="basic-addon1" />
                            <div class="image-container input-field-icon">
                                <img src="<?php icon_url('username.png'); ?>" alt="" />
                            </div>
                        </div>
                        <div class="input-field-container mt-3">
                            <input type="password" name="password" class="form-control input-field" required placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" />
                            <div class="image-container input-field-icon">
                                <img src="<?php icon_url('password.png'); ?>" alt="" />
                            </div>
                        </div>
                    </div>
                    <?php
                    if (!empty($error)) {
                    ?>
                        <div class="errnotice text-danger mt-1"><?= $error; ?></div>
                    <?php
                    }
                    ?>
                    <div class="input-field-container-outer full">
                        <p class="font-12">By continuing, you agree to our <a class="btn-link" href="<?= url("terms"); ?>">Terms of Service</a></p>
                    </div>
                    <div class="button-container mt-2 full" id="sign-in-button">
                        <button type="submit" class="btn btn-lg pt-2 pb-2 btn-primary full">Log In</button>
                    </div>
                    <div class="full my-3 divider text-center position-relative">
                        <span class="font-18 hr-center">OR</span>
                    </div>
                    <div class="full">
                        <div class="google">
                            <a class="btn btn-outline-secondary full font-16 pt-2 pb-2" href="<?= $g_login_url; ?>">
                                <i class="fa fa-google font-20" aria-hidden="true"></i>
                                <span>Continue with Google</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>