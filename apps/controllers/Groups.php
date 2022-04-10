<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: Category.php
 *  Path: application/controllers/Category.php
 *  Description: It's a categories controler .
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         29/05/2021              Created
 *
 */

class Groups extends CI_Controller {

    public function index($slug = "", $page_number = 1) {
        $this->load->library(["Category", "Group"]);
        $number_of_record = $this->config->item("show_groups_per_page");
        if (empty($slug) || ($category = $this->category->by_slug($slug)) == false) {
            echo "Not a Group";
        } else {
            $offsets = ($page_number < 2) ? 0 : (($page_number - 1) * $number_of_record);
            $groups = $this->group->by_category($category->id, $number_of_record, $offsets);
            $data = array(
                "popup" => array(),
                "category" => $category->name,
                "page_number" => $page_number,
                "next_url" => (count($groups) == $number_of_record) ? url("groups/$slug/" . ($page_number + 1), true) : "",
                "groups" => $groups,
                "trending" => $this->group->trending(12)
            );
            $this->template->addMeta('title', 'Category Groups | Whatsapp Groups');
            $this->template->addMeta('description', 'Groups of this category categories.');
            $this->template->show('pages/groups/list', $data, 'grouplist');
        }
    }

    public function invite($invite_key = "") {
        $this->load->library(["Group"]);
        if (empty($invite_key) || ($group = $this->group->by_invite_key($invite_key)) == false) {
            show_404();
        } else {
            $data = array(
                "popup" => array(),
                "group" => $group,
                "trending" => $this->group->trending(12)
            );
            $this->template->addMeta('title', 'Group Name | Whatsapp Groups Links');
            $this->template->addMeta('description', 'Collection of whatsapp groups');
            $this->template->show('pages/groups/single', $data, 'singlegroup');
        }
    }
}
