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
 * @package    Infinys - DNS Management Systems for Reseller
 * @copyright  Copyright (c) PT Infinys System Indonesia 2020
 **/

define("CLIENTAREA", true);

require __DIR__ . "/init.php";
require __DIR__ . "/includes/domainfunctions.php";
require __DIR__ . "/includes/clientfunctions.php";
require __DIR__ . "/includes/registrarfunctions.php";


$ca = new WHMCS\ClientArea();
$ca->requireLogin();

$domainID = $whmcs->get_req_var("id");
$userId = WHMCS\Session::get("uid");
$domains = new WHMCS\Domains();
$domainData = $domains->getDomainsDatabyID($domainID);

if (!$domainData) {
  redir("action=domains", "clientarea.php");
}

checkContactPermission("domains");

$pagetitle = "DNS Management for " . $domainData["domain"];
$breadcrumbnav = "<a href=\"index.php\">Portal Home</a> > <a href=\"clientarea.php\">Client Area</a> > <a href=\"clientarea.php?action=domains\">My Domains</a> > <a href=\"clientarea.php?action=domaindetails&id=" . $domainID . "\">" . $domainData["domain"] . "</a> > <a href=\"\">DNS Management</a>";
$pageicon = "images/domains_big.gif";

initialiseClientArea($pagetitle, $pageicon, $breadcrumbnav);

$smartyvalues['domainid'] = $domainID;
$smartyvalues['message'] = "<b>Info: </b> Make sure you understand using DNSSEC function, failure when creating record will make your domain cannot be accesed.";

$post_data = array(
  'domain' => $domainData['domain'],
  'host' => (isset($_POST['recordhost']) ? WHMCS\Input\Sanitize::decode($_POST['recordhost']) : ""),
  'type' => (isset($_POST['recordtype']) ? WHMCS\Input\Sanitize::decode($_POST['recordtype']) : ""),
  'address' => (isset($_POST['recordaddress']) ? WHMCS\Input\Sanitize::decode($_POST['recordaddress']) : ""),
  'ttl' => (isset($_POST['recordttl']) ? WHMCS\Input\Sanitize::decode($_POST['recordttl']) : ""),
  'priority' => (isset($_POST['recordpriority']) ? WHMCS\Input\Sanitize::decode($_POST['recordpriority']) : ""),
);

$action = $whmcs->get_req_var('action');

$params = array(
  'registrar' => 'kilatdomain',
  'domainid' => $domainID,
);

if (isset($action) && !empty($action)) {
  if ($action == 'add') {
    $params['data'] = $post_data;

    $dns_result = RegCallFunction($params, 'DNSAddRecord');

    if ($dns_result['status'] == 'success') {
      $smartyvalues["successful"] = true;
    } else {
      $smartyvalues["error"] = $dns_result['message'];
    }
  } else if ($action == 'rem') {
    $params['data'] = ['recid' => $whmcs->get_req_var("recid")];

    $delete = RegCallFunction($params, 'DNSDeleteRecord');

    if ($delete['code'] == '200') {
      $smartyvalues["successful"] = true;
    } else {
      $smartyvalues["error"] = $delete['message'];
    }
  }
}

$dnsrecords = RegCallFunction($params, 'DNSGetRecords');

$smartyvalues['dnsrecords'] = $dnsrecords['records'];

$templatefile = "clientareadns";

$primarySidebar = Menu::primarySidebar("domainView");
$secondarySidebar = Menu::secondarySidebar("domainView");
outputClientArea($templatefile);
