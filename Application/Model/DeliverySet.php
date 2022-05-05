<?php
namespace gw\gw_oxid_delivery_more_info\Application\Model;

/**
 * @see OxidEsales\Eshop\Application\Model\DeliverySet
 */
class DeliverySet extends DeliverySet_parent {
	public $additionalMandatoryFields = null;

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
}
