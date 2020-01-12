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
 * Add DNS Management, Domain Forwarding and Domain Documents on Domain Details Management Sidebar
 *
 * @package    Infinys
 * @copyright  Copyright (c) PT Infinys System Indonesia 2020
 */

add_hook('ClientAreaPrimarySidebar', 1, function ($primarySidebar) {
  $domainid = $_REQUEST['id'];
  if (!$domainid || empty($domainid)) {
    $domainid = $_REQUEST['domainid'];
  }

  if (!is_null($primarySidebar->getChild('Domain Details Management'))) {
    $action = \App::get_req_var('action');
    $filename = \App::getCurrentFilename();

    $sidebar = $primarySidebar->getChild('Domain Details Management');
    $inDomainDetails = \App::getCurrentFilename() == 'clientarea' && $action == 'domaindetails' && !$customAction && !$modop;
    $legacyDomainService = new \WHMCS\Domains();
    $domaindata = $legacyDomainService->getDomainsDatabyID($domainid);

    $domainIsNotActive = !$legacyDomainService->isActive();

    // registrar and tld check
    if (substr($domaindata['domain'], -2) == 'id') {
      $linkPrefix = ($inDomainDetails ? '' : 'clientarea.php?action=domaindetails&id=' . $domainid);

      // OVERVIEW
      if (!($sidebar->getChild('Overview'))) {
        $sidebar->addChild(
          'overview',
          array(
            'name' => 'Overview',
            'label' => \Lang::trans('overview'),
            'uri' => $linkPrefix . '#tabOverview',
            'attributes' => array('dataToggleTab' => $inDomainDetails),
            'current' => $inDomainDetails,
            'order' => 10
          )
        );
      }

      // AUTO RENEW SETTINGS
      if (!($sidebar->getChild('Auto Renew Settings'))) {
        $sidebar->addChild(
          'autorenewsettings',
          array(
            'name' => 'Auto Renew Settings',
            'label' => \Lang::trans('domainsautorenew'),
            'uri' => $linkPrefix . '#tabAutorenew',
            'attributes' => array('dataToggleTab' => $inDomainDetails),
            'disabled' => $domainIsNotActive,
            'order' => 20
          )
        );
      }

      // MODIFY NAMESERVERS
      if (!($sidebar->getChild('Modify Nameservers'))) {
        $sidebar->addChild(
          'nameservers',
          array(
            'name' => 'Modify Nameservers',
            'label' => \Lang::trans('domainnameservers'),
            'uri' => $linkPrefix . '#tabNameservers',
            'attributes' => array('dataToggleTab' => $inDomainDetails),
            'disabled' => $domainIsNotActive,
            'order' => 30
          )
        );
      }

      // REGISTRAR LOCK STATUS
      if (!($sidebar->getChild('Registrar Lock Status'))) {
        $sidebar->addChild(
          'locking',
          array(
            'name' => 'Registrar Lock Status',
            'label' => \Lang::trans('domainregistrarlock'),
            'uri' => $linkPrefix . '#tabReglock',
            'attributes' => array(
              'dataToggleTab' => $inDomainDetails
            ),
            'disabled' => $domainIsNotActive,
            'order' => 40
          )
        );
      }

      // DOMAIN ADDONS
      if (!($sidebar->getChild('Domain Addons'))) {
        $sidebar->addChild(
          'addons',
          array(
            'name' => 'Domain Addons',
            'label' => \Lang::trans('clientareahostingaddons'),
            'uri' => $linkPrefix . '#tabAddons',
            'attributes' => array(
              'dataToggleTab' => $inDomainDetails,
              'class' => ($inDomainAddons ? 'active' : '')
            ),
            'disabled' => $domainIsNotActive,
            'order' => 70
          )
        );
      }

      // DOMAIN CONTACTS
      if (!($sidebar->getChild('Domain Contacts'))) {
        $sidebar->addChild(
          'addons',
          array(
            'name' => 'Domain Contacts',
            'label' => \Lang::trans('domaincontactinfo'),
            'uri' => 'clientarea.php?action=domaincontacts&domainid=' . $domainid,
            'current' => $action == 'domaincontacts',
            'disabled' => $domainIsNotActive,
            'order' => 80
          )
        );
      }

      // MANAGE PRIVATE NAMESERVERS
      if (!($sidebar->getChild('Manage Private Nameservers'))) {
        $sidebar->addChild(
          'privatens',
          array(
            'name' => 'Manage Private Nameservers',
            'label' => \Lang::trans('domainprivatenameservers'),
            'uri' => 'clientarea.php?action=domainregisterns&domainid=' . $domainid,
            'current' => $action == 'domainregisterns',
            'disabled' => $domainIsNotActive,
            'order' => 90
          )
        );
      }

      // GET EPP CODE
      if (!($sidebar->getChild('Get EPP Code'))) {
        $sidebar->addChild(
          'eppcode',
          array(
            'name' => 'Get EPP Code',
            'label' => \Lang::trans('domaingeteppcode'),
            'uri' => 'clientarea.php?action=domaingetepp&domainid=' . $domainid,
            'current' => $action == 'domaingetepp',
            'disabled' => $domainIsNotActive,
            'order' => 120
          )
        );
      }

      // DNS MANAGEMENT
      $sidebar->addChild(
        'dnsmanagement',
        array(
          'name' => 'DNS Management',
          'label' => 'DNS Management',
          'uri' => 'kilatdomain_dns.php?id=' . $domainid,
          'current' => $filename == 'kilatdomain_dns',
          'disabled' => $domainIsNotActive,
          'order' => 130,
        )
      );

      // DOMAIN FORWARDING
      $sidebar->addChild(
        'domainforwarding',
        array(
          'name' => 'Domain Forwarding',
          'label' => 'Domain Forwarding',
          'uri' => 'kilatdomain_df.php?id=' . $domainid,
          'current' => $filename == 'kilatdomain_df',
          'disabled' => $domainIsNotActive,
          'order' => 140,
        )
      );

      // DOMAIN DOCUMENTS
      $sidebar->addChild(
        'domaindocuments',
        array(
          'name' => 'Domain Documents',
          'label' => 'Domain Documents',
          'uri' => 'kilatdomain_document.php?id=' . $domainid,
          'current' => $filename == 'kilatdomain_document',
          'disabled' => false,
          'order' => 160,
        )
      );
    }
  }
});
