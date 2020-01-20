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
 * This is registar command module for connecting to Kilat Domain API.
 *
 * @author     Infinys System Indonesia
 * @copyright  Copyright (c) Infinys System Indonesia. 2020
 * @license    http://www.isi.co.id/
 * @version    3.0
 * @link       http://www.isi.co.id/
 */

if (!defined('WHMCS')) die('This file cannot be accessed directly');

require 'config.php';

function kilatdomain_getConfigArray()
{
  $configarray = array(
    'Username' => array(
      'Type' => 'text',
      'Size' => '20',
      'Description' => 'Enter your username here'
    ),
    'Password' => array(
      'Type' => 'password',
      'Size' => '20',
      'Description' => 'Enter your password here'
    ),

    'TestMode' => array(
      'Type' => 'yesno'
    )
  );
  return $configarray;
}


function kilatdomain_RegisterDomain($params)
{
  $params['data'] = $params;
  $result = kilatdomain_call('register', 'post', $params);
  $result = json_decode($result, true);

  if ($result['status'] == 'error') {
    return ['error' => $result['message']];
  }

  return $result;
}


function kilatdomain_TransferDomain($params)
{
  $params['data'] = $params;
  $result = kilatdomain_call('transfer/' . $params['domainname'], 'post', $params);
  $result = json_decode($result, true);

  if ($result['status'] == 'error') {
    return ['error' => $result['message']];
  }

  return $result['status'];
}


function kilatdomain_RenewDomain($params)
{
  $params['data'] = $params;
  $result = kilatdomain_call('renew/' . $params['domainname'], 'post', $params);
  $result = json_decode($result, true);

  if ($result['status'] == 'error') {
    return ['error' => $result['message']];
  }

  return $result['status'];
}


function kilatdomain_GetNameservers($params)
{
  $result = kilatdomain_call('nameservers/' . $params['domainname'], 'get', $params);

  $result = json_decode($result, true);

  if ($result['status'] == 'success') {
    return $result['data'];
  }

  return ['error' => $result['message']];
}

function kilatdomain_SaveNameservers($params)
{
  $params['data']['ns1'] = $params['ns1'];
  $params['data']['ns2'] = $params['ns2'];
  $params['data']['ns3'] = $params['ns3'];
  $params['data']['ns4'] = $params['ns4'];
  $params['data']['ns5'] = $params['ns5'];

  $result = kilatdomain_call('nameservers/' . $params['domainname'], 'post', $params);

  $result = json_decode($result, true);

  if ($result['status'] == 'success') {
    return $result['status'];
  }

  return ['error' => $result['message']];
}

function kilatdomain_GetRegistrarLock($params)
{
  $result = kilatdomain_call('lock/' . $params['domainname'], 'get', $params);

  $result = json_decode($result, true);

  if ($result['status'] == 'success') {
    return $result['lockstatus'];
  }

  return ['error' => $result['message']];
}

function kilatdomain_SaveRegistrarLock($params)
{
  $result = kilatdomain_call('lock/' . $params['domainname'], 'post', $params);

  $result = json_decode($result, true);

  if ($result['status'] == 'success') {
    $values = array(
      'status' => $result['status'],
      'lock' => $result['lockstatus']
    );

    return $values;
  }

  return ['error' => $result['message']];
}

function kilatdomain_GetContactDetails($params)
{
  $result = kilatdomain_call('contact/' . $params['domainname'], 'get', $params);
  $result = json_decode($result, true);

  return $result['data'];
}


function kilatdomain_SaveContactDetails($params)
{
  $result = kilatdomain_call('contact/' . $params['domainname'], 'post', $params);
  $result = json_decode($result, true);

  if ($result['status'] == 'error') {
    return ['error' => $result['message']];
  }

  return $result['status'];
}


function kilatdomain_GetEPPCode($params)
{
  $result = kilatdomain_call('eppcode/' . $params['domainname'], 'get', $params);
  $result = json_decode($result, true);
  return $result;
}


function kilatdomain_RegisterNameserver($params)
{
  $params['data'] = $params;
  $result = kilatdomain_call('nameserver/register', 'post', $params);
  $result = json_decode($result, true);

  if ($result['status'] == 'error') {
    return ['error' => $result['message']];
  }

  return $result['status'];
}


function kilatdomain_ModifyNameserver($params)
{
  $params['data'] = $params;
  $result = kilatdomain_call('nameserver/modify', 'post', $params);
  $result = json_decode($result, true);

  if ($result['status'] == 'error') {
    return ['error' => $result['message']];
  }

  return $result['status'];
}

function kilatdomain_DeleteNameserver($params)
{
  $params['data'] = $params;
  $result = kilatdomain_call('nameserver/delete', 'post', $params);
  $result = json_decode($result, true);

  if ($result['status'] == 'error') {
    return ['error' => $result['message']];
  }

  return $result['status'];
}

function kilatdomain_RequestDelete($params)
{
  $params['domain'] = $params['domainname'];

  $result = kilatdomain_call('delete', 'post', $params);
  $result = json_decode($result, true);

  return $result;
}

function kilatdomain_DNSGetRecords($params)
{
  $result = kilatdomain_call('dns/' . $params['domainname'], 'get', $params);

  $result = json_decode($result, true);
  return $result;
}

function kilatdomain_DNSAddRecord($params)
{
  $result = kilatdomain_call('dns/add', 'post', $params);
  $result = json_decode($result, true);

  return $result;
}

function kilatdomain_DNSDeleteRecord($params)
{
  $result = kilatdomain_call('dns/delete', 'post', $params);
  $result = json_decode($result, true);

  return $result;
}

function kilatdomain_DFGetRecords($params)
{
  $result = kilatdomain_call('url/' . $params['domainname'], 'get', $params);
  $result = json_decode($result, true);

  return $result;
}

function kilatdomain_DFAddRecords($params)
{
  $result = kilatdomain_call('url/add', 'post', $params);
  $result = json_decode($result, true);

  return $result;
}

function kilatdomain_DFDeleteRecords($params)
{
  $result = kilatdomain_call('url/delete', 'post', $params);
  $result = json_decode($result, true);

  return $result;
}

function kilatdomain_InfoDNSSec($params)
{
  $result = kilatdomain_call('dnssec/' . $params['domainname'], 'get', $params);

  $result = json_decode($result, true);

  if ($result['status'] == 'error') {
    return ['error' => $result['message']];
  }

  return $result['dnssec'];
}

function kilatdomain_AddDNSSec($params)
{
  $params['data'] = $params;
  $result = kilatdomain_call('dnssec/add', 'post', $params);
  $result = json_decode($result, true);
 
  if ($result['status'] == 'error') {
    return ['error' => $result['message']];
  }

  return $result['status'];
}

function kilatdomain_DeleteDNSSec($params)
{
  $params['data'] = $params;
  $result = kilatdomain_call('dnssec/delete', 'post', $params);
  $result = json_decode($result, true);
 
  if ($result['status'] == 'error') {
    return ['error' => $result['message']];
  }

  return $result['status'];
}

function kilatdomain_UploadDocument($params)
{

  if (function_exists('curl_file_create')) {
    $cFile = curl_file_create($params['full_path'], $params['mime']);
  } else {
    $cFile = '@' . realpath($params['full_path']);
  }

  $params['file'] = $cFile;
  $params['do_active'] = (isset($params['do_active']) ? $params['do_active'] : false);
  $result = kilatdomain_call('document/' . $params['domainname'], 'post', $params, true);

  return $result;
}


function kilatdomain_call($url, $method, $params, bool $isUpload = false)
{
  if ($params["TestMode"] == true || $params["TestMode"] == 'on') {
    $base_url = KILATDOMAIN_API_SANDBOX;
  } else {
    $base_url = KILATDOMAIN_API;
  }

  $url = $base_url . $url;

  $sendParams = http_build_query($params);

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url);

  $header = [
    'Authorization: Basic ' . base64_encode($params['Username'] . ':' . $params['Password'])
  ];

  if (!$isUpload) {
    array_push($header, 'Content-Type: application/json');
  }
  else {
    $sendParams = $params;
  }

  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

  if ($method == 'post') {
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $sendParams);
  }

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  $response = curl_exec($ch);

  if (curl_errno($ch)) {
    var_dump(curl_error($ch));
  }

  return $response;
}
