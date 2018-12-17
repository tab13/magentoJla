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

/**
 * Webpos Helper
 * 
 * @category    Magestore
 * @package     Magestore_Webpos
 * @author      Magestore Developer
 */
class Magestore_Webpos_Helper_Payment extends Mage_Core_Helper_Abstract {

    const CASH_PAYMENT_CODE = 'cashforpos';
    const NL_PAY_TRANSACTION_LOG_PATH = 'var/log/';

    /*
      These are some functions to get payment method information
     */

    // CUSTOMIZE pending payment US15,16
    public function getPendingMethodTitle() {
        $title = Mage::getStoreConfig('payment/pendingforpos/title');
        if ($title == '')
            $title = $this->__("Pending Payment");
        return $title;
    }

    public function isPendingPaymentEnabled() {
        return (Mage::getStoreConfig('payment/pendingforpos/active') && $this->isAllowOnWebPOS('pendingforpos'));
    }
    // CUSTOMIZE pending payment end US15,16

    public function getCashMethodTitle() {
        $title = Mage::getStoreConfig('payment/cashforpos/title');
        if ($title == '')
            $title = $this->__("Web POS - Cash");
        return $title;
    }

    public function isCashPaymentEnabled() {
        return (Mage::getStoreConfig('payment/cashforpos/active') && $this->isAllowOnWebPOS('cashforpos'));
    }

    public function getCcMethodTitle() {
        $title = Mage::getStoreConfig('payment/ccforpos/title');
        if ($title == '')
            $title = $this->__("Web POS - Credit card");
        return $title;
    }

    public function isCcPaymentEnabled() {
        return (Mage::getStoreConfig('payment/ccforpos/active') && $this->isAllowOnWebPOS('ccforpos'));
    }

    public function isWebposShippingEnabled() {
        return Mage::getStoreConfig('carriers/webpos_shipping/active');
    }

    public function getCp1MethodTitle() {
        $title = Mage::getStoreConfig('payment/cp1forpos/title');
        if ($title == '')
            $title = $this->__("Web POS - Custom Payment 1");
        return $title;
    }

    public function isCp1PaymentEnabled() {
        return (Mage::getStoreConfig('payment/cp1forpos/active') && $this->isAllowOnWebPOS('cp1forpos'));
    }

    public function getCp2MethodTitle() {
        $title = Mage::getStoreConfig('payment/cp2forpos/title');
        if ($title == '')
            $title = $this->__("Web POS - Custom Payment 2");
        return $title;
    }

    public function isCp2PaymentEnabled() {
        return (Mage::getStoreConfig('payment/cp2forpos/active') && $this->isAllowOnWebPOS('cp2forpos'));
    }

    public function getCodMethodTitle() {
        $title = Mage::getStoreConfig('payment/codforpos/title');
        if ($title == '')
            $title = $this->__("Web POS - Cash On Delivery");
        return $title;
    }

    public function isCodPaymentEnabled() {
        return (Mage::getStoreConfig('payment/codforpos/active') && $this->isAllowOnWebPOS('codforpos'));
    }

    public function getMultipaymentMethodTitle() {
        $title = Mage::getStoreConfig('payment/multipaymentforpos/title');
        if ($title == '')
            $title = $this->__("Web POS - Multiple Payments");
        return $title;
    }

    public function getMultipaymentActiveMethodTitle() {
        $payments = Mage::getStoreConfig('payment/multipaymentforpos/payments');
        if ($payments == '')
            $payments = explode(',', 'cp1forpos,cp2forpos,cashforpos,pendingforpos,ccforpos,codforpos'); // CUSTOMIZE pending payment US15,16
        return explode(',', $payments);
    }

    public function isMultiPaymentEnabled() {
        return (Mage::getStoreConfig('payment/multipaymentforpos/active'));
    }

    public function isAllowOnWebPOS($code) {
        $defaultPayment = $this->getDefaultPaymentMethod();
        $allowPayments = Mage::getModel('webpos/source_adminhtml_payment')->getAllowPaymentMethods();
        if (Mage::getStoreConfig('webpos/payment/allowspecific_payment', Mage::app()->getStore()->getId()) == '1') {
            $specificpayment = Mage::getStoreConfig('webpos/payment/specificpayment', Mage::app()->getStore()->getId());
            $specificpayment = explode(',', $specificpayment);
            return (in_array($code, $specificpayment) || $defaultPayment == $code)?true:false;
        }
        return (in_array($code, $allowPayments) || $defaultPayment == $code)?true:false;
    }

    public function getDefaultPaymentMethod() {
        return Mage::getStoreConfig('webpos/payment/defaultpayment', Mage::app()->getStore()->getId());
//        $result = '';
//        if(Mage::getStoreConfig('webpos/payment/allowspecific_payment', Mage::app()->getStore()->getId()) == 0){
//            $result = 'free';
//        }else if($result == '' && Mage::getStoreConfig('webpos/payment/allowspecific_payment', Mage::app()->getStore()->getId()) == '1'){
//            $specificpayment = Mage::getStoreConfig('webpos/payment/specificpayment', Mage::app()->getStore()->getId());
//            $specificpayment = explode(',', $specificpayment);
//            $result = (in_array('free', $specificpayment) ) ? 'free' : Mage::getStoreConfig('webpos/payment/defaultpayment', Mage::app()->getStore()->getId());
//        }
//        return $result;
    }

    public function isWebposPayment($code)
    {
        $payments = array('multipaymentforpos','cp1forpos','cp2forpos','cashforpos','pendingforpos','ccforpos','codforpos'); // CUSTOMIZE pending payment US15,16
        return in_array($code, $payments);
    }

    public function useCvv($code)
    {
        $useCvv =  Mage::getStoreConfig('payment/'.$code.'/useccv',  Mage::app()->getStore()->getId());
        return $useCvv;
    }

    public function isPayLater($code)
    {
        $isPayLater = Mage::getStoreConfig('payment/'.$code.'/pay_later',  Mage::app()->getStore()->getId());
        return $isPayLater;
    }

    public function isReferenceNumber($code)
    {
        $isReferenceNumber = Mage::getStoreConfig('payment/'.$code.'/use_reference_number',  Mage::app()->getStore()->getId());
        return $isReferenceNumber;
    }

    public function isPaypalEnable()
    {
        $isPaypalEnable = Mage::getStoreConfig('payment/payment/paypal_enable',  Mage::app()->getStore()->getId());
        return $isPaypalEnable;
    }

    /**
     * @return bool
     */
    public function isRetailerPos()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if((strpos(strtolower($userAgent), 'ipad') !== false || strpos(strtolower($userAgent), 'android') !==false)
            && (!(strpos(strtolower($userAgent), 'mozilla') !== false))) {
            return true;
        }
        return false;
    }

    public function isPayNlPayment($code){
        return in_array($code, array('pay_payment_instore'));
    }


    /**
     * when cancel order only refund once all money cash in order not refund
     * @param Mage_Sales_Model_Order $order
     */
    public function cancelOrder($order) {
        $orderPayments = Mage::getModel('webpos/payment_orderPayment')->getCollection()
            ->addFieldToFilter('method', 'pay_payment_instore')
            ->addFieldToFilter('order_id', $order->getId());

        try {
            /* @var Magestore_Webpos_Model_Payment_OrderPayment $orderPayment */
            foreach ($orderPayments as $orderPayment) {
                $orderPayment->delete();
            }
        } catch (Exception $exception) {
            Mage::logException($exception);
        }
    }
}
