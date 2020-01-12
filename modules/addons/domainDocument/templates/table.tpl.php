<?php

use Modules\Addons\DomainDocument\Document as Document;

use Illuminate\Database\Capsule\Manager as Capsule;

use INFINYS_table;

$where = Document::TBLDOMAINS . ".domain like '%.id' and (id_doc_storage_name != '' or le_doc_storage_name != '' or su_doc_storage_name != '')";

$result = Capsule::table(Document::TBLDOMAINS)
  ->select(Capsule::raw('count(*) as domains_count'))
  ->leftJoin(Document::TBLDOCUMENTS, Document::TBLDOMAINS . '.id', '=', Document::TBLDOCUMENTS . '.domainid')
  ->whereRaw($where)
  ->first();

$num_rows = $result->domains_count;

$pages = new INFINYS_table($num_rows, 9, array(15, 25, 50, 100, 250, 'All'), []);

echo "<div style=\"float:right\">" . $pages->displayJumpMenu() . $pages->displayItemsPerPage() . "</div>
      <div style=\"float:left\">" . $pages->displayTableFooter() . "</div>
      <div style=\"clear:both\"></div>
      <div style=\"margin-top:10px\"></div>";

$result = Capsule::table(Document::TBLDOMAINS)
  ->select(Capsule::raw(
    Document::TBLDOMAINS . '.*,' .
      Document::TBLINVOICES . '.status,' .
      Document::TBLDOCUMENTS . '.id_doc_storage_name,' .
      Document::TBLDOCUMENTS . '.id_doc_type,' .
      Document::TBLDOCUMENTS . '.le_doc_storage_name,' .
      Document::TBLDOCUMENTS . '.le_doc_type,' .
      Document::TBLDOCUMENTS . '.su_doc_storage_name,' .
      Document::TBLDOCUMENTS . '.su_doc_type,' .
      Document::TBLDOCUMENTS . '.domain_approval_date,' .
      Document::TBLDOCUMENTS . '.domain_status'
  ))
  ->leftJoin(Document::TBLDOCUMENTS, 'tbldomains.id', '=', Document::TBLDOCUMENTS . '.domainid')
  ->leftJoin(Document::TBLORDERS, Document::TBLDOMAINS . '.orderid', '=', Document::TBLORDERS . '.id')
  ->leftJoin(Document::TBLINVOICES, Document::TBLORDERS . '.invoiceid', '=', Document::TBLINVOICES . '.id')
  ->orderBy('id', 'desc')
  ->skip($pages->limit_start)->take($pages->limit_end)
  ->whereRaw($where)
  ->get();

$pages->setTableHeader(array("Domain", "Identity Document", "Legality Document", "Other Document", "Registration Date", "Payment", "Domain Status", "Action"));

foreach ($result as $row) {

  $pages->addRow(array(
    "<a class=\"link\" href=\"clientsdomains.php?userid=" . $row->userid . "&id=" . $row->id . "\">" . $row->domain . "</a>",

    ($row->id_doc_storage_name) ?
      '<div class="btn-group">
        <a href="' . Document::BASEURL . '&amp;action=download&amp;name=' . $row->id_doc_storage_name . '" class="btn btn-default btn-sm">Download (' . $row->id_doc_type . ')</a>
        
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
          <li><a class="upload-document" data-toggle="modal" data-target="#modal-identity" data-domainid="' . $row->id . '">Reupload</a></li>
        </ul>
      </div>' :
      '<a class="upload-document btn btn-default btn-sm" data-toggle="modal" data-target="#modal-identity" data-domainid="' . $row->id . ' data-domain="' . $row->domain . '" data-date="' . $row->registrationdate . ' data-userid="' . $row->userid . '">Upload</a>',


    ($row->le_doc_storage_name) ?
      '<div class="btn-group">
        <a href="' . Document::BASEURL . '&amp;action=download&amp;name=' . $row->le_doc_storage_name . '" class="btn btn-default btn-sm">Download (' . $row->le_doc_type . ')</a>
        
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
          <li><a class="upload-document" href="#" data-toggle="modal" data-target="#modal-legality" data-domainid="' . $row->id . '">Reupload</a></li>
        </ul>
      </div>' :
      '<a class="upload-document btn btn-default btn-sm" data-toggle="modal" data-target="#modal-legality" data-domainid="' . $row->id . ' data-domain="' . $row->domain . '" data-date="' . $row->registrationdate . ' data-userid="' . $row->userid . '">Upload</a>',

    ($row->su_doc_storage_name) ?
      '<div class="btn-group">
        <a href="' . Document::BASEURL . '&amp;action=download&amp;name=' . $row->su_doc_storage_name . '" class="btn btn-default btn-sm">Download (' . $row->su_doc_type . ')</a>
        
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
          <li><a class="upload-document" data-toggle="modal" data-target="#modal-additional" data-domainid="' . $row->id . '">Reupload</a></li>
        </ul>
      </div>' :
      '<a class="upload-document btn btn-default btn-sm" data-toggle="modal" data-target="#modal-additional" data-domainid="' . $row->id . ' data-domain="' . $row->domain . '" data-date="' . $row->registrationdate . ' data-userid="' . $row->userid . '">Upload</a>',

    $row->registrationdate,

    $row->status == 'Paid' ? "<span class=\"label active\">" . $row->status . "</span>" : "<span class=\"label cancelled\">" . $row->status . "</span>",

    $row->domain_status == 3 ? "<span class=\"label active\">Approved</span>" : ($row->domain_status == 2 ? "<span class=\"label pending\">Review</span>" : ($row->domain_status == 1 ? "<span class=\"label closed\">Rejected</span>" : "")),

    '<div class="btn-group">
        <a class="update-status btn btn-default btn-sm" data-toggle="modal" data-target="#modal-manage" data-domain="' . $row->domain . '" data-id="' . $row->id . '">Update Status</a>
        
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
          <li><a class="renew" data-toggle="modal" data-target="#modal-renew" data-domain="' . $row->domain . '" data-id="' . $row->id . '">Renew via transfer</a></li>
        </ul>
      </div>'
  ));
}

echo $pages->displayTable();
echo "<div style=\"text-align:center\">" . $pages->displayPages() . "</div>";

$baseurl = Document::BASEURL;

include 'upload.tpl.php';
include 'manage.tpl.php';
include 'renew.tpl.php';
