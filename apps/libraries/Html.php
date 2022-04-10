<?php

defined('BASEPATH') or exit('No direct script access allowed');
/* * ***********************************************************************
 *  File: WhatsApp.php
 *  Path: application/libraries/WhatsApp.php
 *  Description: This is WhatsApp Group management library
 * 
 *  History:
 *  Programmer          Date                    Description
 *  -----------         ----------              ----------------
 *  Rahul Siwal         04/07/2021              Created
 *
 */

if (!class_exists('Html')) {

	class Html {

		private $ci;

		public function __construct() {
			$this->ci = &get_instance();
		}

		public function get($html, $id, $singleNode = true, $bytagname = false, $byid = false) {
			return $this->_get($html, $id, $singleNode, $bytagname, $byid);
		}

		public function metatag($html, $tags = []) {
			$html = is_array($html) ? implode("", $html) : $html;
			preg_match_all('/<[\s]*meta[\s]*name="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $html, $name);
			preg_match_all('/<[\s]*meta[\s]*property="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $html, $property);
			return array_merge($this->extract($name, $tags), $this->extract($property, $tags));
		}

		public function href($anchor) {
			$extract = new SimpleXMLElement($anchor);
			return isset($extract["href"]) ? $extract["href"] : null;
		}

		public function extract($match, $tags) {
			$metaTags = array();
			$all_tags = (is_array($tags) && count($tags) > 0) ? false : true;
			if (isset($match) && is_array($match) && count($match) == 3) {
				$originals = $match[0];
				$names = $match[1];
				$values = $match[2];
				if (count($originals) == count($names) && count($names) == count($values)) {
					for ($i = 0, $limiti = count($names); $i < $limiti; $i++) {
						if ($all_tags || in_array($names[$i], $tags)) {
							$metaTags[$names[$i]] = html_entity_decode($values[$i]);
						}
					}
				}
			}
			return $metaTags;
		}

		private function _get($html, $id, $singleNode = true, $bytagname = false, $byid = false) {
			$identifier = ($byid == true) ? "(@id, '" . $id . "')" : "(@class, '" . $id . "')";
			$doc = new DOMDocument();
			libxml_use_internal_errors(true);
			$doc->loadHTML($html);
			$finder = new DomXPath($doc);
			$node = $finder->query("//*[contains" . $identifier . "]");
			if ($singleNode == true) {
				if ($bytagname == true) {
					$node = $doc->getElementsByTagName($id);
					$html = $doc->saveHTML($node->item(0));
					$html = preg_replace('/(\>)\s*(\<)/m', '$1$2', $html);
					return strip_tags($html);
				} else {
					$html = $doc->saveHTML($node->item(0));
					$html = preg_replace('/(\>)\s*(\<)/m', '$1$2', $html);
					$html = preg_replace('/\s+/', ' ', $html);
					$html = strip_tags($html);
					return (strlen($html) > 250) ? "" : $html;
				}
			} else {
				if ($bytagname == true) {
					$array = array();
					$list = $doc->getElementsByTagName($id);
					foreach ($list as $row)
						$array[] = $doc->saveHTML($row);
					return $array;
				} else {
					$array = array();
					foreach ($node as $row)
						$array[] = $doc->saveHTML($row);
					return $array;
				}
			}
		}
	}
}
