<?php

// CUSTOMIZE store credit US03

class Magestore_Customercredit_Model_System_Config_Backend_ExpirationPeriodConfig extends Mage_Core_Model_Config_Data
{
    protected function _afterSave()
    {
        if ($this->isValueChanged()) {
            Mage::helper('customercredit')->setCreditExpirationDateAfterChangeConfig($this->getOldValue(),$this->getValue());
        }
    }
}