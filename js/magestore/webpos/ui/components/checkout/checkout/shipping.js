/*
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

define(
    [
        'jquery',
        'ko',
        'underscore',
        'posComponent',
        'model/checkout/checkout/shipping',
        'helper/general',
        'mage/calendar',
        'model/checkout/checkout',
        'dataManager',
        'model/customer/customer/edit-customer',
        'model/checkout/cart',
        'model/customer/current-customer',
        // CUSTOMIZE edit custom method shipping US13
        'helper/price',
        // CUSTOMIZE edit custom method shipping end US13
    ],
    function ($, ko, _, Component, ShippingModel, Helper, Calendar, CheckoutModel, DataManager, EditCustomer, CartModel, CurrentCustomer, PriceHelper) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'ui/checkout/checkout/shipping',
            },
            items: ShippingModel.items,
            isSelected: ShippingModel.isSelected,
            // CUSTOMIZE edit custom method shipping US13
            priceEditAble: ShippingModel.priceEditAble,
            // CUSTOMIZE edit custom method shipping end US13
            initialize: function () {
                this._super();
                this.initObserver();
            },
            initObserver: function () {
                var self = this;
                Helper.observerEvent('go_to_checkout_page', function () {
                    if (!Helper.isOnlineCheckout()) {
                        ShippingModel.resetShipping();
                    }
                });
            },
            setShippingMethod: function (data) {
                if (typeof data.code !== "undefined") {
                    var shippingAddress = $.extend({}, CheckoutModel.shippingAddress());
                    // CUSTOMIZE edit custom method shipping US13
                    if (data.code === 'webpos_shipping_cs1') {
                        ShippingModel.priceEditAble(true);
                    } else {
                        ShippingModel.priceEditAble(false);
                    }
                    // CUSTOMIZE edit custom method shipping end US13
                    if (data.code != 'webpos_shipping_storepickup') {
                        if (CurrentCustomer.data() && CurrentCustomer.data().addresses) {
                            var customerAddresses = CurrentCustomer.data().addresses;
                            $.each(customerAddresses, function (index, value) {
                                if (value.default_shipping) {
                                    shippingAddress = value;
                                }
                            });
                        }
                    } else {
                        var storeAddress = DataManager.getData('webpos_store_address');
                        shippingAddress.city = storeAddress.city;
                        shippingAddress.country_id = storeAddress.country_id;
                        shippingAddress.region_id = storeAddress.region_id;
                        shippingAddress.region = storeAddress.region;
                        shippingAddress.postcode = storeAddress.postcode;
                    }
                    CheckoutModel.selectedShippingCode(data.code);
                    var isChangeShippingAddress = false;
                    if (!_.isEqual(CheckoutModel.shippingAddress(), shippingAddress)) {
                        isChangeShippingAddress = true;
                    }
                    CheckoutModel.shippingAddress(shippingAddress);
                    if (Helper.isOnlineCheckout()) {
                        if (isChangeShippingAddress) {
                            var saveCartResult = CartModel.saveCartOnline();
                            saveCartResult.done(function (response) {
                                if (response.status && response.data) {
                                    ShippingModel.saveShippingMethod(data);
                                }
                            });
                        }else{
                            ShippingModel.saveShippingMethod(data);
                        }
                    } else {
                        ShippingModel.saveShippingMethod(data);
                        CartModel.reCollectTaxRate();
                    }
                }
            },
            getShippingPrice: function (price, priceType) {
                return ShippingModel.formatShippingPrice(price, priceType);
            },
            useDeliveryTime: function () {
                return ShippingModel.useDeliveryTime();
            },
            initDate: function () {
                var currentDate = new Date();
                var year = currentDate.getFullYear();
                var month = currentDate.getMonth();
                var day = currentDate.getDate();
                $("#delivery_date").calendar({
                    showsTime: true,
                    controlType: 'select',
                    timeFormat: 'HH:mm TT',
                    showTime: false,
                    minDate: new Date(year, month, day, '00', '00', '00', '00'),
                });
            },
            // CUSTOMIZE edit custom method shipping US13
            checkCustomerMethod: function (code) {
                if (code === 'webpos_shipping_cs1' && ShippingModel.isSelected) {
                    return true;
                } else {
                    return false;
                }
            },
            editShippingPrice: function (data, event) {
                var value = event.target.value;
                value = (PriceHelper.toNumber(value) > 0)?PriceHelper.toNumber(value):data.price;
                event.target.value = PriceHelper.formatPrice(PriceHelper.toNumber(value));
                data.price = value;
                if (Helper.isOnlineCheckout()) {
                    ShippingModel.saveShippingMethod(data);
                } else {
                    ShippingModel.saveShippingMethod(data);
                    CartModel.reCollectTaxRate();
                }
                ShippingModel.editShippingPrice();
            },
            // CUSTOMIZE edit custom method shipping end US13
        });
    }
);