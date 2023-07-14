<?php
namespace XShoppingSt\Marketplace\Block;

/*
 * XShoppingSt Marketplace Sellerblock Block
 */
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Catalog\Model\Product;
use XShoppingSt\Marketplace\Model\ResourceModel\ProductFlagReason\CollectionFactory;

class Sellerblock extends \Magento\Framework\View\Element\Template
{
    const FLAG_REASON_ENABLE = 1;
    const FLAG_REASON_DISABLE = 0;
    /**
     * @var Product
     */
    protected $_product = null;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $session;

    /**
     * @var \XShoppingSt\Marketplace\Helper\Data
     */
    protected $mpHelper;

    /**
     * @var \XShoppingSt\Marketplace\Model\ResourceModel\ProductFlagReason\Collection
     */
    protected $reasonCollection;

    /**
     * Core registry.
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry                      $registry
     * @param Customer                                         $customer
     * @param \Magento\Customer\Model\Session                  $session
     * @param \XShoppingSt\Marketplace\Helper\Data                  $mpHelper
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        Customer $customer,
        \Magento\Customer\Model\Session $session,
        \XShoppingSt\Marketplace\Helper\Data $mpHelper,
        array $data = [],
        CollectionFactory $reasonCollection = null
    ) {
        $this->Customer = $customer;
        $this->Session = $session;
        $this->_coreRegistry = $registry;
        $this->mpHelper = $mpHelper;
        $this->reasonCollection = $reasonCollection ?: \Magento\Framework\App\ObjectManager::getInstance()
                                  ->create(CollectionFactory::class);
        parent::__construct($context, $data);
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }

        return $this->_product;
    }

    /**
     * getProductFlagReasons is used to get the product Flag Reasons
     * @return \XShoppingSt\Marketplace\Model\ResourceModel\ProductFlagReason\Collection
     */
    public function getProductFlagReasons()
    {
        $reasonCollection = $this->reasonCollection->create()
                          ->addFieldToFilter('status', self::FLAG_REASON_ENABLE)
                          ->setPageSize(5);
        return $reasonCollection;
    }
}
