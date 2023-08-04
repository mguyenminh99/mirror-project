<?php

namespace Mpx\MpAssignProduct\Controller\Product;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Mpx\Marketplace\Helper\CommonFunc as MpxMarketplaceHelper;

class Save extends \XShoppingSt\MpAssignProduct\Controller\Product\Save
{

    const DEFAULT_STORE_ID = 0;
    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $_url;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_session;

    /**
     * @var \XShoppingSt\MpAssignProduct\Helper\Data
     */
    protected $_assignHelper;

    /**
     * @var \Magento\Catalog\Model\Product\Copier
     */
    protected $productCopier;

    /**
     * @var \XShoppingSt\Marketplace\Helper\Data
     */
    protected $mpHelper;

    /**
     * @var StockConfigurationInterface
     */
    protected $stockConfiguration;

    /**
     * @var StockRegistryInterface
     */
    protected $stockRegistry;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var MpxMarketplaceHelper
     */
    protected $mpxMarketplaceHelper;

    /**
     * @param Context $context
     * @param \Magento\Customer\Model\Url $url
     * @param \Magento\Customer\Model\Session $session
     * @param \XShoppingSt\MpAssignProduct\Helper\Data $helper
     * @param \Magento\Catalog\Model\Product\Copier $productCopier
     * @param \XShoppingSt\Marketplace\Helper\Data $mpHelper
     * @param StockConfigurationInterface $stockConfiguration
     * @param StockRegistryInterface $stockRegistry
     * @param ProductRepositoryInterface $productRepository
     * @param MpxMarketplaceHelper $mpxMarketplaceHelper
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Model\Url $url,
        \Magento\Customer\Model\Session $session,
        \XShoppingSt\MpAssignProduct\Helper\Data $helper,
        \Magento\Catalog\Model\Product\Copier $productCopier,
        \XShoppingSt\Marketplace\Helper\Data $mpHelper,
        StockConfigurationInterface $stockConfiguration,
        StockRegistryInterface $stockRegistry,
        ProductRepositoryInterface $productRepository,
        MpxMarketplaceHelper            $mpxMarketplaceHelper
    ) {
        $this->_url = $url;
        $this->_session = $session;
        $this->_assignHelper = $helper;
        $this->productCopier = $productCopier;
        $this->mpHelper = $mpHelper;
        $this->stockConfiguration = $stockConfiguration;
        $this->stockRegistry = $stockRegistry;
        $this->productRepository = $productRepository;
        $this->mpxMarketplaceHelper = $mpxMarketplaceHelper;
        parent::__construct($context,
            $url,
            $session,
            $helper,
            $productCopier,
            $mpHelper,
            $stockConfiguration,
            $stockRegistry,
            $productRepository);
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $helper = $this->_assignHelper;
        $currentStoreId = $helper->getStoreId();
        $data = $this->getRequest()->getParams();
        $data['image'] = '';
        if (!array_key_exists('product_id', $data)) {
            $this->messageManager->addError(__('Something went wrong.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/view');
        }
        $productId = $data['product_id'];
        $newProductId = 0;
        $product = $helper->getProduct($productId);
        $sku = substr($product->getSku(),  strpos($product->getSku(), '-') + 1, strlen($product->getSku()));
        $productType = $product->getTypeId();
        $product->setSku($this->mpxMarketplaceHelper->formatSku($sku));
        $result = $helper->validateData($data, $productType);
        if ($result['error']) {
            $this->messageManager->addError(__($result['msg']));
            return $this->resultRedirectFactory->create()->setPath('*/*/view');
        }
        if (array_key_exists('assign_id', $data) && array_key_exists('assign_product_id', $data)) {
            $flag = 1;
            $newProductId = $data['assign_product_id'];
        } else {
            $flag = 0;
            $data['del'] = 0;
        }
        if (!$flag) {
            $newProduct = $this->productCopier->copy($product);
            $newProductId = $newProduct->getId();
            $data['assign_product_id'] = $newProductId;
            $this->removeImages($newProductId);
        }
        $status = \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED;
        $attributData = [
            'status' => $status,
            'description' => $data['description']
        ];
        if ($productType != "configurable") {
            $attributData['price'] = $data['price'];
        }
        $helper->updateProductData([$newProductId], $attributData, $currentStoreId);
        $duplicateProduct = $helper->getProduct($newProductId);
        $sku = $duplicateProduct->getSku();
        if ($productType != "configurable") {
            $this->mpHelper->reIndexData();
            $this->updateStockData($sku, $data['qty'], 1);
        } else {
            $associateProducts = [];
            $updatedProducts = $this->addAssociatedProducts($newProductId, $data);
            $this->mpHelper->reIndexData();
            $data['products'] = $updatedProducts;
            foreach ($updatedProducts as $exProductId => $updatedData) {
                $associateProducts[] = $updatedData['new_product_id'];
                $this->updateStockData($updatedData['sku'], $updatedData['qty'], 1);
            }
            $duplicateProduct->setStatus($status);
            $duplicateProduct->setDescription($data['description']);
            $duplicateProduct->setAssociatedProductIds($associateProducts);
            $duplicateProduct->setCanSaveConfigurableAttributes(true);
            $duplicateProduct->save();
        }
        $result = $helper->processAssignProduct($data, $productType, $flag);
        if ($result['assign_id'] > 0) {
            $this->adminStoreMediaImages($newProductId, $data, $currentStoreId);
            $helper->processProductStatus($result);
            $this->messageManager->addSuccess(__('Product is saved successfully.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/productlist');
        } else {
            $this->messageManager->addError(__('There was some error while processing your request.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/add', ['id' => $data['product_id']]);
        }
    }
}
