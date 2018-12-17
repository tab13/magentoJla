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
 * @package     Magestore_Webpos
 * @copyright   Copyright (c) 2016 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

//CUSTOMIZE pending payment US15,16

class Magestore_Webpos_Model_Payment_Method_Pending extends Mage_Payment_Model_Method_Abstract {
    /* This model define payment method */

    protected $_code = 'pendingforpos';
    protected $_infoBlockType = 'webpos/payment_method_pending_info_pending';

    public function isAvailable($quote = null) {
        $isWebposApi = Mage::helper('webpos/permission')->validateRequestSession();
        $routeName = Mage::app()->getRequest()->getRouteName();
        $pendingenabled = Mage::helper('webpos/payment')->isPendingPaymentEnabled();
        if (($routeName == "webpos" || $isWebposApi) && $pendingenabled == true)
            return true;
        else
            return false;
    }

}