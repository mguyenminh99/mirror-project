<?php

namespace Mpx\MpMassUpload\Helper;

class Export extends \XShoppingSt\MpMassUpload\Helper\Export
{

    /**
     * Process Export Product Data
     *
     * @param string $productType
     * @param array $productIds
     *
     * @return array
     */
    public function exportProducts($productType, $productIds, $allowedAttributes)
    {
        $helper = $this->_massUploadHelper;
        $productsRow = [];
        $productsRow[0] = $this->prepareFileColumnRow($productType, $allowedAttributes);
        $productsDataRow = [];
        foreach ($productIds as $id) {
            /** @var $mageProduct \Magento\Catalog\Model\Product */
            $mageProduct = $this->loadData($id);
            $productData = $mageProduct->getData();
            $mageProductType = $mageProduct->getTypeId();
            if ($mageProductType == $productType) {
                $wholeData = [];
                /*Get Category Names by category id (set by comma seperated)*/
                $categories = $this->getCategorysByIds($mageProduct['category_ids']);
                if ($productType == 'configurable') {
                    $wholeData['type'] = 'configurable';
                }
                $wholeData['ステータス'] = $mageProduct['status'];
                $wholeData['商品カテゴリー'] = $categories;
                $wholeData['商品名'] = $mageProduct['name'];
                $wholeData['概要'] = $mageProduct['description'];
                $wholeData['キャッチコピー'] = $mageProduct['short_description'];
                $wholeData['商品番号'] = $mageProduct['sku'];
                $wholeData['単価'] = floor($mageProduct['price']);
                $wholeData['特別価格'] = floor($mageProduct['special_price']);
                $wholeData['特別価格開始日'] = $mageProduct['special_from_date'];
                $wholeData['特別価格終了日'] = $mageProduct['special_to_date'];
                if (!empty($mageProduct['quantity_and_stock_status']['qty'])) {
                    $wholeData['在庫数'] = $mageProduct['quantity_and_stock_status']['qty'];
                } else {
                    $wholeData['在庫数'] = 0;
                }

                $wholeData['商品画像'] = $this->getImages($mageProduct);
                $wholeData['メタタイトル'] = $mageProduct['meta_title'];
                $wholeData['メタキーワード'] = $mageProduct['meta_keyword'];
                $wholeData['メタ概要'] = $mageProduct['meta_description'];
                /*Set Downloadable Data*/
                $wholeData = $this->getDownloadableData($mageProduct, $wholeData);
                /*Set Configurable Data*/
                $associatedData = [];
                if ($productType == 'configurable') {
                    $result = $this->getConfigurableData($mageProduct, $wholeData);
                    $wholeData = $result['parent'];
                    $associatedData = $result['child'];
                }
                /*Set Custom Attributes Values*/
                if ($helper->canSaveCustomAttribute()) {
                    $wholeData = $this->getCustomAttributeData(
                        $mageProduct,
                        $wholeData,
                        $allowedAttributes
                    );
                }
                /*Set Custom Options Values*/
                if ($helper->canSaveCustomOption()) {
                    $wholeData = $this->getCustomOptionData($mageProduct, $wholeData);
                }
                $productsDataRow[] = $wholeData;
                foreach ($associatedData as $value) {
                    $productsDataRow[] = $value;
                }
            }
        }
        $productsRow[1] = $productsDataRow;
        return $productsRow;
    }

    /**
     * Prepare File Column Row
     *
     * @param string $productType
     *
     * @return array
     */
    public function prepareFileColumnRow($productType, $allowedAttributes)
    {
        $helper = $this->_massUploadHelper;
        if ($productType == 'configurable') {
            $wholeData[] = 'type';
        }
        $wholeData[] = 'ステータス';
        $wholeData[] = '商品カテゴリー';
        $wholeData[] = '商品名';
        $wholeData[] = '概要';
        $wholeData[] = 'キャッチコピー';
        $wholeData[] = '商品番号';
        $wholeData[] = '単価';
        $wholeData[] = '特別価格';
        $wholeData[] = '特別価格開始日';
        $wholeData[] = '特別価格終了日';
        $wholeData[] = '在庫数';

        $wholeData[] = '商品画像';
        $wholeData[] = 'メタタイトル';
        $wholeData[] = 'メタキーワード';
        $wholeData[] = 'メタ概要';
        /*Set Downloadable Data*/
        if ($productType == 'downloadable') {
            $wholeData[] = 'links_title';
            $wholeData[] = 'links_purchased_separately';
            $wholeData[] = 'downloadable_link_title';
            $wholeData[] = 'downloadable_link_price';
            $wholeData[] = 'downloadable_link_type';
            $wholeData[] = 'downloadable_link_file';
            $wholeData[] = 'downloadable_link_sample_type';
            $wholeData[] = 'downloadable_link_sample';
            $wholeData[] = 'downloadable_link_is_sharable';
            $wholeData[] = 'downloadable_link_is_unlimited';
            $wholeData[] = 'downloadable_link_number_of_downloads';
            $wholeData[] = 'samples_title';
            $wholeData[] = 'downloadable_sample_title';
            $wholeData[] = 'downloadable_sample_file';
            $wholeData[] = 'downloadable_sample_type';
        }

        /*Set Configurable Data*/
        if ($productType == 'configurable') {
            $wholeData[] = '_super_attribute_code';
            $wholeData[] = '_super_attribute_option';
        }
        /*Set Custom Attributes Values*/
        if ($helper->canSaveCustomAttribute()) {
            foreach ($allowedAttributes as $id => $code) {
                $code = trim($code);
                if ($code == 'null' || $code === null || empty($code)) {
                    continue;
                }
                $wholeData[] = $code;
            }
        }
        // /*Set Custom Options Values*/
        if ($helper->canSaveCustomOption()) {
            $wholeData[] = 'custom_option';
        }
        return $wholeData;
    }
}
