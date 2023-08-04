<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_MpAssignProduct
 * @author    Mpx
 */
namespace Mpx\MpAssignProduct\Helper;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableCollection;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use XShoppingSt\Marketplace\Model\ResourceModel\Product\CollectionFactory;
use XShoppingSt\Marketplace\Model\ResourceModel\Seller\CollectionFactory as SellerCollection;
use XShoppingSt\MpAssignProduct\Model\ResourceModel\Data\CollectionFactory as DataCollection;
use XShoppingSt\MpAssignProduct\Model\ResourceModel\Items\CollectionFactory as ItemsCollection;
use XShoppingSt\MpAssignProduct\Model\ResourceModel\Quote\CollectionFactory as QuoteCollection;

class Data extends \XShoppingSt\MpAssignProduct\Helper\Data
{
    /**
     * Get Associated Options of Assign Product
     *
     * @param int $productId
     * @param int $viewProductId
     * @return array
     */

    public function getAssociatedOptions($productId, $viewProductId = 0): array
    {
        $result = [];
        $associateData = [];
        $parentId = $this->_items->create()->load($viewProductId, 'assign_product_id')->getId();
        $websiteId = $this->_storeManager->getWebsite()->getId();
        $model = $this->_associates->create();
        $associateCollection = $model->getCollection()->addFieldToFilter('parent_id', $parentId);
        foreach ($associateCollection as $associateProduct) {
            $associateData[$associateProduct->getProductId()] = $associateProduct->getAssignProductId();
        }
        $collection = $model->getCollection()
            ->addFieldToFilter("parent_product_id", $productId);
        $proPriceAttrId = $this->eavAttribute->getIdByCode("catalog_product", "price");
        $catalogProductEntityDecimal = $model->getCollection()->getTable('catalog_product_entity_decimal');
        $catalogInventoryStockItem = $model->getCollection()->getTable('cataloginventory_stock_item');
        $collection->getSelect()->joinLeft(
            $catalogProductEntityDecimal.' as cped',
            'main_table.assign_product_id = cped.entity_id and cped.store_id = 0
            AND cped.attribute_id = '.$proPriceAttrId,
            ["product_price" => "value"]
        );
        $collection->getSelect()->join(
            $catalogInventoryStockItem.' as csi',
            'main_table.assign_product_id = csi.product_id',
            ["assign_qty" => "qty"]
        )->where("csi.website_id = 0 OR csi.website_id = ".$websiteId);
        $productInfo = $this->getAssociatedOptionsForOriginal($productId);
        foreach ($collection as $item) {
            if ($parentId != $item->getParentId()) {
                $info = [
                    'id' => $item->getId(),
                    'qty' => $item->getAssignQty(),
                    'price' => number_format($this->convertPriceFromBase($item->getProductPrice()), 0)
                ];
                $productId = $item->getProductId();
                $itemProductId = $associateData[$productId] ?? $item->getProductId();
                $result[$itemProductId][$item->getParentId()] = $info;
            }
            $assignProductId = $item->getAssignProductId();
            if (isset($productInfo[$item->getProductId()])) {
                $result[$assignProductId][0] = $productInfo[$item->getProductId()];
            }
        }
        return $result;
    }

    /**
     * Get Origin Seller Id
     *
     * @return int
     */
    public function getCustomerId()
    {
        $customerId = 0;
        if ($this->_customerSession->isLoggedIn()) {
            $customerId = (int) $this->commonFunc->getOriginSellerId($this->_customerSession->getCustomerId());
        }
        return $customerId;
    }
}
