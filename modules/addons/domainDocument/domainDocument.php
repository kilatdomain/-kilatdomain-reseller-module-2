<?php

/**
 * Copyright (c) 2020, Infinys System Indonesia
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * This is document management module for Kilat Domain Reseller. 
 *
 * @author     Infinys System Indonesia
 * @copyright  Copyright (c) Infinys System Indonesia. 2020
 * @license    http://www.isi.co.id/
 * @version    $Id$
 * @link       http://www.isi.co.id/
 */

require_once 'document.class.php';
require_once ROOTDIR . '/modules/addons/tableinfinys.class.php';


use Modules\Addons\DomainDocument\Document as Document;

if (!defined("WHMCS"))
  die("This file cannot be accessed directly");

function domainDocument_config()
{
  $config = array(
    "name" => "Domain Documents",
    "description" => "Domain Documents Management",
    "version" => "3.0",
    "author" => "Infinys System Indonesia",
    "language" => "english",
    "fields" => array()
  );

  return $config;
}

function domainDocument_activate()
{
  try {
    Document::create_table();

    return array('status' => 'success', 'description' => 'Domain Document module has been activated successfully');
  } catch (Exception $e) {
    return array('status' => 'error', 'description' => "Unable to activate module: {$e->getMessage()}");
  }
}

function domainDocument_deactivate()
{
  return array('status' => 'success', 'description' => 'Domain Document module has been deactivated successfully');
}

function domainDocument_output($vars)
{

  $action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : false);

  if (isset($action) && !empty($action)) {
    if ($action == 'identity' || $action == 'legality' || $action == 'additional') {


      $data = array(
        'userid' => (isset($_POST['userid']) ? WHMCS\Input\Sanitize::decode($_POST['userid']) : ""),
        'domainid' => (isset($_POST['domainid']) ? WHMCS\Input\Sanitize::decode($_POST['domainid']) : ""),
        'domain' => (isset($_POST['domain']) ? WHMCS\Input\Sanitize::decode($_POST['domain']) : ""),
        'registration_date' => (isset($_POST['registration_date']) ? WHMCS\Input\Sanitize::decode($_POST['registration_date']) : ""),
        'action' => $action
      );

      switch ($action) {
        case 'identity':
          $data['type'] = (isset($_POST['type_id']) ? WHMCS\Input\Sanitize::decode($_POST['type_id']) : "");
          $data['file'] = ((isset($_FILES['file_id'])) ? $_FILES['file_id'] : "");
          break;

        case 'legality':
          $data['type'] = (isset($_POST['type_ld']) ? WHMCS\Input\Sanitize::decode($_POST['type_ld']) : "");
          $data['file'] = ((isset($_FILES['file_ld'])) ? $_FILES['file_ld'] : "");
          break;

        case 'additional':
          $data['type'] = (isset($_POST['type_ad']) ? WHMCS\Input\Sanitize::decode($_POST['type_ad']) : "");
          $data['file'] = ((isset($_FILES['file_ad'])) ? $_FILES['file_ad'] : "");
          break;
      }

      $upload = Document::save_file($data);

      if ($upload['status'] == 'success') {
        echo Document::notification('Domain Document has been uploaded');
      } else {
        echo Document::notification($upload['message'], 'error');
      }
    } else if ($action == 'download') {
      $name = (isset($_REQUEST['name']) ? $_REQUEST['name'] : false);
      if ($name) {
        $subfolder = substr($name, 0, 2);
        $file = Document::UPLOADPATH .  $subfolder . '/' . $name;
        Document::download_file($file);
      }
    } else if ($action == 'manage') {
      $data = array(
        'id' => (isset($_POST['id']) ? WHMCS\Input\Sanitize::decode($_POST['id']) : ""),
        'status' => (isset($_POST['status']) ? (int) WHMCS\Input\Sanitize::decode($_POST['status']) : ""),
      );

      $update = Document::update_status($data);
      if ($update['status'] == 'success') {
        echo Document::notification('Domain status has been updated');
      } else {
        echo Document::notification($update['message'], 'error');
      }
    } else if ($action == 'renew') {
      $data = array(
        'transfersecret' => (isset($_POST['epp_code']) ? WHMCS\Input\Sanitize::decode($_POST['epp_code']) : ""),
        'id' => (isset($_POST['id']) ? WHMCS\Input\Sanitize::decode($_POST['id']) : "")
      );

      $renew = Document::renew_transfer($data);

      if ($renew['status'] == 'success') {
        echo Document::notification('Domain status has been updated');
      } else {
        echo Document::notification($renew['message'], 'error');
      }
    }
  }

  include 'templates/table.tpl.php';
}
