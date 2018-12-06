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
/** @var $installer Mage_Core_Model_Resource_Setup */

//CUSTOMIZE store credit US03

$installer = $this;

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();
/* Add expiration date attribute */
$data = array(
    'type' => 'datetime',
    'input' => 'date',
    'label' => 'Expiration date',
    'backend' => 'eav/entity_attribute_backend_datetime',
    'frontend' => 'eav/entity_attribute_frontend_datetime',
    'source' => '',
    'is_visible' => 0,
    'is_visible_on_front' => 0,
    'required' => 0,
    'user_defined' => 0,
    'is_searchable' => 1,
    'is_filterable' => 0,
    'is_comparable' => 0,
    'position' => 3,
    'unique' => 0,
    'is_global' => ''
);
$setup->removeAttribute('customer', 'credit_expiration_date');
$setup->addAttribute('customer', 'credit_expiration_date', $data);
$data1 = array(
    'type' => 'text',
    'input' => 'textarea',
    'label' => 'Expiration notify by mail date',
    'backend' => '',
    'frontend' => '',
    'source' => '',
    'is_visible' => 0,
    'is_visible_on_front' => 0,
    'required' => 0,
    'user_defined' => 0,
    'is_searchable' => 1,
    'is_filterable' => 0,
    'is_comparable' => 0,
    'position' => 4,
    'unique' => 0,
    'is_global' => ''
);
$setup->removeAttribute('customer', 'credit_expiration_notify_date');
$setup->addAttribute('customer', 'credit_expiration_notify_date', $data1);

$installer->endSetup();