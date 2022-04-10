<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Emailer.php
 *  Path: application/libraries/Emailer.php
 *  Description: 
 *  This library used for send Emails with multiple formats
 * 
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         09/05/2019              Created
 *
 */
if (!class_exists('Emailer')) {

    class Emailer {

        private $ci;

        function __construct() {
            $this->ci = & get_instance();
        }

        /**
         * This function is used for send simple email. 
         * Example
         * $this->simple(
         *              array(
         *                  "to"        => "abc@example.com", // Comma seperated string or array of emails
         *                  "cc"        => array("abc@example.com","def@example.com",),   // Comma seperated string or array of emails
         *                  "bcc"       => "abc@example.com",   // Comma seperated string or array of emails
         *                  "subject"   => "Subject title",
         *                  "message"   => "Custom message entered"
         *  ));
         * 
         * @param type $data
         * @return boolean
         */
        public function simple($data = array()) {
            $subject = isset($data['subject']) ? $data['subject'] : "AdGyde Notification";
            $message = isset($data['message']) ? $data['message'] : "";
            $email = $this->prepairEmails($data);
            if ($email != FALSE) {
                return $this->prepairMail($email, $subject, $message, $data);
            }
            return FALSE;
        }

        /**
         * This function is used for send html email to user.
         * This can be used in following two ways
         * 1. Only Pass message and it will automatically set the default header and footer and send the email
         * Example
         * $this->html(
         *      array(
         *          "to"        => "abc@example.com", // Comma separated string or array of email
         *          "cc"        => array("abc@example.com","def@example.com",),   // Comma separated string or array of email
         *          "bcc"       => "abc@example.com",   // Comma separated string or array of email
         *          "subject"   => "Subject title",
         *          "header"    => "user_email",
         *          "footer"    => "user_email",
         *          "message"   => "Custom message entered"
         *          "data"       => array("var a" => "val a"),
         *          "csv"       => array(
         *                          "filenameA" => array(
         *                                          array("col A, row 1","col B, row 1","col C, row 1",),
         *                                          array("col A, row 2","col B, row 2","col C, row 2",),
         *                                      ),
         *                          "filenameB" => array(
         *                                          array("col A, row 1","col B, row 1","col C, row 1",),
         *                                          array("col A, row 2","col B, row 2","col C, row 2",),
         *                                      ),
         *                          ),
         *  ));
         * 
         * 2. Specify the HTML Template located in "application/views/mailer/template/template-name.php"
         * $this->html(
         *      array(
         *          "to"        => "abc@example.com", // Comma separated string or array of email
         *          "cc"        => array("abc@example.com","def@example.com",),   // Comma separated string or array of email
         *          "bcc"       => "abc@example.com",   // Comma separated string or array of email
         *          "subject"   => "Subject title",
         *          "header"    => "user_email",
         *          "footer"    => "user_email",
         *          "template"  => "forgot_password",
         *          "data       => array("var a" => "val a"),
         * ));
         * 
         * 
         * @param type $data
         * @return boolean
         */
        public function html($data = array()) {
            $subject = isset($data['subject']) ? $data['subject'] : "AdGyde Notification";
            $values = isset($data['data']) ? $data['data'] : array();
            $email = $this->prepairEmails($data);
            $validMail = (isset($data['template']) || (isset($data['message']) && $data['message'] != "")) ? TRUE : FALSE;

            if ($email != FALSE && $validMail == TRUE) {
                $header = isset($data['header']) ? $data['header'] : "default";
                $message = $this->ci->load->view("mailer/header/" . $header, $values, true);
                if (isset($data['template'])) {
                    $message .= $this->ci->load->view("mailer/template/" . $data['template'], $values, true);
                } else {
                    $message .= $this->ci->load->view("mailer/template/view", $data, true);
                }
                $footer = isset($data['footer']) ? $data['footer'] : "default";
                $message .= $this->ci->load->view("mailer/footer/" . $footer, $values, true);
                return $this->prepairMail($email, $subject, $message, $data);
            }
            return FALSE;
        }

        /**
         * ******************************************************************************************************
         * **************************************** Private Function ********************************************
         * ******************************************************************************************************
         * * */

        /**
         * 
         * @param type $data
         * @return type
         */
        private function prepairEmails($data) {
            $to = array_unique(isset($data['to']) ? (is_array($data['to']) ? $data['to'] : explode(",", $data['to'])) : array());
            $cc = array_unique(isset($data['cc']) ? (is_array($data['cc']) ? $data['cc'] : explode(",", $data['cc'])) : array());
            $bcc = array_unique(isset($data['bcc']) ? (is_array($data['bcc']) ? $data['bcc'] : explode(",", $data['bcc'])) : array());
            return (count($to) > 0) ? (array("to" => $to, "cc" => $cc, "bcc" => $bcc)) : FALSE;
        }

        /**
         * 
         * @param type $email
         * @param type $subject
         * @param type $message
         */
        private function prepairMail($email, $subject, $message, $data) {
            $this->ci->logger->start("prepairMail");
            $to = $email['to'];
            $cc = $email['cc'];
            $bcc = $email['bcc'];
            $default_bcc = $this->ci->config->item('default_bcc');
            $bcc = array_unique(array_merge($bcc, $default_bcc));
            $attachment['csv'] = (isset($data['csv']) && is_array($data['csv']) && count($data['csv']) > 0) ? $this->getCSVFromArray($data['csv']) : array();
            $response = $this->send($to, $cc, $bcc, $subject, $message, $attachment);
            $this->deleteCsvFiles($attachment['csv']);
            $this->ci->logger->end("Send mail to " . implode(", ", $to), "prepairMail");
            return $response;
        }

        /**
         * 
         * @param type $to
         * @param type $cc
         * @param type $bcc
         * @param type $subject
         * @param type $message
         * @param type $attachment
         * @return boolean
         */
        private function send($to = array(), $cc = array(), $bcc = array(), $subject = "", $message = "", $attachment = array()) {
            $this->ci->email->clear(TRUE);
            $from = $this->ci->config->item('emails')['no-reply'];
            $this->ci->email->from($from['email'], $from['name']);
            $this->ci->email->set_newline("\r\n");
            $this->ci->email->to($to);
            $this->ci->email->cc($cc);
            $this->ci->email->bcc($bcc);
            $this->ci->email->subject($subject);
            $this->ci->email->message($message);
            if (isset($attachment['csv']) && is_array($attachment['csv']) && count($attachment['csv']) > 0) {
                foreach ($attachment['csv'] as $attach) {
                    if (isset($attach['path'])) {
                        if (isset($attach['name'])) {
                            $this->ci->email->attach($attach['path'], 'attachment', $attach['name']);
                        } else {
                            $this->ci->email->attach($attach['path']);
                        }
                    }
                }
            }
            if (!$this->ci->email->send()) {
                $this->ci->logger->error("Emailer::send - Unable to send email to " . implode(", ", $to) . " -- Error: " . print_r($this->ci->email->print_debugger(array('headers', 'subject')), TRUE));
                return FALSE;
            } else {
                $this->ci->logger->info("Emailer::send - Success email to " . implode(", ", $to));
                return TRUE;
            }
        }

        /**
         * 
         * @param type $csvFilesArray
         * @return array
         */
        private function getCSVFromArray($csvFilesArray = array()) {
            $this->ci->logger->start("getCSVFromArray");
            $csvFiles = array();
            if (is_array($csvFilesArray) && count($csvFilesArray) > 0) {
                foreach ($csvFilesArray as $fileName => $csvArray) {
                    if (is_array($csvArray) && count($csvArray) > 0) {
                        $path = sys_get_temp_dir() . "/" . uniqid(uniqid()) . ".csv";
                        array_push($csvFiles, array("name" => $fileName . ".csv", "path" => $path));
                        $handle = fopen($path, 'w');
                        foreach ($csvArray as $row) {
                            if (is_array($row) && count($row) > 0) {
                                fputcsv($handle, $row);
                            } else if (is_object($row) && count($row) > 0) {
                                fputcsv($handle, (array) $row);
                            }
                        }
                        fclose($handle);
                    }
                }
            }
            $this->ci->logger->end("Create CSV files for send via email", "getCSVFromArray");
            return $csvFiles;
        }

        /**
         * 
         * @param type $filesArray
         */
        private function deleteCsvFiles($csvfiles) {
            if (is_array($csvfiles) && count($csvfiles) > 0) {
                foreach ($csvfiles as $csvfile) {
                    if (isset($csvfile['path'])) {
                        if (!unlink($csvfile['path'])) {
                            $this->ci->logger->error("Emailer:deleteCsvFiles Unable to delete csv file - " . $csvfile['path']);
                        }
                    }
                }
            }
        }

        /**
         * This function allowed direct download to csv file
         * @param type $csvArray
         */
        private function downloadCSVFromArray($csvArray) {
            $name = uniqid(uniqid());

            echo "<br>CSV Name : " . $name . "<br>";

            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"$name" . ".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            foreach ($csvArray as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }

    }

}