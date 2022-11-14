<?php
namespace gw\gw_oxid_delivery_more_info\Application\Controller;

use OxidEsales\Eshop\Core\Registry;

class OrderController extends OrderController_parent {

	public function execute() {
		if($this->isAdditionalShippingDataOK()) {
			return parent::execute();
		} else {
			// an error occured, just stay here; error is displayed by DelvierySet::areAdditionalFieldsValid()
			return;
		}
	}

	/**
	 * @return false
	 */
	public function isAdditionalShippingDataOK() {
		$sDelAddressId = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('deladrid');
		$oActiveShipSet = $this->getShipSet();
		if($sDelAddressId) {
			$oDelAddress = oxNew(\OxidEsales\Eshop\Application\Model\Address::class);
			$oDelAddress->setId($sDelAddressId);
			$oDelAddress->load($sDelAddressId);
			return $oActiveShipSet->areAdditionalFieldsValid($oDelAddress, (string)$sDelAddressId);
		} else {
			return $oActiveShipSet->areAdditionalFieldsValid($this->getUser());
		}
		return false;
	}

	/**
	 * This will add the submitted additional Data to the users address or to the shipping address.
	 * @return void
	 */
	public function updateAdditionalInfo() {
		$additionalDeliveryInfosSubmitted = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("additionalDeliveryFields");
		$oActiveShipSet = $this->getShipSet();
		$sDelAddressId = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('deladrid');
		if($oActiveShipSet->hasAddtionalMandatoryFields()) {
			if(!$oActiveShipSet->areAdditionalFieldsValid($additionalDeliveryInfosSubmitted, (string)$sDelAddressId)) {
				return;
			} else {
				$oActiveShipSet->updateAddressInfo($additionalDeliveryInfosSubmitted, (string)$sDelAddressId);
			}
		}
	}
}
