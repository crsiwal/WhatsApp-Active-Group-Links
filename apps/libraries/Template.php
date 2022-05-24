<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Template.php
 *  Path: application/libraries/Template.php
 *  Description: This class is used for handle template for view page.
 *  
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         17/04/2019              Created
 *  Rahul Siwal         30/05/2021              Enhanced
 *
 */
if (!class_exists('Template')) {

    class Template {

        private $head;
        private $js;

        public function __construct() {
            $this->ci = &get_instance();
            $this->init();
        }

        private function init() {
            $this->update_configs();
            $this->meta();
            $this->css();
            $this->javaScript();
            $this->header();
            $this->sidebar('left');
            $this->sidebar('right');
            $this->footer();
        }


        /**
         * This will set the page name.
         * @param type $page_name
         */
        private function setPageName($page_name) {
            $this->ci->sessions->set_page($page_name);
        }


        /**
         * 
         * @param type $template
         * @param type $data
         */
        public function show($template, $data = array(), $page_name = 'unknown', $need_access = "public") {
            if (access($need_access)) {
                $this->ci->logger->start(__METHOD__);
                $this->setPageName($page_name);
                $footer_end = array(
                    'js' => $this->js,
                    'popup' => $this->popups((isset($data['popup']) && is_array($data['popup'])) ? $data['popup'] : array())
                );
                $sanitize = (ENVIRONMENT == 'development') ? FALSE : TRUE;
                switch ($sanitize) {
                    case TRUE:
                        $content = '';
                        $content .= $this->ci->load->view('header/head', $this->head, TRUE);
                        $content .= $this->ci->load->view('header/' . $this->head['header'], $data, TRUE);
                        $content .= ($this->head['sidebar_left'] != FALSE) ? $this->ci->load->view('sidebar/' . $this->head['sidebar_left'], $data, TRUE) : '';
                        $content .= $this->ci->load->view($template, $data, TRUE);
                        $content .= ($this->head['sidebar_right'] != FALSE) ? $this->ci->load->view('sidebar/' . $this->head['sidebar_right'], $data, TRUE) : '';
                        $content .= $this->ci->load->view('footer/' . $this->head['footer'], $data, TRUE);
                        $content .= $this->ci->load->view('footer/foot', $footer_end, TRUE);
                        $this->sanitize($content);
                        break;
                    case FALSE:
                        $this->ci->load->view('header/head', $this->head);
                        $this->ci->load->view('header/' . $this->head['header'], $data);
                        ($this->head['sidebar_left'] != FALSE) ? $this->ci->load->view('sidebar/' . $this->head['sidebar_left'], $data) : '';
                        $this->ci->load->view($template, $data);
                        ($this->head['sidebar_right'] != FALSE) ? $this->ci->load->view('sidebar/' . $this->head['sidebar_right'], $data) : '';
                        $this->ci->load->view('footer/' . $this->head['footer'], $data);
                        $this->ci->load->view('footer/foot', $footer_end);
                        break;
                }
                $this->ci->logger->end(__METHOD__);
            } else {
                show_404();
            }
        }

        /**
         * 
         * @param type $key
         * @param type $value
         */
        public function addMeta($key = '', $value = '') {
            switch ($key) {
                case 'title':
                    $this->head['title'] = $value;
                    break;
                case 'description':
                    $this->head['description'] = $value;
                    break;
                default:
                    if (!empty($key))
                        $this->head['meta'][$key] = $value;
                    break;
            }
        }

        /**
         * 
         * @param type $name
         * @param type $path
         * @param type $isurl
         */
        public function addCss($name, $isurl = FALSE) {
            if (is_array($name)) {
                foreach ($name as $filename) {
                    $this->addCss($filename);
                }
            } else {
                $path = $this->cssFiles($name);
                if ($path === FALSE) {
                    $this->ci->logger->error(__METHOD__, "Unknown file trying to add - $name");
                } else {
                    if ($isurl === TRUE) {
                        $this->head['css'][$name] = $path;
                    } else {
                        $css_path = 'css/' . $path . '.css';
                        $location = asset_path($css_path);
                        if (file_exists($location)) {
                            $this->head['css'][$name] = asset_url($css_path, TRUE);
                        } else {
                            $this->ci->logger->error(__METHOD__, "File not found Location - $location");
                        }
                    }
                }
            }
        }

        /**
         * 
         * @param type $name
         * @param type $path
         * @param type $inHead
         * @param type $isurl
         */
        public function addJs($name, $inHead = FALSE, $isurl = FALSE) {
            if (is_array($name)) {
                foreach ($name as $filename) {
                    $this->addJs($filename);
                }
            } else {
                $path = $this->jsFiles($name);
                if ($path === FALSE) {
                    $this->ci->logger->error(__METHOD__, "Unknown file trying to add - $name");
                } else {
                    if ($isurl === TRUE) {
                        if ($inHead === TRUE) {
                            $this->head['js'][$name] = $path;
                        } else {
                            $this->js[$name] = $path;
                        }
                    } else {
                        $js_path = 'js/' . $path . '.js';
                        $location = asset_path($js_path);
                        if (file_exists($location)) {
                            if ($inHead === TRUE) {
                                $this->head['js'][$name] = asset_url($js_path, TRUE);
                            } else {
                                $this->js[$name] = asset_url($js_path, TRUE);
                            }
                        } else {
                            $this->ci->logger->error(__METHOD__, "File not found Location - $location");
                        }
                    }
                }
            }
        }

        public function header($header = 'main') {
            $file = "blank";
            if (in_array($header, ["main", "admin"])) {
                $file = $header;
            }
            $this->head['header'] = $file;
        }

        /**
         * 
         * @param type $footer
         */
        public function footer($footer = 'main') {
            $file = "blank";
            if (in_array($footer, ["main"])) {
                $file = $footer;
            }
            $this->head['footer'] = $file;
        }

        /**
         * 
         * @param type $sidebar
         * @param type $template
         */
        public function sidebar($sidebar = '', $template = 'main') {
            if (in_array($sidebar, ["left", "right"]) && !empty($template)) {
                $this->head["sidebar_$sidebar"] = $sidebar . "_" . $template;
            }
            return false;
        }

        /**
         * 
         */
        private function meta() {
            $this->addMeta('title', $this->ci->config->item('site_title'));
            $this->addMeta('description', $this->ci->config->item('site_description'));
        }

        /**
         * 
         * @param type $popupList
         * @return array
         */
        private function popups($popupList = array()) {
            $popups = array();
            foreach ($popupList as $popupFileName) {
                $location = APPPATH . 'views/popup/' . $popupFileName . '.php';
                if (file_exists($location)) {
                    array_push($popups, $location);
                } else {
                    $this->ci->logger->error(__METHOD__, "File not found Location - $location");
                }
            }
            return $popups;
        }

        private function css() {
            if (minify('css')) {
                $this->addCss('fontawesome-link-css', true);
                $compressedFiles = $this->ci->config->item('minified_css_files');
                if (is_array($compressedFiles) && count($compressedFiles) > 0) {
                    foreach ($compressedFiles as $filename => $path) {
                        $this->addCss($filename);
                    }
                }
            } else {
                $this->addCss('fontawesome-link-css', true);
                $this->addCss(['bootstrap-css', 'toast-css', 'custom-css']);
            }
        }

        private function javaScript() {
            if (minify('js')) {
                $this->addJs('config', TRUE, TRUE);
                $compressedFiles = $this->ci->config->item('minified_js_files');
                if (is_array($compressedFiles) && count($compressedFiles) > 0) {
                    foreach ($compressedFiles as $filename => $path) {
                        $this->addJs($filename);
                    }
                }
            } else {
                $this->addJs(['jquery', 'bootstrap', 'toast']);
                $this->addJs('config', TRUE, TRUE);
                $this->addJs(['app',  'page', 'event']);
            }
        }

        private function jsFiles($name) {
            $files = $this->ci->config->item('assets_js');
            if (minify('js')) {
                $minifiedFiles = $this->ci->config->item('minified_js_files');
                $files = (is_array($minifiedFiles) && count($minifiedFiles) > 0) ? array_merge($files, $minifiedFiles) : $files;
            }
            return isset($files[$name]) ? $files[$name] : FALSE;
        }

        private function cssFiles($name) {
            $files = $this->ci->config->item('assets_css');
            if (minify('css')) {
                $minifiedFiles = $this->ci->config->item('minified_css_files');
                $files = (is_array($minifiedFiles) && count($minifiedFiles) > 0) ? array_merge($files, $minifiedFiles) : $files;
            }
            return isset($files[$name]) ? $files[$name] : FALSE;
        }

        private function update_configs() {
            $this->ci->config->config['assets_js']['config'] = base_url("/assets/config");
        }

        private function sanitize($content = '') {
            $search = array(
                '/\>[^\S ]+/s', // strip whitespaces after tags, except space
                '/[^\S ]+\</s', // strip whitespaces before tags, except space
                '/(\s)+/s', // shorten multiple whitespace sequences
                '/<!--(.|\s)*?-->/' // Remove HTML comments
            );
            echo preg_replace($search, ['>', '<', '\\1', ''], $content);
        }
    }
}
