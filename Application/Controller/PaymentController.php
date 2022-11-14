<?php
namespace gw\gw_oxid_delivery_more_info\Application\Controller;

use OxidEsales\Eshop\Core\Registry;

class PaymentController extends PaymentController_parent {
	/**
	 * Template variable getter. Returns delivery address
	 *
	 * @return object
	 */
	public function getDelAddress()
	{
		if ($this->_oDelAddress === null) {
			$this->_oDelAddress = false;
			$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
			$this->_oDelAddress = $oOrder->getDelAddressInfo();
		}

		return $this->_oDelAddress;
	}


	/**
	 * Validates oxidcreditcard and oxiddebitnote user payment data.
	 * Returns null if problems on validating occured. If everything
	 * is OK - returns "order" and redirects to payment confirmation
	 * page.
	 *
	 * Session variables:
	 * <b>paymentid</b>, <b>dynvalue</b>, <b>payerror</b>
	 *
	 * @return  mixed
	 */
	public function validatePayment() {
		$parent_return = parent::validatePayment();
		$additionalDeliveryInfosSubmitted = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("additionalDeliveryFields");
		$sActShipSet = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('sShipSet');
		$oActiveShipSet = oxNew(\OxidEsales\Eshop\Application\Model\DeliverySet::class);
		$oActiveShipSet->load($sActShipSet);
		$neededAdditionalDeliveryInfos = $oActiveShipSet->getAdditionalMandatoryFields();
		$sDelAddressId = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('deladrid');

		if($oActiveShipSet->hasAddtionalMandatoryFields()) {
			if(!$oActiveShipSet->areAdditionalFieldsValid($additionalDeliveryInfosSubmitted, (string)$sDelAddressId)) {
				return;
			} else {
				$oActiveShipSet->updateAddressInfo($additionalDeliveryInfosSubmitted, (string)$sDelAddressId);
			}
		}
		return $parent_return;
	}
}
