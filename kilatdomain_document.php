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
 * @package    Infinys - Document Management (Clientarea) for Reseller
 * @copyright  Copyright (c) PT Infinys System Indonesia 2020
 **/

define("CLIENTAREA", true);

require __DIR__ . "/init.php";
require __DIR__ . "/includes/domainfunctions.php";
require __DIR__ . "/includes/clientfunctions.php";
require __DIR__ . "/includes/registrarfunctions.php";
require __DIR__ . '/modules/addons/domainDocument/document.class.php';

use Modules\Addons\DomainDocument\Document as Document;

use Illuminate\Database\Capsule\Manager as Capsule;

$ca = new WHMCS\ClientArea();
$ca->requireLogin();

$domainid = $whmcs->get_req_var("id");
$userid = WHMCS\Session::get("uid");
$domains = new WHMCS\Domains();
$domaindata = $domains->getDomainsDatabyID($domainid);

if (!$domaindata) {
  redir("action=domains", "clientarea.php");
}

checkContactPermission("domains");

$pagetitle = "Domain Documents for " . $domaindata["domain"];
$breadcrumbnav = "<a href=\"index.php\">Portal Home</a> > <a href=\"clientarea.php\">Client Area</a> > <a href=\"clientarea.php?action=domains\">My Domains</a> > <a href=\"clientarea.php?action=domaindetails&id=" . $domainid . "\">" . $domaindata["domain"] . "</a> > <a href=\"\">Domain Documents</a>";
$pageicon = "images/domains_big.gif";

initialiseClientArea($pagetitle, $pageicon, $breadcrumbnav);

$smartyvalues['domainid'] = $domainid;
$smartyvalues['message'] = "From here you can upload your document for .ID Registration.
";

$action = $whmcs->get_req_var('action');

if (isset($action) && !empty($action)) {
  if ($action == 'identity' || $action == 'legality' || $action == 'additional') {
    $data = array(
      'userid' => $userid,
      'domainid' => $domainid,
      'domain' => $domaindata['domain'],
      'registration_date' => $domaindata['registrationdate'],
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
      $smartyvalues["successful"] = true;
    } else {
      $smartyvalues["error"] = $upload['message'];
    }
  } else if ($action == 'download') {
    $name = $whmcs->get_req_var("name");
    $subfolder = substr($name, 0, 2);
    $file = Document::UPLOADPATH .  $subfolder . '/' . $name;
    Document::download_file($file);
  }
}

$get_identity = Document::get_file($domainid, 'identity');
$smartyvalues['identity_document'] = $get_identity['document'];
$smartyvalues['identity_type'] = $get_identity['type'];

$get_legality = Document::get_file($domainid, 'legality');
$smartyvalues['legality_document'] = $get_legality['document'];
$smartyvalues['legality_type'] = $get_legality['type'];

$get_additional = Document::get_file($domainid, 'additional');
$smartyvalues['additional_document'] = $get_additional['document'];
$smartyvalues['additional_type'] = $get_additional['type'];

$templatefile = "clientareadomaindocuments";

$primarySidebar = Menu::primarySidebar("domainView");
$secondarySidebar = Menu::secondarySidebar("domainView");
outputClientArea($templatefile);
