<?php
/**
 * @abstract
 * @author 	Gregor Wendland <oxid@gregor-wendland.com>
 * @copyright Copyright (c) 2016-2021, Gregor Wendland
 * @package gw
 * @version 2022-05-05
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = array(
    'id'           => 'gw_oxid_delivery_more_info',
    'title'        => 'Versandarten mit weiteren Pflichtfeldern',
//     'thumbnail'    => 'out/admin/img/logo.jpg',
    'version'      => '1.0',
    'author'       => 'Gregor Wendland',
    'email'		   => 'oxid@gregor-wendland.com',
    'url'		   => 'https://gregor-wendland.com',
    'description'  => array(
    	'de'		=> 'Dieses Modul ermöglicht, bei der Auswahl einer Versandart weitere Pflichtfelder definieren zu können.',
    	'en'		=> 'This module makes it possible to define additional mandatory fields when selecting a shipping method.',
    ),

    /* extend */
    'extend'       => array(
		OxidEsales\Eshop\Application\Controller\PaymentController::class => gw\gw_oxid_delivery_more_info\Application\Controller\PaymentController::class,
		OxidEsales\Eshop\Application\Model\DeliverySet::class => gw\gw_oxid_delivery_more_info\Application\Model\DeliverySet::class,
    ),
    /* settings */
    'settings'		=> array(),
	'events'       => array(
		'onActivate'   => '\gw\gw_oxid_delivery_more_info\Core\Events::onActivate',
		'onDeactivate' => '\gw\gw_oxid_delivery_more_info\Core\Events::onDeactivate'
	),
	'blocks' => array(
		// frontend
		array(
			'template' => 'page/checkout/payment.tpl',
			'block' => 'change_payment_form',
			'file' => 'Application/views/blocks/change_payment_form.tpl'
		),

		// backend
		array(
			'template' => 'deliveryset_main.tpl',
			'block' => 'admin_deliveryset_main_form',
			'file' => 'Application/views/blocks/admin_deliveryset_main_form.tpl'
		),
	),

);
?>
