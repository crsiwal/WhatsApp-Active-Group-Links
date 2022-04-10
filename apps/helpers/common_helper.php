<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Common.php
 *  Path: application/helpers/Common.php
 *  Description: Common function collection.
 *
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              --------------
 *  Rahul Siwal         29/05/2021              Created
 *
 */

function get_integer($string) {
    return (int) $string;
}

function get_boolean_input($input_key) {
    $ci = &get_instance();
    return (filter_var($ci->input->post($input_key, true), FILTER_VALIDATE_BOOLEAN) === true) ? true : false;
}

function get_boolean($input_value) {
    return (filter_var($input_value, FILTER_VALIDATE_BOOLEAN) === true) ? true : false;
}

function get_input_method() {
    $ci = &get_instance();
    return strtoupper($ci->input->server('REQUEST_METHOD'));
}

function cache_key($cache_array) {
    return implode("_", $cache_array);
}

function cache_time($obj = "daily") {
    $ci = &get_instance();
    if ($hours = $ci->config->item($obj . "_cache_time")) {
        return hour_in_seconds($hours);
    } else {
        return hour_in_seconds($ci->config->item("daily_cache_time"));
    }
}

function hour_in_seconds($hours = 0) {
    return $hours * 60 * 60;
}

function add_block($blockName, $blockData = [], $access = "public", $return = false) {
    if ($access != false && access($access)) {
        $ci = &get_instance();
        return $ci->load->view('blocks/' . $blockName, $blockData, $return);
    }
}

function get_time($format = "Y-m-d H:i:s") {
    return date($format);
}

function get_date($format = "Y-m-d") {
    $timeFormat = in_array($format, ["Y-m-d", "Y/m/d", "Ym", "d"]) ? $format : "Y-m-d";
    return date($timeFormat);
}

function access($accessName = "") {
    if ($accessName == "public") {
        return true;
    } else {
        $ci = &get_instance();
        return $ci->user->is_accessable($accessName);
    }
}

function get_page_name() {
    $ci = &get_instance();
    return $ci->sessions->get_page();
}


function is_active_page($pagename) {
    if (is_array($pagename)) {
        return in_array(get_page_name(), $pagename);
    } else {
        return (get_page_name() == $pagename);
    }
}

function addmenu($permision, $name, $url = "#", $icon = "", $active = false, $uppercase = false) {
    if (access($permision)) {
        $active_class = ($active) ? "active" : "";
?>
        <li class="nav-item <?= $active_class; ?>">
            <a class="nav-link" href="<?= url($url, true); ?>">
                <?php
                if (!empty($icon)) {
                ?>
                    <div class="image-container">
                        <span class="fa <?= $icon; ?>" aria-hidden="true"></span>
                    </div>
                <?php
                }
                ?>
                <span class="<?= ($uppercase) ? 'text-uppercase' : 'text-capitalize'; ?>"><?= $name; ?></span>
            </a>
        </li>
    <?php
    }
}

function addgroup($permision, $name, $icon = "", $submenu = [], $active = false) {
    if (access($permision) && is_array($submenu)) {
        $active_class = ($active) ? "active" : "";
    ?>
        <li class="<?= $active_class; ?>">
            <div class="navitem-container">
                <a href="#<?= "link" . $permision; ?>" data-toggle="collapse" aria-expanded="false" class="attribution-link">
                    <i class="fa fa-caret-down multiopt"></i>
                    <div class="text-image-container">
                        <div class="image-container">
                            <span class="fa <?= $icon; ?>" aria-hidden="true"></span>
                        </div>
                        <span class="text-capitalize"><?= $name; ?></span>
                    </div>
                </a>
            </div>
            <ul class="collapse list-unstyled" id="<?= "link" . $permision; ?>">
                <?php
                foreach ($submenu as $menu) {
                    addSubmenu($menu[0], $menu[1], $menu[2]);
                }
                ?>
            </ul>
        </li>
<?php
    }
}

function addSubmenu($permision, $name, $url = "#") {
    if (access($permision)) {
        echo '<li><a href="' . url($url, true) . '">' . $name . '</a></li>';
    }
}

function tooltipList($listArray) {
    $message = "";
    if (is_array($listArray)) {
        foreach ($listArray as $name => $list) {
            switch ($name) {
                case "title":
                    $message .= '<div class="ttle">' . $list . '</div>';
                    break;
                case "description":
                    $message .= '<div class="tlst"><span class="tplv">' . $list . '</span></div>';
                    break;
                default:
                    $message .= '<div class="tlst"><span class="tpln">' . $name . '</span>: <span class="tplv">' . $list . '</span></div>';
                    break;
            }
        }
    }
    tooltip($message);
}

function breadcrumb($list = [], $return = false) {
    $text = '<p class="font-12 text-capitalize text-darkgray-2">';
    $text .= '<span class=""><a href="' . url("", true) . '">Home</a></span> / ';
    if (is_array($list)) {
        $length = count($list);
        $loop = 0;
        foreach ($list as $name => $link) {
            $link = url($link, true);
            if (++$loop == $length) {
                $text .= '<span class="text-blue">' . $name;
            } else {
                $text .= '<span class=""><a href="' . $link . '">' . $name . '</a><span> / ';
            }
        }
    }
    $text .= '</p>';
    if ($return) {
        return $text;
    } else {
        echo $text;
    }
}

function tooltip($message = "") {
    echo '<span class="infotip pointer" data-toggle="tooltip" data-placement="bottom" title="' . htmlentities($message) . '"></span>';
}

function ellipse($string = "", $length = 12) {
    return (strlen($string) > $length) ? substr($string, 0, strrpos($string, ' ', ($length - strlen($string)))) . '...' : $string;
}

function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function clog($log, $method = "") {
    echo get_time() . "\t" . $method  . "\t -> " . $log . PHP_EOL;
}

/**
 *
 * @param type $length
 * @param type $method
 * @author Rahul Siwal <rsiwal@yahoo.com>
 * @return type
 */
function unique_key($length = 8, $method = "shuffle") {
    if ($method == "shuffle") {
        $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($string), 0, $length);
    } elseif ($method == "microtime") {
        $unique_number = str_replace(".", "", microtime(true));
        return base_convert($unique_number, 10, 36);
    } else {
        return bin2hex(openssl_random_pseudo_bytes(($length / 2)));
    }
}

function set_input_error() {
    $ci = &get_instance();
    $error = $ci->form->error_array();
    $ci->sessions->set_error(reset($error));
}

function set_error($error = "") {
    $ci = &get_instance();
    $ci->sessions->set_error($error);
}

function get_error() {
    $ci = &get_instance();
    return $ci->sessions->get_error();
}

function set_msg($message = "") {
    $ci = &get_instance();
    $ci->sessions->set_msg($message);
}

function get_msg() {
    $ci = &get_instance();
    return $ci->sessions->get_msg();
}

function is_mobile() {
    $ci = &get_instance();
    return $ci->agent->is_mobile();
}

function get_hashtag($content = "") {
    $hashtags = [];
    $clean_one = strip_tags($content);
    $clean_two = html_entity_decode($clean_one);
    $clean_final = strip_tags($clean_two);
    preg_match_all("/#(\\w+)/", $clean_final, $hashtags);
    return (isset($hashtags[1]) && is_array($hashtags[1])) ? array_unique($hashtags[1]) : [];
}


function groups_url_from_others($website_link) {
    $ci = &get_instance();
    $ci->load->library("Html");
    $html = $ci->curl->html($website_link, []);
    $links = $ci->html->get($html, "a", false, true);
    $groups = [];
    if (is_array($links)) {
        foreach ($links as $link) {
            if (strpos($link, 'chat.whatsapp.com') !== false) {
                $url = $ci->html->href($link);
                if (substr_count($url, "https") == 1) {
                    array_push($groups, $url);
                } elseif (substr_count($url, "http") == 1) {
                    $url = str_replace("http://", "https://", $url);
                    array_push($groups, $url);
                } else {
                    $split = explode("https", $url);
                    if (count($split) > 0) {
                        foreach ($split as $gurl) {
                            if (!empty($gurl)) {
                                $gurl = "https" . $gurl;
                                array_push($groups, $gurl);
                            }
                        }
                    }
                }
            }
        }
    }
    return $groups;
}

function groups_url_from_line_by_line_text($group_links_text) {
    $groups = [];
    $group_links = explode("\r\n", trim($group_links_text));
    if (is_array($group_links)) {
        foreach ($group_links as $url) {
            if (strpos($url, 'chat.whatsapp.com') !== false) {
                if (substr_count($url, "https") == 1) {
                    array_push($groups, $url);
                } elseif (substr_count($url, "http") == 1) {
                    $url = str_replace("http://", "https://", $url);
                    array_push($groups, $url);
                } else {
                    $split = explode("https", $url);
                    if (count($split) > 0) {
                        foreach ($split as $gurl) {
                            if (!empty($gurl)) {
                                $gurl = "https" . $gurl;
                                array_push($groups, $gurl);
                            }
                        }
                    }
                }
            }
        }
    }
    return $groups;
}

function convert_username($string, $rand_if_none = true, $min_length = 5) {
    $phase_one = strtolower(str_replace(" ", "-", strip_tags($string)));
    $phase_two = preg_replace('/[^A-Za-z0-9]+/', '-', $phase_one);
    $phase_three = trim($phase_two, "-");
    return (empty($phase_three)) ? ($rand_if_none ? unique_key($min_length) : "") : (strlen($phase_three) < $min_length ? ($phase_three . "_" . unique_key($min_length - strlen($phase_three))) : $phase_three);
}

/**
 *
 * @param type $condition
 * @param type $values
 * @return type
 */
function where($condition = [], $values = []) {
    $where = [];
    if (is_array($condition)) {
        foreach ($condition as $key) {
            switch ($key) {
                case 'user_id':
                    $where['user_id'] = (isset($values[$key]) ? $values[$key] : get_logged_in_user_id());
                    break;
                default:
                    $where[$key] = (isset($values[$key]) ? $values[$key] : "");
            }
        }
    }
    return $where;
}
