<?php

namespace Mpx\Marketplace\Helper;

class Constant
{
    //Start Mpx_Catalog
    const DEFAULT_CATEGORY = 2;
    //End Mpx_Catalog

    //Start Mpx_OrderComment
    const STATUS_DELETE_ORDER_COMMENT = 1;
    const STATUS_EDIT_ORDER_COMMENT = 2;
    //End Mpx_OrderComment

    //Start Mpx_Mpshipping
    const PRICE_NONE_NUMERIC_ERROR_CODE = "none_numeric";
    const PRICE_NONE_NUMERIC_ERROR_MESSAGE = "Please enter a valid number in this field.";

    //End Mpx_Mpshipping

    //    Start Mpx_Marketplace
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

    //    End Mpx_Marketplace

    const PRICE_DECIMAL_ERROR_CODE = "price_decimal";
    const PRICE_DECIMAL_ERROR_MESSAGE = "Please enter a valid integer in this field.";
    
}
