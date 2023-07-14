var config = {
    map: {
        '*': {
            colorpicker: 'XShoppingSt_Marketplace/js/colorpicker',
            verifySellerShop: 'XShoppingSt_Marketplace/js/account/verify-seller-shop',
            editSellerProfile: 'XShoppingSt_Marketplace/js/account/edit-seller-profile',
            sellerDashboard: 'XShoppingSt_Marketplace/js/account/seller-dashboard',
            sellerAddProduct: 'XShoppingSt_Marketplace/js/product/seller-add-product',
            sellerEditProduct: 'XShoppingSt_Marketplace/js/product/seller-edit-product',
            sellerCreateConfigurable: 'XShoppingSt_Marketplace/js/product/attribute/create',
            sellerProductList: 'XShoppingSt_Marketplace/js/product/seller-product-list',
            sellerOrderHistory: 'XShoppingSt_Marketplace/js/order/history',
            sellerOrderShipment: 'XShoppingSt_Marketplace/js/order/shipment',
            colorPickerFunction: 'XShoppingSt_Marketplace/js/color-picker-function',
            productGallery:     'XShoppingSt_Marketplace/js/product-gallery',
            baseImage:          'XShoppingSt_Marketplace/catalog/base-image-uploader',
            newVideoDialog:  'XShoppingSt_Marketplace/js/new-video-dialog',
            openVideoModal:  'XShoppingSt_Marketplace/js/video-modal',
            productAttributes:  'XShoppingSt_Marketplace/catalog/product-attributes',
            configurableAttribute:  'XShoppingSt_Marketplace/catalog/product/attribute',
            relatedProduct: 'XShoppingSt_Marketplace/js/product/related-product',
            upsellProduct: 'XShoppingSt_Marketplace/js/product/upsell-product',
            crosssellProduct: 'XShoppingSt_Marketplace/js/product/crosssell-product',
            notification : 'XShoppingSt_Marketplace/js/notification',
            separateSellerProductList: 'XShoppingSt_Marketplace/js/product/separate-seller-product-list',
            formButtonAction: 'XShoppingSt_Marketplace/js/form-button-action',
            "OwlCarousel": "XShoppingSt_Marketplace/js/sellerlideshow/owl.carousel.min",
            "WkSellerSlideShow": 'XShoppingSt_Marketplace/js/sellerlideshow/WkSellerSlideShow',
            'Magento_Ui/js/form/element/date':  'XShoppingSt_Marketplace/js/form/element/date',
            descriptionGallary: 'XShoppingSt_Marketplace/js/description-gallery'
        }
    },
    paths: {
        "colorpicker": 'js/colorpicker'
    },
    "shim": {
        "colorpicker" : ["jquery"],
        "OwlCarousel" : ["jQuery"]
    }
};
