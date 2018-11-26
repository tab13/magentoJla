<?php

/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Storecredit
 * @module      Storecredit
 * @author      Magestore Developer
 *
 * @copyright   Copyright (c) 2016 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 *
 */

/**
 * Customercredit Block
 * 
 * @category    Magestore
 * @package     Magestore_Customercredit
 * @author      Magestore Developer
 */
class Magestore_Customercredit_Block_Sumary extends Mage_Core_Block_Template
{

    /**
     * prepare block's layout
     *
     * @return Magestore_Customercredit_Block_Customercredit
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * @return mixed
     */
    public function getBalanceLabel()
    {
        return Mage::getModel('customercredit/customercredit')->getCustomerCreditLabel();
    }

//    CUSTOMIZE store credit US01
    public function getStoreConfig($path)
    {
        return Mage::getStoreConfig($path, Mage::app()->getStore()->getId());
    }

    public function getConfigAllowPurchaseCredit() {
        $configEnable = $this->getStoreConfig('customercredit/general/enable_purchase_credit');
        return $configEnable;
    }
//    CUSTOMIZE store credit end US01

//    CUSTOMIZE store credit US02
    public function getConfigAllowRedeemCredit() {
        $configEnable = $this->getStoreConfig('customercredit/general/enable_redeem_credit');
        return $configEnable;
    }
//    CUSTOMIZE store credit end US02

}
