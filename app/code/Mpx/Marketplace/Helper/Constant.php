<?php

namespace Mpx\Marketplace\Helper;

class Constant
{

    const ENABLED_SELLER_STATUS = 1;
    const TEMPORARILY_SUSPENDED_SELLER_STATUS = 3;
    const UNICODE_HYPHEN_MINUS = "\u{002D}";
    const SKU_PREFIX_LENGTH = 4;
    const MARKETPLACE_NAME_CONFIG_PATH = 'mpx_web/general/marketplaceName';
    const FROM_MAIL_ADDRESS_CONFIG_PATH = 'mpx_web/general/notificationEmail';
    const XS_ADMIN_MAIL_ADDRESS_CONFIG_PATH = 'mpx_web/general/xsadminEmail';
    const SYSTEM_ADMIN_MAIL_ADDRESS_CONFIG_PATH = 'mpx_web/general/systemAdminEmail';
    const SYSTEM_NOTICE_MAIL_FROM_ADDRESS_CONFIG_PATH = 'mpx_web/general/systemNotificationEmail';
    const MARKETPLACE_ID_CONFIG_PATH = 'mpx_web/general/marketplaceId';
    const ENABLE_SELLER = "enable_seller";
    const DEFAULT_CARRIER_CODE = 'yamato_transport';
    const DEFAULT_CARRIER_TITLE = 'ヤマト運輸';
    const LIST_CARRIERS_SOFT = [
        'yamato_transport','sagawa_express','japan_post','seino_transportation','seino_super_express',
        'fukuyama_transporting','meitetsu_transport','tonami_transport','daiichi_freight',
        'niigata_unyu','chuetsu_group','okayama_shipping','kurume_transport','sanyo_auto_delivery',
        'nx_transport','eco_distribution','ems','dhl','fedex','ups','nippon_express','tnt','ocs',
        'usps','sf_express','aramex','sgh_global_japan'];

    const DEPLOY_MODE_CODE_PRODUCTION = "production";
    const X_SHOPPING_ST_BECOMESELLER_FULL_ACTION = "marketplace_account_becomeseller";
    const MPX_403_PAGE_ENABLE_CONFIG = "mpx_web/default/enable";
    const COUNTRY_PIC = 'JP';
    const JAPANESE_LOCALE_TIME_FORMAT = "YYYY/MM/DD";
    const DATE_VALIDATION_ERROR_CODE = "date_format";
    const DATE_VALIDATION_ERROR_MESSAGE = "The date entered is incorrect.";
    const EMPTY_SPECIAL_FROM_CODE = "empty_special_from";
    const EMPTY_SPECIAL_FROM_MESSAGE = "Enter special price start date.";
    const EMPTY_SPECIAL_TO_CODE = "empty_special_to";
    const EMPTY_SPECIAL_TO_MESSAGE = "Enter special price end date.";
    const EMPTY_SPECIAL_PRICE_CODE = "empty_special_price";
    const EMPTY_SPECIAL_PRICE_MESSAGE = "Please enter a special price.";
    const INVALID_SPECIAL_PRICE_ERROR_CODE =  "invalid_special_price";
    const INVALID_SPECIAL_PRICE_ERROR_MESSAGE = "Please enter the special price as a numerical value.";
    const SHORT_DESCRIPTION_LENGTH_ERROR_CODE =  "lenght_short_description";
    const SHORT_DESCRIPTION_LENGTH_ERROR_MESSAGE = "Please enter no more than 128 characters.";
    const SHORT_DESCRIPTION_MAX_LENGTH = 128;
    const SKU_LENGTH_ERROR_CODE =  "length_sku";
    const SKU_LENGTH_ERROR_MESSAGE = "Please enter the sku within 32 characters.";
    const SKU_MAX_LENGTH = 32;
    const REQUIRED_CATEGORY_ERROR_CODE = "product_category";
    const REQUIRED_CATEGORY_ERROR_MESSAGE = "Please select a category to register the product.";
    const MINIMUM_QUANTITY_CATEGORY = 1;
    const X_SHOPPING_ST_BECOMESELLER_CONFIG_DEFAULT_PAGE = 'mpx_web/default/non_seller';

}
