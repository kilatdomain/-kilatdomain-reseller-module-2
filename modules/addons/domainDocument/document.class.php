<?php

namespace Modules\Addons\DomainDocument;

use Illuminate\Database\Capsule\Manager as Capsule;

require_once ROOTDIR . "/includes/registrarfunctions.php";
require_once ROOTDIR . "/modules/registrars/kilatdomain/config.php";

class Document
{
  const MODULENAME = 'domainDocument';
  const BASEURL = '?module=domainDocument';
  const UPLOADPATH = UPLOAD_PATH_ID_DOC;
  const TBLDOMAINS = 'tbldomains';
  const TBLINVOICES = 'tblinvoices';
  const TBLORDERS = 'tblorders';
  const TBLDOCUMENTS = 'mod_kilatdomain_documents';
  const REGISTRAR = 'kilatdomain';

  public function notification($message, $type = 'success')
  {
    $class = ($type == 'success') ? 'successbox' : 'errorbox';
    $title = ($type == 'success') ? 'Successfully' : 'Failed';
    return "
        <div class=" . $class . " style=\"margin: 30px 0 20px 0;\">
          <strong>
            <span class=\"title\">Changes Saved " . $title . "!</span>
          </strong>
          <br>" . $message . "
        </div>
      ";
  }

  public function create_table()
  {
    if (!Capsule::schema()->hasTable(Document::TBLDOCUMENTS)) {
      Capsule::schema()->create(Document::TBLDOCUMENTS, function ($table) {
        $table->increments('id');
        $table->integer('userid')->length(10);
        $table->integer('domainid')->length(10);
        $table->string('id_doc_storage_name')->length(50);
        $table->string('id_doc_type')->length(50);
        $table->string('le_doc_storage_name')->length(50);
        $table->string('le_doc_type')->length(50);
        $table->string('su_doc_storage_name')->length(50);
        $table->string('su_doc_type')->length(50);
        $table->dateTime('domain_registration_date');
        $table->dateTime('domain_approval_date');
        $table->string('reason');
        $table->integer('domain_status');
      });
    }
  }

  public function get_file($domainid, $document_type)
  {
    $document = Capsule::table(self::TBLDOCUMENTS)
      ->select('id_doc_storage_name', 'le_doc_storage_name', 'su_doc_storage_name', 'id_doc_type', 'le_doc_type', 'su_doc_type')
      ->where('domainid', $domainid)
      ->first();

    if ($document_type == 'identity') {
      return array(
        'document' => (!empty($document->id_doc_storage_name) ? $document->id_doc_storage_name : false),
        'type' => (!empty($document->id_doc_type) ? $document->id_doc_type : "")
      );
    } else if ($document_type == 'legality') {
      return array(
        'document' => (!empty($document->le_doc_storage_name) ? $document->le_doc_storage_name : false),
        'type' => (!empty($document->le_doc_type) ? $document->le_doc_type : "")
      );
    } else if ($document_type == 'additional') {
      return array(
        'document' => (!empty($document->su_doc_storage_name) ? $document->su_doc_storage_name : false),
        'type' => (!empty($document->su_doc_type) ? $document->su_doc_type : "")
      );
    } else {
      return $document;
    }

    return false;
  }

  public function save_file($data)
  {
    try {
      if (!empty($data['file']['name'])) {
        $ext = pathinfo($data['file']['name'], PATHINFO_EXTENSION);
        $mime = mime_content_type($data['file']['tmp_name']);
        if (in_array($mime, ['image/jpeg', 'image/png', 'application/pdf'])) {
          $filename = md5($data['userid'] . $data['domain'] . $data['action']) . "." . $ext;
          $subfolder = substr($filename, 0, 2);
          $full_path = Document::UPLOADPATH . $subfolder;

          if (!file_exists($full_path)) {
            mkdir($full_path, 0755, true);
          }

          if (!is_writeable($full_path)) {
            return ['status' => 'error', 'message' => 'Cannot write to destination file.'];
          }

          // upload to local
          $upload = move_uploaded_file($data['file']['tmp_name'], $full_path . '/' . $filename);

          // upload to api
          $params = array(
            'registrar' => Document::REGISTRAR,
            'domainid' => $data['domainid'],
            'action' => $data['action'],
            'type' => $data['type'],
            'full_path' => $full_path . '/' . $filename,
            'mime' => $mime
          );
          $upload_to_api = RegCallFunction($params, 'UploadDocument');

          $domainparts = explode(".", $domain, 2);

          $values = [];

          if ($data['action'] == 'identity') {
            $values['id_doc_storage_name'] = $filename;
            $values['id_doc_type'] = $data['type'];
          } else if ($data['action'] == 'legality') {
            $values['le_doc_storage_name'] = $filename;
            $values['le_doc_type'] = $data['type'];
          } else if ($data['action'] == 'additional') {
            $values['su_doc_storage_name'] = $filename;
            $values['su_doc_type'] = $data['type'];
          }

          $exist_file = Document::get_file($data['domainid'], 'all');

          if ($exist_file) {
            // update
            $query = Capsule::table(Document::TBLDOCUMENTS)
              ->where('domainid', $data['domainid'])
              ->update($values);
          } else {
            // insert
            $values['userid'] = $data['userid'];
            $values['domainid'] = $data['domainid'];
            $values['domain_registration_date'] = $data['registration_date'];
            $values['domain_status'] = 2;

            $query = Capsule::table(Document::TBLDOCUMENTS)
              ->insert($values);
          }
          return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => 'Invalid files.'];
      }
      return ['status' => 'error', 'message' => 'Files was empty.'];
    } catch (Exception $e) {
      return ['status' => 'error', 'message' => $e->getMessage()];
    }
  }

  public function download_file($file)
  {
    if (file_exists($file)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=' . basename($file));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
      header('Content-Length: ' . filesize($file));
      ob_clean();
      flush();
      readfile($file);
      exit;
    }
  }

  public function update_status($data)
  {
    try {
      $values = [];
      $document = Capsule::table(self::TBLDOMAINS)
        ->select(self::TBLDOMAINS . '.userid', 'domain', 'type', 'registrationperiod', 'transfersecret')
        ->where(self::TBLDOMAINS . '.id', $data['id'])
        ->leftJoin('tblorders', 'tbldomains.orderid', '=', 'tblorders.id')
        ->first();

      $params = array(
        'userid' => $document->userid,
        'domainid' => $data['id'],
        'regpriod' => $document->registrationperiod,
        'registrar' => 'kilatdomain',
        'regtype' => $document->type
      );

      $domain = $document->domain;
      $domainparts = explode(".", $domain, 2);
      $params['sld'] = $domainparts[0];
      $params['tld'] = $domainparts[1];


      if (isset($data['status']) && !empty($data['status'])) {
        if ($data['status'] == 3) {
          $result = false;

          if ($document->type == 'Register') {
            $result = RegCallFunction($params, 'RegisterDomain');
          } else if ($document->type == 'Transfer') {
            $params['transfersecret'] = $document->transfersecret;
            $result = RegCallFunction($params, 'TransferDomain');
          }

          // upload
          $identity_file = Document::get_file($data['id'], 'identity');
          $legality_file = Document::get_file($data['id'], 'legality');
          $additional_file = Document::get_file($data['id'], 'additional');

          if ($identity_file) {
            $subfolder = substr($identity_file['document'], 0, 2);
            $file = Document::UPLOADPATH .  $subfolder . '/' . $identity_file['document'];
            $mime = mime_content_type($file);
            $params = array(
              'registrar' => Document::REGISTRAR,
              'domainid' => $data['id'],
              'action' => 'identity',
              'type' => $identity_file['type'],
              'full_path' => $file,
              'mime' => $mime,
              'do_active' => true,
            );

            RegCallFunction($params, 'UploadDocument');
          }

          if ($legality_file) {
            $subfolder = substr($legality_file['document'], 0, 2);
            $file = Document::UPLOADPATH .  $subfolder . '/' . $legality_file['document'];
            $mime = mime_content_type($file);
            $params = array(
              'registrar' => Document::REGISTRAR,
              'domainid' => $data['id'],
              'action' => 'legality',
              'type' => $legality_file['type'],
              'full_path' => $file,
              'mime' => $mime,
              'do_active' => true,
            );

            RegCallFunction($params, 'UploadDocument');
          }

          if ($additional_file) {
            $subfolder = substr($additional_file['document'], 0, 2);
            $file = Document::UPLOADPATH .  $subfolder . '/' . $additional_file['document'];
            $mime = mime_content_type($file);
            $params = array(
              'registrar' => Document::REGISTRAR,
              'domainid' => $data['id'],
              'action' => 'additional',
              'type' => $additional_file['type'],
              'full_path' => $file,
              'mime' => $mime,
              'do_active' => true,
            );

            RegCallFunction($params, 'UploadDocument');
          }

          if (($result && !$result['error']) || ($result && $result['error'] == 'PANDI/RegisterDomain: Domain exist.')) {
            $values['domain_approval_date'] = date('Y-m-d');

            // update domain
            $query = Capsule::table(self::TBLDOMAINS)
              ->where('id', $data['id'])
              ->update([
                'registrar' => 'kilatdomain',
                'status' => 'Active'
              ]);
          } else {
            throw new \Exception($result['error']);
          }
        }

        // update table
        $values['domain_status'] = $data['status'];

        $query = Capsule::table(self::TBLDOCUMENTS)
          ->where('domainid', $data['id'])
          ->update($values);

        return ['status' => 'success'];
      }
      throw new \Exception("Status is empty.");
    } catch (\Exception $e) {
      return ['status' => 'error', 'message' => $e->getMessage()];
    }
  }

  public function renew_transfer($data)
  {
    try {
      if (!empty($data['transfersecret'])) {
        $values = [];
        $document = Capsule::table(self::TBLDOMAINS)
          ->select(self::TBLDOMAINS . '.userid', 'domain', 'type', 'registrationperiod', 'transfersecret')
          ->where(self::TBLDOMAINS . '.id', $data['id'])
          ->leftJoin('tblorders', 'tbldomains.orderid', '=', 'tblorders.id')
          ->first();

        $params = array(
          'userid' => $document->userid,
          'domainid' => $data['id'],
          'regpriod' => $document->registrationperiod,
          'registrar' => Document::REGISTRAR,
          'regtype' => $document->type,
          'transfersecret' => $data['transfersecret']
        );

        $domain = $document->domain;
        $domainparts = explode(".", $domain, 2);
        $params['sld'] = $domainparts[0];
        $params['tld'] = $domainparts[1];


        $result = RegTransferDomain($params);

        if ($result && !$result['error']) {
          $query = Capsule::table(self::TBLDOCUMENTS)
            ->where("domain_approval_date", $current_date)
            ->where("domain_status", 3)
            ->update(["domainid" => $data['id']]);

          # Check domain status, if 'Pending Transfer' set it to 'Active'.
          $query = Capsule::table(self::TBLDOMAINS)
            ->where("id", $data['id'])
            ->where("status", "Pending Transfer")
            ->update(["status" => "Active"]);

          return ['status' => 'success'];
        } else {
          throw new \Exception($result['error']);
        }
      } else {
        throw new \Exception("EPP Code is empty.");
      }
    } catch (\Exception $e) {
      return ['status' => 'error', 'message' => $e->getMessage()];
    }
  }
}
