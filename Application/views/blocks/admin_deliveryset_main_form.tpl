[{$smarty.block.parent}]

[{if $oxid != "-1"}]
<tr>
    <td class="edittext">
        [{oxmultilang ident="GW_DELIVERY_SET_INFO_FIELDS"}]
    </td>
    <td class="edittext">
        <textarea style="" rows="5" placeholder="oxfon
oxmobfon
oxstateid
..." class="txtfield" name=editval[gw_delivery_mandatory_userinfo_fields] [{$readonly}]>[{$edit->oxdeliveryset__gw_delivery_mandatory_userinfo_fields->value}]</textarea>
        [{oxinputhelp ident="HELP_GW_DELIVERY_SET_INFO_FIELDS"}]
    </td>
</tr>
[{/if}]

