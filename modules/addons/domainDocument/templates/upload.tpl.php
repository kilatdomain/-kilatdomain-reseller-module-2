<?php

use Modules\Addons\DomainDocument\Document as Document;

?>

<div class="modal fade" id="modal-identity" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Identity Document</h4>
      </div>
      <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="<?= Document::BASEURL ?>&amp;action=identity">
        <div class="modal-body">
          <input type="hidden" name="domainid" class="domainid">
          <input type="hidden" name="userid" class="userid">
          <input type="hidden" name="domain" class="domain">
          <input type="hidden" name="registration_date" class="registration_date">

          <div class="form-group">
            <label for="type_id" class="col-xs-4 control-label">Document Type</label>
            <div class="col-xs-6 col-sm-5">
              <select name="type_id" class="form-control" id="type_id">
                <option value="KTP">KTP</option>
                <option value="PASSPORT">PASSPORT</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="file_id" class="col-xs-4 control-label">File <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Allowed file extensions: .jpg, .jpeg, .png, and .pdf."></span></label>
            <div class="col-xs-6 col-sm-5">
              <input type="file" class="form-control-file" id="file_id" name="file_id" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="modal-legality" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Legality Document</h4>
      </div>
      <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="<?= Document::BASEURL ?>&amp;action=legality">
        <div class="modal-body">
          <input type="hidden" name="domainid" class="domainid">

          <div class="form-group">
            <label for="type_ld" class="col-xs-4 control-label">Document Type</label>
            <div class="col-xs-6 col-sm-5">
              <select name="type_ld" class="form-control" id="type_ld">
                <option value="SIUP">SIUP</option>
                <option value="TDA">TDA</option>
                <option value="AKTA">AKTA</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="file_ld" class="col-xs-4 control-label">File <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Allowed file extensions: .jpg, .jpeg, .png, and .pdf."></span></label>
            <div class="col-xs-6 col-sm-5">
              <input type="file" class="form-control-file" id="file_ld" name="file_ld" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="modal-additional" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Additional Document</h4>
      </div>
      <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="<?= Document::BASEURL ?>&amp;action=additional">
        <div class="modal-body">
          <input type="hidden" name="domainid" class="domainid">

          <div class="form-group">
            <label for="type_ad" class="col-xs-4 control-label">Document Type</label>
            <div class="col-xs-6 col-sm-5">
              <select name="type_ad" class="form-control" id="type_ad">
                <option value="Surat Kuasa">Surat Kuasa</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="file_ad" class="col-xs-4 control-label">File <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Allowed file extensions: .jpg, .jpeg, .png, and .pdf."></span></label>
            <div class="col-xs-6 col-sm-5">
              <input type="file" class="form-control-file" id="file_ad" name="file_ad" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).on("click", ".upload-document", function() {
    var domainid = $(this).data('domainid');
    var domain = $(this).data('domain');
    var userid = $(this).data('userid');
    var registration_date = $(this).data('registration_date');

    $(".modal-body .domainid").val(domainid);
    $(".modal-body .domain").val(domain);
    $(".modal-body .userid").val(userid);
    $(".modal-body .registration_date").val(registration_date);
  });
</script>