<?php
namespace XShoppingSt\Marketplace\Controller\Seller;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use XShoppingSt\Marketplace\Helper\Data as MpDataHelper;

/**
 * XShoppingSt Marketplace Seller Usernameverify controller.
 */
class Usernameverify extends Action
{
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    /**
     * @var \XShoppingSt\Marketplace\Model\ResourceModel\Seller\CollectionFactory
     */
    protected $_sellerCollectionFactory;

    /**
     * Initialize dependencies
     *
     * @param Context $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \XShoppingSt\Marketplace\Model\ResourceModel\Seller\CollectionFactory $sellerCollectionFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \XShoppingSt\Marketplace\Model\ResourceModel\Seller\CollectionFactory $sellerCollectionFactory
    ) {
        $this->_jsonHelper = $jsonHelper;
        $this->_sellerCollectionFactory = $sellerCollectionFactory;
        parent::__construct($context);
    }

    /**
     * Verify seller shop URL exists or not
     *
     * @return \Magento\Framework\App\Response\Http
     */
    public function execute()
    {
        $profileUrl = trim($this->getRequest()->getParam("profileurl", ""));
        if ($profileUrl == "" || $profileUrl == MpDataHelper::MARKETPLACE_ADMIN_URL) {
            $this->getResponse()->representJson($this->_jsonHelper->jsonEncode(1));
        } else {
            $collection = $this->_sellerCollectionFactory->create();
            $collection->addFieldToFilter('shop_url', $profileUrl);
            $this->getResponse()->representJson($this->_jsonHelper->jsonEncode($collection->getSize()));
        }
    }
}
