<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        next_page("/login");
    }

    public function login() {
        if (is_login()) {
            next_page("admin/");
        } else {
            $this->load->library(["Google"]);
            $data = array(
                'popup' => array('login'),
                'loginurl' => admin_url("authentication/loginrequest", true),
                'g_login_url' => $this->google->get_login_url(),
                'error' => $this->sessions->get_error()
            );
            $this->template->addMeta('title', 'Login In');
            $this->template->addMeta('description', 'Login to your account.');
            $this->template->show('admin/accounts/login', $data, 'login');
        }
    }

    public function loginRequest() {
        $redirect_url = "login";
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation', NULL, 'form');
        $this->form->set_rules('username', 'Username', 'trim|required|min_length[1]|max_length[128]');
        $this->form->set_rules('password', 'Password', 'trim|required|min_length[1]|max_length[128]');
        if ($this->form->run() != FALSE) {
            $username = $this->input->post('username', true);
            $password = $this->input->post('password', true);
            if ($this->user->log_in_user($username, $password) === true) {
                $redirect_url = "admin";
            } else {
                $this->sessions->set_error("Invalid username or password");
            }
        } else {
            $this->sessions->set_error(reset($this->form->error_array()));
        }
        next_page($redirect_url);
    }

    public function logoutRequest() {
        logout();
    }

    public function loginGoogle() {
        $success = FALSE;
        if (!empty($this->input->get('code', TRUE))) {
            $this->load->library('Google');
            $auth = $this->google->get_access_token_auth(TRUE);
            if ($auth) {
                $g_user = $this->google->get_user_details($auth['access_token']);
                if ($g_user && isset($g_user["email"])) {
                    $user = email_exists($g_user["email"]);
                    $success = TRUE;
                    if ($user && isset($user->id)) {
                        $this->user->update_user_access_token($auth, $user->id);
                        $this->user->log_in_this_user($user);
                    } else {
                        if (isset($auth["refresh_token"])) {
                            $user_id = $this->user->add_new_user($g_user);
                            if ($user_id) {
                                $this->user->add_user_access_token($user_id, $auth);
                                $user = $this->user->get_user_by_id($user_id);
                                $this->user->log_in_this_user($user);
                                next_page("admin");
                            }
                        } else {
                            next_page($this->google->get_signup_url(), FALSE);
                        }
                    }
                }
            }
        }
        next_page((!$success) ? "login" : "admin");
    }

    public function signupGoogle() {
        $success = FALSE;
        if (!empty($this->input->get('code', TRUE))) {
            $this->load->library('Google');
            $auth = $this->google->get_access_token_auth();
            if ($auth) {
                $g_user = $this->google->get_user_details($auth['access_token']);
                if ($g_user && isset($g_user["email"])) {
                    $user = email_exists($g_user["email"]);
                    $success = TRUE;
                    if ($user && isset($user->id)) {
                        $this->user->update_user_access_token($auth, $user->id);
                        $this->user->log_in_this_user($user);
                    } else {
                        $user_id = $this->user->add_new_user($g_user);
                        if ($user_id) {
                            $this->user->add_user_access_token($user_id, $auth);
                            $user = $this->user->get_user_by_id($user_id);
                            $this->user->log_in_this_user($user);
                            next_page("admin");
                        }
                    }
                }
            }
        }
        next_page((!$success) ? "login" : "/");
    }
}
