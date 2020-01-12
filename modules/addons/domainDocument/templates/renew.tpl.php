<?php

use Modules\Addons\DomainDocument\Document as Document;

?>

<div class="modal fade" id="modal-renew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Renew Domain</h4>
      </div>
      <form class="form-horizontal" role="form" method="post" action="<?= Document::BASEURL ?>&amp;action=renew">
        <div class="modal-body">
          <input type="hidden" name="id" class="id">

          <div class="form-group">
            <label for="domain" class="col-xs-4 control-label">Domain</label>
            <div class="col-xs-6 col-sm-5">
              <input type="text" id="domain" disabled name="domain" class="domain form-control" class="form-control">
            </div>
          </div>

          <div class="form-group">
            <label for="epp_code" class="col-xs-4 control-label">EPP Code</label>
            <div class="col-xs-6 col-sm-5">
              <input type="test" name="epp_code" class="form-control" id="epp_code">
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
  $(document).on("click", ".renew", function() {
    var id = $(this).data('id');
    var domain = $(this).data('domain');

    $(".modal-body .id").val(id);
    $(".modal-body .domain").val(domain);
  });
</script>