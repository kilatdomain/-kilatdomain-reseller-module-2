<?php

define("CLIENTAREA", true);

require __DIR__ . "/init.php";
require __DIR__ . "/includes/domainfunctions.php";
require __DIR__ . "/includes/clientfunctions.php";
require __DIR__ . "/includes/registrarfunctions.php";

$domainID = $whmcs->get_req_var("id");
$userId = WHMCS\Session::get("uid");
$dnssec = [];
$domains = new WHMCS\Domains();
$domainData = $domains->getDomainsDatabyID($domainID);

if (!$domainData) {
  redir("action=domains", "clientarea.php");
}

checkContactPermission("domains");

$ca = new WHMCS\ClientArea();
$ca->requireLogin();

$pagetitle = "DNSSEC for " . $domainData["domain"];
$breadcrumbnav = "<a href=\"index.php\">Portal Home</a> > <a href=\"clientarea.php\">Client Area</a> > <a href=\"clientarea.php?action=domains\">My Domains</a> > <a href=\"clientarea.php?action=domaindetails&id=" . $domainID . "\">" . $domainData["domain"] . "</a> > <a href=\"\">DNSSEC</a>";
$pageicon = "images/domains_big.gif";

initialiseClientArea($pagetitle, $pageicon, $breadcrumbnav);

$smartyvalues['domainid'] = $domainID;
$smartyvalues['message'] = "<b>Info: </b> Make sure you understand using DNSSEC function, failure when creating record will make your domain cannot be accesed.";

$action = $whmcs->get_req_var('action');

if (isset($action) && !empty($action)) {
  if ($action == 'rem') {
    $params = array(
      'domain' => $domainData['domain'],
      'keytag' => $whmcs->get_req_var('keytag'),
      'alg' => $whmcs->get_req_var('alg'),
      'digestType' => $whmcs->get_req_var('digesttype'),
      'digest' => $whmcs->get_req_var('digest'),
    );
    $success = $domains->moduleCall('DeleteDNSSec', $params);

    if ($success) {
      $smartyvalues["successful"] = true;
    } else {
      $smartyvalues["error"] = $domains->getLastError();
    }
  } else if ($action == 'add') {
    $id = WHMCS\Input\Sanitize::decode($_POST['domainid']);

    $params = array(
      'domain' => $domainData["domain"],
      'keytag' => WHMCS\Input\Sanitize::decode($_POST['keyTag']),
      'alg' => WHMCS\Input\Sanitize::decode($_POST['alg']),
      'digestType' => WHMCS\Input\Sanitize::decode($_POST['digestType']),
      'digest' => WHMCS\Input\Sanitize::decode($_POST['digest'])
    );

    $success = $domains->moduleCall("AddDNSSec", $params);

    if ($success) {
      $smartyvalues["successful"] = true;
    } else {
      $smartyvalues["error"] = $domains->getLastError();
    }
  }
}

$success = $domains->moduleCall("InfoDNSSec");

$dnssec = array();
if ($success) {
  $data = $domains->getModuleReturn();
  if (isset($data)) {
    // parsing for 1 record
    if (isset($data['keyTag']) || isset($data['alg']) || isset($data['digestType']) || isset($data['digest'])) {
      array_push($dnssec, $data);
    }
    // parsing for > 1 record
    else {
      $dnssec = $data;
    }
  }
}

$smartyvalues['dnssec'] = $dnssec;

$templatefile = "clientareadnssec";

$primarySidebar = Menu::primarySidebar("domainView");
$secondarySidebar = Menu::secondarySidebar("domainView");
outputClientArea($templatefile);
