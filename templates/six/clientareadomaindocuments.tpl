{include file="$template/includes/alert.tpl" type="info" msg=$message}

{if $error}
  {include file="$template/includes/alert.tpl" type="error" msg=$error}
{/if}

{if $successful}
  {include file="$template/includes/alert.tpl" type="success" msg=$LANG.changessavedsuccessfully textcenter=true}
{/if}

<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="{$smarty.server.PHP_SELF}?id={$domainid}&amp;action=identity">

  <h4>Identity Document</h4>

  <div class="form-group">
    <label for="type_id" class="col-xs-4 control-label">Document Type</label>
    <div class="col-xs-6 col-sm-5">
      <select name="type_id" class="form-control" id="type_id">
        <option value="KTP" {if $identity_type eq 'KTP'}selected{/if}>KTP</option>
        <option value="PASSPORT" {if $identity_type eq 'PASSPORT'}selected{/if}>PASSPORT</option>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label for="file_id" class="col-xs-4 control-label">File <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Allowed file extensions: .jpg, .jpeg, .png, and .pdf."></span></label>
    <div class="col-xs-6 col-sm-5">
      <input type="file" class="form-control-file" id="file_id" name="file_id" />
    </div>
  </div>

  <div class="form-group">
    <label class="col-xs-4 control-label">Current file </label>
    <div class="col-xs-6 col-sm-5" style="padding-top: 7px;">
      {if $identity_document}
        <a href="{$smarty.server.PHP_SELF}?id={$domainid}&amp;&amp;action=download&amp;name={$identity_document}">Download</a>
      {else}
        <p>No file have been uploaded.</p>
      {/if}
    </div>
  </div>

  <p class="text-center">
    <input type="submit" value="{$LANG.clientareasavechanges}" class="btn btn-primary" />
  </p>

</form>

<div style="padding-top:40px"></div>

<form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="{$smarty.server.PHP_SELF}?id={$domainid}&amp;action=legality">
  
  <h4>Legality Document</h4>

  <div class="form-group">
    <label for="type_ld" class="col-xs-4 control-label">Document Type</label>
    <div class="col-xs-6 col-sm-5">
      <select name="type_ld" class="form-control" id="type_ld">
        <option value="SIUP" {if $legality_type eq 'SIUP'}selected{/if}>SIUP</option>
        <option value="TDA" {if $legality_type eq 'TDA'}selected{/if}>TDA</option>
        <option value="AKTA" {if $legality_type eq 'AKTA'}selected{/if}>AKTA</option>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label for="file_ld" class="col-xs-4 control-label">File <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Allowed file extensions: .jpg, .jpeg, .png, and .pdf."></span></label>
    <div class="col-xs-6 col-sm-5">
      <input type="file" class="form-control-file" id="file_ld" name="file_ld" />
    </div>
  </div>

  <div class="form-group">
    <label class="col-xs-4 control-label">Current file </label>
    <div class="col-xs-6 col-sm-5" style="padding-top: 7px;">
      {if $legality_document}
        <a href="{$smarty.server.PHP_SELF}?id={$domainid}&amp;&amp;action=download&amp;name={$legality_document}">Download</a>     
      {else}
        <p>No file have been uploaded.</p>
      {/if}
    </div>
  </div>

  <p class="text-center">
    <input type="submit" value="{$LANG.clientareasavechanges}" class="btn btn-primary" />
  </p>

</form>

<div style="padding-top:40px"></div>

<form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="{$smarty.server.PHP_SELF}?id={$domainid}&amp;action=additional">
  
  <h4>Additional Document</h4>

  <div class="form-group">
    <label for="type_ad" class="col-xs-4 control-label">Document Type</label>
    <div class="col-xs-6 col-sm-5">
      <select name="type_ad" class="form-control" id="type_ad">
        <option value="Surat Kuasa" {if $additional_type eq 'Surat Kuasa'}selected{/if}>Surat Kuasa</option>
        <option value="Lainnya" {if $additional_type eq 'Lainnya'}selected{/if}>Lainnya</option>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label for="file_ad" class="col-xs-4 control-label">File <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Allowed file extensions: .jpg, .jpeg, .png, and .pdf."></span></label>
    <div class="col-xs-6 col-sm-5">
      <input type="file" class="form-control-file" id="file_ad" name="file_ad" />
    </div>
  </div>

  <div class="form-group">
    <label class="col-xs-4 control-label">Current file </label>
    <div class="col-xs-6 col-sm-5" style="padding-top: 7px;">
      {if $additional_document}
        <a href="{$smarty.server.PHP_SELF}?id={$domainid}&amp;&amp;action=download&amp;name={$additional_document}">Download</a>
      {else}
        <p>No file have been uploaded.</p>
      {/if}
    </div>
  </div>

  <p class="text-center">
    <input type="submit" value="{$LANG.clientareasavechanges}" class="btn btn-primary" />
  </p>

</form>
