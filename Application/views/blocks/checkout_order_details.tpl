[{if !$oView->isAdditionalShippingDataOK()}]
    <form action="[{$oViewConf->getSslSelfLink()}]" method="post" class="gw-additional-delivery-info">
        <div class="hidden">
            [{$oViewConf->getHiddenSid()}]
            <input type="hidden" name="cl" value="order">
            <input type="hidden" name="fnc" value="updateAdditionalInfo">
        </div>
        [{include file="page/checkout/inc/additional_shipping_info_form_elements.tpl" oShippingSet=$oView->getShipSet() delAddress=$oView->getDelAddress()}]
        <button type="submit"  class="btn btn-primary submitButton">[{oxmultilang ident="GW_UPDATE_ADDITIONAL_MANDATORY_FIELDS"}]</button>
    </form>
[{/if}]
[{$smarty.block.parent}]
