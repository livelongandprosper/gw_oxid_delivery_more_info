[{$smarty.block.parent}]

[{*include file="page/checkout/inc/additional_shipping_info_form_elements.tpl"*}]

[{assign var="delAddress" value=$oView->getDelAddress()}]
[{assign var="allDeliverySets" value=$oView->getAllSets()}]
[{foreach key=sShipID from=$oView->getAllSets() item=oShippingSet}]
    [{if $oShippingSet->blSelected}]
        <div class="gw-additional-delivery-info">
            [{include file="page/checkout/inc/additional_shipping_info_form_elements.tpl" oShippingSet=$oShippingSet delAddress=$delAddress}]
        </div>
    [{/if}]
[{/foreach}]
