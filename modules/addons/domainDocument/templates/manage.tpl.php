<?php

use Modules\Addons\DomainDocument\Document as Document;

?>

<div class="modal fade" id="modal-manage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Domain Status</h4>
      </div>
      <form class="form-horizontal" role="form" method="post" action="<?= Document::BASEURL ?>&amp;action=manage">
        <div class="modal-body">
          <input type="hidden" name="id" class="id">

          <div class="form-group">
            <label for="domain" class="col-xs-4 control-label">Domain</label>
            <div class="col-xs-6 col-sm-5">
              <input type="text" id="domain" disabled name="domain" class="domain form-control" class="form-control">
            </div>
          </div>

          <div class="form-group">
            <label for="status" class="col-xs-4 control-label">Status</label>
            <div class="col-xs-6 col-sm-5">
              <select name="status" class="form-control" id="status">
                <option value="2">Review</option>
                <option value="3">Approve</option>
              </select>
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
  $(document).on("click", ".update-status", function() {
    var id = $(this).data('id');
    var domain = $(this).data('domain');

    $(".modal-body .id").val(id);
    $(".modal-body .domain").val(domain);
  });
</script>