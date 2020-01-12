{include file="$template/includes/alert.tpl" type="info" msg=$LANG.domaindnsmanagementdesc}

{if $error}
  {include file="$template/includes/alert.tpl" type="error" msg=$error}
{/if}

{if $successful}
  {include file="$template/includes/alert.tpl" type="success" msg=$LANG.changessavedsuccessfully textcenter=true}
{/if}

<form method="post" action="{$smarty.server.PHP_SELF}?id={$domainid}&amp;action=save" id="form_save">
</form>

<form method="post" action="{$smarty.server.PHP_SELF}?id={$domainid}&amp;action=add" id="form_add">
</form>

<table class="table table-framed table-inf">
  <thead>
    <tr>
      <th width="20%">{$LANG.domaindnshostname} <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Put @ as hostname value to point your domain to root."></span></th>
      <th width="15%">{$LANG.domaindnsrecordtype}</th>
      <th width="20%">{$LANG.domaindnsaddress}</th>
      <th width="10%">TTL <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="TTL for A record must be greater than or equal to 3600."></span></th>
      <th width="10%">{$LANG.domaindnspriority} <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Priority Record for MX Only."></span></th>
      <th width="15%">Action</th>
    </tr>
  </thead>
  <tbody>
    {foreach from=$dnsrecords item=dnsrecord}
    <tr>
      <td>
        <input type="hidden" name="dnsrecid[]" form="form_save" value="{$dnsrecord.recid}" />
        <input type="text" form="form_save" name="dnsrecordhost[]" value="{$dnsrecord.hostname}" class="form-control input-sm" required />
      </td>
      <td>
        <select name="dnsrecordtype[]" form="form_save" class="form-control">
          <option value="A"{if $dnsrecord.type eq "A"} selected="selected"{/if}>A (Address)</option>
          <option value="CNAME"{if $dnsrecord.type eq "CNAME"} selected="selected"{/if}>CNAME (Alias)</option>
          <option value="MX"{if $dnsrecord.type eq "MX"} selected="selected"{/if}>MX (Mail)</option>
          <option value="TXT"{if $dnsrecord.type eq "TXT"} selected="selected"{/if}>SPF (txt)</option>
        </select>
      </td>
      <td>
        <input type="text" name="dnsrecordaddress[]" form="form_save" value="{$dnsrecord.address}" class="form-control" required />
      </td>
      <td>
        <input type="text" name="dnsrecordttl[]" form="form_save" value="{$dnsrecord.ttl}" class="form-control" required />
      </td>
      <td>
        {if $dnsrecord.type eq "MX"}
          <input type="text" form="form_save" name="dnsrecordpriority[]" value="{$dnsrecord.priority}" class="form-control" />
        {else}
          <input type="hidden" form="form_save" value="N/A" />{$LANG.domainregnotavailable}
        {/if}
      </td>
      <td>
        <div class="btn-toolbar" role="toolbar">
          <div class="btn-group">
            <a href="{$smarty.server.PHP_SELF}?id={$domainid}&amp;action=rem&amp;recid={$dnsrecord.recid}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">
              <span aria-hidden="true" class="glyphicon glyphicon-trash"></span>
            </a>

            <!--<button type="submit" class="btn btn-primary" aria-label="Center Align" form="form_save">
              <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
            </button>-->
          </div>
        </div>
      </td>
    </tr>
    {/foreach}

    <tr>
      <td>
        <input type="text" form="form_add" name="recordhost" class="form-control" required /></td>
      <td>
        <select name="recordtype" form="form_add" class="form-control">
          <option value="A">A (Address)</option>
          <option value="MX">MX (Mail)</option>
          <option value="CNAME">CNAME (Alias)</option>
          <option value="TXT">SPF (txt)</option>
        </select>
      </td>
      <td>
        <input type="text" form="form_add" name="recordaddress" class="form-control" required />
      </td>
      <td>
        <input type="text" form="form_add" name="recordttl" class="form-control" required />
      </td>
      <td>
        <input type="text" form="form_add" name="recordpriority" class="form-control" />
      </td>
      <td>
       <button type="submit" class="btn btn-success" form="form_add">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add
        </button>
      </td>
    </tr>
  </tbody>
</table>
