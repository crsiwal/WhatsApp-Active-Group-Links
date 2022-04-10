<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Google.php
 *  Path: application/libraries/Google.php
 *  Description: 
 *  This library used for handle google product access request
 * 
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         31/01/2020              Created
 *
 */
if (!class_exists('Google')) {

    class Google {

        private $ci;
        private $client_id;
        private $client_secret;
        private $signup_redirect;
        private $login_redirect;

        function __construct() {
            $this->ci = & get_instance();
            $this->client_id = $this->ci->config->item('g-client');
            $this->client_secret = $this->ci->config->item('g-secret');
            $this->signup_redirect = url("signup/google", true);
            $this->login_redirect = url("login/google", true);
        }

        public function get_signup_url() {
            $params = array(
                "scope" => $this->ci->config->item('g-grantaccess'),
                "access_type" => "offline",
                "approval_prompt" => "force", /** This will force user to get access token again * */
                "include_granted_scopes " => true,
                "redirect_uri" => $this->signup_redirect,
                "response_type" => "code",
                "client_id" => $this->client_id,
            );
            return $this->ci->config->item('g-authaccess') . '?' . http_build_query($params);
        }

        public function get_login_url() {
            $params = array(
                "scope" => $this->ci->config->item('g-grantaccess'),
                "access_type" => "offline",
                "include_granted_scopes " => true,
                "redirect_uri" => $this->login_redirect,
                "response_type" => "code",
                "client_id" => $this->client_id,
            );
            return $this->ci->config->item('g-authaccess') . '?' . http_build_query($params);
        }

        public function get_user_access_token($user_id = 0) {
            $token = $this->ci->user->get_access_token($user_id);
            if (isset($token->access_token) && $this->is_token_valid($token->access_token)) {
                return $token->access_token;
            } else {
                $access_token = $this->refresh_access_token($token->refresh_token);
                if ($access_token) {
                    $auth = array(
                        "access_token" => $access_token
                    );
                    $this->ci->user->update_user_access_token($auth, $user_id);
                    return $access_token;
                }
            }
            return false;
        }

        /**
         * This return the Access Token with Refresh Token Key
         */
        public function get_access_token_auth($is_login = FALSE) {
            $request = array(
                "url" => $this->ci->config->item('g-authtoken'),
                "fields" => array(
                    "code" => $this->ci->input->get('code', TRUE),
                    "client_id" => $this->client_id,
                    "client_secret" => $this->client_secret,
                    "redirect_uri" => ($is_login) ? $this->login_redirect : $this->signup_redirect,
                    "grant_type" => "authorization_code"
                )
            );
            $response = $this->ci->curl->post($request);
            return (isset($response["access_token"])) ? $response : FALSE;
        }

        public function refresh_access_token($refresh_token) {
            $request = array(
                "url" => $this->ci->config->item('g-oauth'),
                "fields" => array(
                    "refresh_token" => $refresh_token,
                    "client_id" => $this->client_id,
                    "client_secret" => $this->client_secret,
                    "grant_type" => "refresh_token"
                )
            );
            $response = $this->ci->curl->post($request, [], TRUE, 'app_json');
            return (isset($response["access_token"])) ? $response["access_token"] : FALSE;
        }

        public function is_token_valid($access_token) {
            $request = array(
                "url" => $this->ci->config->item('g-infotoken'),
                "fields" => array(
                    "access_token" => $access_token
                )
            );
            $response = $this->ci->curl->get($request);
            return (is_array($response) && isset($response['user_id']) && isset($response['expires_in']) && $response['expires_in'] > 20) ? TRUE : FALSE;
        }

        public function get_user_details($access_token) {
            $params = array(
                "access_token" => $access_token
            );
            $request = array(
                "url" => $this->ci->config->item('g-infouser'),
                "fields" => $params
            );
            $response = $this->ci->curl->get($request);
            return (is_array($response) && isset($response['id'])) ? array(
                "gid" => $response['id'],
                "first_name" => isset($response['given_name']) ? $response['given_name'] : "",
                "middle_name" => isset($response['middle_name']) ? $response['middle_name'] : "",
                "last_name" => isset($response['family_name']) ? $response['family_name'] : "",
                "display_name" => isset($response['name']) ? $response['name'] : "",
                "email" => $response['email'],
                "password" => unique_key(),
                "email_verify" => $response['verified_email'],
                "picture" => $response['picture']) : FALSE;
        }

    }

}