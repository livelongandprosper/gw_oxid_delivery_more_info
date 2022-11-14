<?php
namespace gw\gw_oxid_delivery_more_info\Application\Model;

/**
 * @see OxidEsales\Eshop\Application\Model\DeliverySet
 */
class DeliverySet extends DeliverySet_parent {
	public $additionalMandatoryFields = null;

	/**
	 * @return array
	 */
	public function getAdditionalMandatoryFields() {
		if($this->additionalMandatoryFields === null) {
			$this->additionalMandatoryFields = [];
			$raw_array = explode("\n", $this->oxdeliveryset__gw_delivery_mandatory_userinfo_fields->value);
			foreach($raw_array as $item) {
				$value = trim($item);
				if($value) {
					$this->additionalMandatoryFields[] = $value;
				}
			}
		}
		return $this->additionalMandatoryFields;
	}

	/**
	 * @param array $additionalDeliveryInfosSubmitted
	 * @param string $sDelAddressId
	 * @return bool
	 */
	public function areAdditionalFieldsValid($additionalDeliveryInfosSubmitted, string $sDelAddressId = '') {
		$hasErrors = false;
		$oUser = $this->getUser();

		if($this->hasAddtionalMandatoryFields()) {
			foreach($this->getAdditionalMandatoryFields() as $neededAdditionalDeliveryInfo) {
				$neededAdditionalDeliveryInfoKey = ($sDelAddressId?'oxaddress__':'oxuser__') . $neededAdditionalDeliveryInfo;
				if(!$this->getValueOfArrayOrOxObject($additionalDeliveryInfosSubmitted, $neededAdditionalDeliveryInfoKey) && $neededAdditionalDeliveryInfo != 'oxstateid') {
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

					// if the country has states we need a stateid in the additonal submitted data, otherwise this should produce an error
					if(count($oCountry->getStates()) > 0 && !$this->getValueOfArrayOrOxObject($additionalDeliveryInfosSubmitted, $neededAdditionalDeliveryInfoKey)) {
						\OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay('GW_ERROR_'.$neededAdditionalDeliveryInfoKey);
						$hasErrors = true;
					}
				}
			}
			return !$hasErrors;
		}

		return true;
	}

	private function getValueOfArrayOrOxObject($arrayOrObject, string $key) {
		if(is_array($arrayOrObject)) {
			return $arrayOrObject[$key];
		} elseif(is_object($arrayOrObject)) {
			if($arrayOrObject->$key->rawValue) {
				return $arrayOrObject->$key->rawValue;
			} else {
				return $arrayOrObject->$key->value;
			}
		}
	}

	/**
	 * @return bool
	 */
	public function hasAddtionalMandatoryFields() {
		return count($this->getAdditionalMandatoryFields()) > 0;
	}

	public function updateAddressInfo(array $additionalDeliveryInfosSubmitted, string $sDelAddressId = '') {
		if($sDelAddressId) {
			// Delivery address
			$oDelAddress = oxNew(\OxidEsales\Eshop\Application\Model\Address::class);
			$oDelAddress->setId($sDelAddressId);
			$oDelAddress->load($sDelAddressId);
			$oDelAddress->assign($additionalDeliveryInfosSubmitted);
			$oDelAddress->save();
		} else {
			// Billing address
			$oUser = $this->getUser();
			$oUser->assign($additionalDeliveryInfosSubmitted);
			$oUser->save();
		}
	}
}
