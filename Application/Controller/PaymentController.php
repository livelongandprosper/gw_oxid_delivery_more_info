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
		$oActiveShopSet = oxNew(\OxidEsales\Eshop\Application\Model\DeliverySet::class);
		$oActiveShopSet->load($sActShipSet);
		$neededAdditionalDeliveryInfos = $oActiveShopSet->getAdditionalMandatoryFields();
		$sDelAddressId = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('deladrid');

		if(count($neededAdditionalDeliveryInfos) > 0) {
			$hasErrors = false;
			$oUser = $this->getUser();

			foreach($neededAdditionalDeliveryInfos as $neededAdditionalDeliveryInfo) {
				$neededAdditionalDeliveryInfoKey = ($sDelAddressId?'oxaddress__':'oxuser__').$neededAdditionalDeliveryInfo;
				if(!$additionalDeliveryInfosSubmitted[$neededAdditionalDeliveryInfoKey] && $neededAdditionalDeliveryInfo != 'oxstateid') {
					\OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay('GW_ERROR_'.$neededAdditionalDeliveryInfoKey);
					$hasErrors = true;
				} elseif($neededAdditionalDeliveryInfo == 'oxstateid') {
					$oCountry = oxNew(\OxidEsales\Eshop\Application\Model\Country::class);
					$sCountryId = "";
					if($sDelAddressId) {
						$oDelAddress = oxNew(\OxidEsales\Eshop\Application\Model\Address::class);
						$oDelAddress->setId($sDelAddressId);
						$oDelAddress->load($sDelAddressId);
						$sCountryId = $oDelAddress->oxaddress__oxcountryid->value;
					} else {
						$sCountryId = $oUser->oxuser__oxcountryid->value;
					}
					$oCountry->load($sCountryId);
					if(count($oCountry->getStates()) > 0 && !$additionalDeliveryInfosSubmitted[$neededAdditionalDeliveryInfoKey]) {
						\OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay('GW_ERROR_'.$neededAdditionalDeliveryInfoKey);
						$hasErrors = true;
					}
				}
			}
			if($hasErrors) {
				return;
			} else {
				if($sDelAddressId) {
					// Delivery address
					$oDelAddress = oxNew(\OxidEsales\Eshop\Application\Model\Address::class);
					$oDelAddress->setId($sDelAddressId);
					$oDelAddress->load($sDelAddressId);
					$oDelAddress->assign($additionalDeliveryInfosSubmitted);
					$oDelAddress->save();
				} else {
					// Billing address
					$oUser->assign($additionalDeliveryInfosSubmitted);
					$oUser->save();
				}
			}
		}
		return $parent_return;
	}
}
