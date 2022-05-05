# Module for OXID eShop that allows you to request more infos from a user depending on the chosen delivery method
This module makes it possible to define additional mandatory fields when selecting a shipping method.

## Install
- This module has to be put to the folder
\[shop root\]**/modules/gw/gw_oxid_delivery_more_info/**

- You also have to create a file
\[shop root\]/modules/gw/**vendormetadata.php**

- add content in composer_add_to_root.json to your global composer.json file and call composer dump-autoload
- in TPL Application/views/zehaberlin/tpl/page/checkout/payment.tpl you have to add the Smarty block change_payment_form inside the form with the id payment
```
    <form action="[{$oViewConf->getSslSelfLink()}]" class="form-horizontal js-oxValidate payment-methods" id="payment" name="order" method="post" novalidate="novalidate">
        [{block name="change_payment_form"}][{/block}]
        ...
    </form>
```
