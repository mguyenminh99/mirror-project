<?php

namespace Mpx\Marketplace\Controller\Product\Ui;

use Magento\Ui\Component\MassAction\Filter;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use XShoppingSt\Marketplace\Model\ResourceModel\Product\CollectionFactory as SellerProduct;
use XShoppingSt\Marketplace\Helper\Data as HelperData;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Url as CustomerUrl;
use Mpx\Marketplace\Helper\Constant;

class MassDelete extends \XShoppingSt\Marketplace\Controller\Product\Ui\MassDelete
{

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var SellerProduct
     */
    protected $_sellerProductCollectionFactory;

    /**
     * @var HelperData
     */
    protected $helper;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CustomerUrl
     */
    private $customerUrl;

    /**
     * @var \XShoppingSt\Marketplace\Model\ResourceModel\Orders\CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param Session $customerSession
     * @param Registry $coreRegistry
     * @param CollectionFactory $productCollectionFactory
     * @param SellerProduct $sellerProductCollectionFactory
     * @param HelperData $helper
     * @param ProductRepositoryInterface $productRepository
     * @param CustomerUrl $customerUrl
     * @param \XShoppingSt\Marketplace\Model\ResourceModel\Orders\CollectionFactory $orderCollectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        Session $customerSession,
        Registry $coreRegistry,
        CollectionFactory $productCollectionFactory,
        SellerProduct $sellerProductCollectionFactory,
        HelperData $helper,
        ProductRepositoryInterface $productRepository = null,
        CustomerUrl $customerUrl,
        \XShoppingSt\Marketplace\Model\ResourceModel\Orders\CollectionFactory $orderCollectionFactory
    ) {
        $this->filter = $filter;
        $this->_customerSession = $customerSession;
        $this->_coreRegistry = $coreRegistry;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_sellerProductCollectionFactory = $sellerProductCollectionFactory;
        $this->helper = $helper;
        $this->productRepository = $productRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()->create(ProductRepositoryInterface::class);
        $this->customerUrl = $customerUrl;
        $this->orderCollectionFactory = $orderCollectionFactory;
        parent::__construct(
            $context,
            $filter,
            $customerSession,
            $coreRegistry,
            $productCollectionFactory,
            $sellerProductCollectionFactory,
            $helper,
            $productRepository,
            $customerUrl
        );
    }

    /**
     * Mass delete seller products action.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $assignIds = [];
        if ($this->helper->isSeller() == Constant::ENABLED_SELLER_STATUS) {
            try {
                $registry = $this->_coreRegistry;
                if (!$registry->registry('mp_flat_catalog_flag')) {
                    $registry->register('mp_flat_catalog_flag', 1);
                }
                $collection = $this->filter->getCollection(
                    $this->_productCollectionFactory->create()
                );

                $ids = $collection->getAllIds();
                $wholedata = [];

                $sellerId = $this->helper->getCustomerId();
                $this->_coreRegistry->register('isSecureArea', 1);
                $deletedIdsArr = [];
                $undeletableProductIdsArr = [];
                $sellerProducts = $this->_sellerProductCollectionFactory
                    ->create()
                    ->addFieldToFilter(
                        'mageproduct_id',
                        ['in' => $ids]
                    )->addFieldToFilter(
                        'seller_id',
                        $sellerId
                    );
                $orderedProductIdsArr = [];
                $orderedProductIdsString = "";
                $orders = $this->orderCollectionFactory->create()->addFieldToFilter('seller_id', $sellerId);
                foreach ($orders as $order) {
                    if ( $orderedProductIdsString ) {
                        $orderedProductIdsString .= ',';
                    }
                    $orderedProductIdsString .= $order->getProductIds();
                }
                $orderedProductIdsArr = explode(",", $orderedProductIdsString);
                foreach ($sellerProducts as $sellerProduct) {
                    $wholedata['id'] = $sellerProduct['mageproduct_id'];
                    $this->_eventManager->dispatch(
                        'mp_delete_product',
                        [$wholedata]
                    );
                    if ($this->_customerSession->getAssignProductIds()) {
                        $assignIds = $this->_customerSession->getAssignProductIds();
                    }
                    if (!in_array($sellerProduct['mageproduct_id'], $assignIds) &&
                        !in_array($sellerProduct['mageproduct_id'], $orderedProductIdsArr)) {
                        array_push($deletedIdsArr, $sellerProduct['mageproduct_id']);
                        $sellerProduct->delete();
                    } else {
                        array_push($undeletableProductIdsArr, $sellerProduct['mageproduct_id']);
                    }
                }

                foreach ($deletedIdsArr as $id) {
                    try {
                        if (!in_array($id, $assignIds) && !in_array($id, $orderedProductIdsArr)) {
                            $product = $this->productRepository->getById($id);
                            $this->productRepository->delete($product);
                        }
                    } catch (\Exception $e) {
                        $this->helper->logDataInLogger(
                            "Controller_Product_Ui_MassDelete execute : ".$e->getMessage()
                        );
                        $this->messageManager->addError($e->getMessage());
                    }
                }

                $this->_coreRegistry->unregister('isSecureArea');
                if (count($deletedIdsArr)) {
                    // clear cache
                    $this->helper->clearCache();
                    $this->messageManager->addSuccess(
                        __('A total of %1 record(s) have been deleted.', count($deletedIdsArr))
                    );
                }
                if (count($undeletableProductIdsArr)) {
                    $this->helper->clearCache();
                    $this->messageManager->addError(
                        __('This product cannot be deleted because there is %1 order(s) in the past. If you want to stop selling the product, please change the product status to disabled.', count($undeletableProductIdsArr))
                    );
                }
            } catch (\Exception $e) {
                $this->helper->logDataInLogger(
                    "Controller_Product_Ui_MassDelete execute : ".$e->getMessage()
                );
                $this->messageManager->addError($e->getMessage());
            }
            return $this->resultRedirectFactory->create()->setPath(
                'marketplace/product/productlist',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        } else {
            return $this->resultRedirectFactory->create()->setPath(
                'marketplace/account/becomeseller',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        }
    }
}
