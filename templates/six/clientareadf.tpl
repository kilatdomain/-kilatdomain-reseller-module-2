{if $error}
  {include file="$template/includes/alert.tpl" type="error" msg=$error}
{/if}

{if $successful}
  {include file="$template/includes/alert.tpl" type="success" msg=$LANG.changessavedsuccessfully textcenter=true}
{/if}

<form class="form-horizontal" method="post" action="{$smarty.server.PHP_SELF}?id={$domainid}&amp;action=add" id="form_add">
</form>

<table class="table table-framed table-inf">
  <thead>
    <tr>
    <th>Type</th>
    <th>From <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Put @ as value to point your domain to root."></span></th>
    <th>Redirect Option</th>
    <th>Redirect to <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Please use this format for optimal use: [protocol]://[sld][tld]. e.g: https://example.com."></span></th>
    <th>Action</th>
    </tr>
  </thead>
  <tbody>
    {foreach from=$dfrecords item=dfrecord}
    <tr>
    <td>
      <input type="hidden" name="recid[]" value="{$dfrecord.id}" />
      <select name="type[]" class="form-control">
      <option value="301" {if $dfrecord.type eq "301"} selected="selected"{/if}>Permanent (301)</option>
      <option value="302" {if $dfrecord.type eq "302"} selected="selected"{/if}>Temporary (302)</option>
      </select>
    </td>
    <td><input type="text" name="origin_domain[]" class="form-control" value="{$dfrecord.origin_domain}" disabled /></td>
    <td>
      <select name="option" class="form-control" disabled>
      <option value="1" {if $dfrecord.option eq "1"} selected="selected"{/if}>Only redirect with www</option>
      <option value="2" {if $dfrecord.option eq "2"} selected="selected"{/if}>Redirect with or without www</option>
      <option value="3" {if $dfrecord.option eq "3"} selected="selected"{/if}>Do no redirect www</option>
      </select>
    </td>
    <td><input type="text" name="destination_domain[]" class="form-control" value="{$dfrecord.destination_domain}" required /></td>
    <td>
      <a href="{$smarty.server.PHP_SELF}?id={$domainid}&amp;recid={$dfrecord.id}&amp;action=rem" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');"><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
    </td>
    </tr>
    {/foreach}
    <tr>
      <td>
        <select name="type" form="form_add" class="form-control">
          <option value="301">Permanent (301)</option>
          <option value="302">Temporary (302)</option>
        </select>
      </td>
      <td>
        <input type="text" form="form_add" name="origin_domain" class="form-control" placeholder="@" required />
      </td>
      <td>
        <select name="option" form="form_add" class="form-control">
          <option value="1">Only redirect with www</option>
          <option value="2">Redirect with or without www</option>
          <option value="3">Do no redirect www</option>
        </select>
      </td>
      <td>
        <input type="text" form="form_add" name="destination_domain" class="form-control" placeholder="https://domain.id" required />
      </td>
      <td>
        <button type="submit" form="form_add" class="btn btn-success">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add
        </button>
      </td>
    </tr>
  </tbody>
</table>