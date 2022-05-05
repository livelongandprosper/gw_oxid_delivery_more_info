[{$smarty.block.parent}]

[{assign var="hasDeliveryAddress" value=false}]
[{assign var="delAddress" value=$oView->getDelAddress()}]
[{assign var="allDeliverySets" value=$oView->getAllSets()}]
[{foreach key=sShipID from=$oView->getAllSets() item=oShippingSet}]
    [{assign var="shippingSetMandatoryFields" value=$oShippingSet->getAdditionalMandatoryFields()}]
    [{if $oShippingSet->blSelected && count($shippingSetMandatoryFields) > 0}]
        <div>
            <span class="gw-delivery-more-info-needed"><strong>[{oxmultilang ident="GW_WE_NEED_THIS_INFO_1"|cat:$mandatoryField}]
            [{foreach key=sShipID from=$oView->getAllSets() item=oShippingSet name=ShipSetSelect}]
                [{if $oShippingSet->blSelected}][{$oShippingSet->oxdeliveryset__oxtitle->value}][{/if}]
            [{/foreach}]
            [{oxmultilang ident="GW_WE_NEED_THIS_INFO_2"|cat:$mandatoryField}]
            </strong></span>

            [{foreach from=$shippingSetMandatoryFields item="mandatoryField"}]
                [{if $mandatoryField == 'oxstateid'}]
                    [{assign var="objectPropertyNameDelAddress" value="oxaddress__oxstateid"}]
                    [{assign var="objectPropertyNameBillingAddress" value="oxuser__oxstateid"}]
                    [{assign var="blCountrySelected" value=false}]
                    [{assign var="hasStates" value=false}]
                    [{assign var="states" value=null}]


                    [{foreach from=$oViewConf->getCountryList() item=country key=country_id}]
                        [{assign var="sCountrySelect" value=""}]
                        [{if !$blCountrySelected}]
                            [{if $delAddress}]
                                [{if (isset($delAddress->oxaddress__oxcountryid->value) && $delAddress->oxaddress__oxcountryid->value == $country->oxcountry__oxid->value) ||
                                (!isset($delAddress->oxuser__oxcountryid->value) && $delAddress->oxaddress__oxcountryid->value == $country->oxcountry__oxid->value)}]
                                    [{assign var="blCountrySelected" value=true}]
                                    [{assign var="sCountrySelect" value="selected"}]
                                [{/if}]
                            [{else}]
                                [{if (isset($invadr.oxuser__oxcountryid) && $invadr.oxuser__oxcountryid == $country->oxcountry__oxid->value) ||
                                (!isset($invadr.oxuser__oxcountryid) && $oxcmp_user->oxuser__oxcountryid->value == $country->oxcountry__oxid->value)}]
                                    [{assign var="blCountrySelected" value=true}]
                                    [{assign var="sCountrySelect" value="selected"}]
                                [{/if}]
                            [{/if}]
                            [{if $blCountrySelected}]
                                [{*$country->oxcountry__oxtitle->value*}]
                                [{if count($country->getStates()) > 0}]
                                    [{assign var="states" value=$country->getStates()}]
                                    [{assign var="hasStates" value=true}]
                                [{/if}]
                            [{/if}]
                        [{/if}]
                    [{/foreach}]

                    [{if $hasStates}]
                        <div class="gw-delivery-form-group">
                            <select name="additionalDeliveryFields[[{if $delAddress}][{$objectPropertyNameDelAddress}][{else}][{$objectPropertyNameBillingAddress}][{/if}]]]" [{if $class}]class="[{$class}]"[{/if}]>
                                <option value="">[{oxmultilang ident="PLEASE_SELECT_STATE"}]</option>
                                [{foreach from=$states item="state"}]
                                    <option[{if $delAddress}]
                                                [{if $smarty.post.additionalDeliveryFields.$objectPropertyNameDelAddress}]
                                                    [{if $smarty.post.additionalDeliveryFields.$objectPropertyNameDelAddress == $state->oxstates__oxid->value}]
                                                        selected="selected"
                                                    [{/if}]
                                                [{else}]
                                                    [{if $delAddress->$objectPropertyNameDelAddress->value == $state->oxstates__oxid->value}]
                                                        selected="selected"
                                                    [{/if}]
                                                [{/if}]
                                           [{else}]
                                                [{if $smarty.post.additionalDeliveryFields.$objectPropertyNameBillingAddress}]
                                                    [{if $smarty.post.additionalDeliveryFields.$objectPropertyNameBillingAddress == $state->oxstates__oxid->value}]
                                                        selected="selected"
                                                    [{/if}]
                                                [{else}]
                                                    [{if $oxcmp_user->$objectPropertyNameBillingAddress->value == $state->oxstates__oxid->value}]
                                                        selected="selected"
                                                    [{/if}]
                                                [{/if}]
                                           [{/if}]
                                            value="[{$state->oxstates__oxid->value}]">
                                        [{$state->oxstates__oxtitle->value}]
                                    </option>
                                [{/foreach}]
                            </select>
                        </div>
                    [{/if}]
                [{else}]
                    [{assign var="objectPropertyNameDelAddress" value="oxaddress__"|cat:$mandatoryField}]
                    [{assign var="objectPropertyNameBillingAddress" value="oxuser__"|cat:$mandatoryField}]
                        <div class="gw-delivery-form-group">
                            <div class="gw-float-label">
                                <input
                                    class="form-control"
                                    type="text"
                                    name="additionalDeliveryFields[[{if $delAddress}][{$objectPropertyNameDelAddress}][{else}][{$objectPropertyNameBillingAddress}][{/if}]]"
                                    value="[{strip}][{if $delAddress}]
                                                [{if $smarty.post.additionalDeliveryFields.$objectPropertyNameDelAddress}]
                                                    [{$smarty.post.additionalDeliveryFields.$objectPropertyNameDelAddress}]
                                                [{else}]
                                                    [{if $delAddress->$objectPropertyNameDelAddress->value}]
                                                        [{$delAddress->$objectPropertyNameDelAddress->value}]
                                                    [{else}]
                                                        [{$oxcmp_user->$objectPropertyNameBillingAddress->value}]
                                                    [{/if}]
                                                [{/if}]
                                           [{else}]
                                                [{if $smarty.post.additionalDeliveryFields.$objectPropertyNameBillingAddress}]
                                                    [{$smarty.post.additionalDeliveryFields.$objectPropertyNameBillingAddress}]
                                                [{else}]
                                                    [{$oxcmp_user->$objectPropertyNameBillingAddress->value}]
                                                [{/if}]
                                            [{/if}][{/strip}]"
                                >
                                <label class="control-label col-lg-3" for="">
                                    [{oxmultilang ident="GW_DELVIERY_MORE_INFO_DELIVERY_"|cat:$mandatoryField}]
                                </label>
                            </div>
                        </div>
                [{/if}]
            [{/foreach}]
        </div>
    [{/if}]
[{/foreach}]
