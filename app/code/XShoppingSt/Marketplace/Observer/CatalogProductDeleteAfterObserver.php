<?php
namespace XShoppingSt\Marketplace\Observer;

use Magento\Framework\Event\ObserverInterface;
use XShoppingSt\Marketplace\Model\ResourceModel\Seller\CollectionFactory;
use XShoppingSt\Marketplace\Model\ProductFactory as MpProductFactory;
use XShoppingSt\Marketplace\Helper\Data as MpHelper;

/**
 * XShoppingSt Marketplace CatalogProductDeleteAfterObserver Observer.
 */
class CatalogProductDeleteAfterObserver implements ObserverInterface
{

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var MpProductFactory
     */
    protected $mpProductFactory;

    /**
     * @var MpHelper
     */
    protected $mpHelper;

    /**
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param CollectionFactory                           $collectionFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param MpProductFactory                            $mpProductFactory
     * @param MpHelper                                    $mpHelper
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        CollectionFactory $collectionFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        MpProductFactory $mpProductFactory,
        MpHelper $mpHelper
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_date = $date;
        $this->messageManager = $messageManager;
        $this->mpProductFactory = $mpProductFactory;
        $this->mpHelper = $mpHelper;
    }

    /**
     * Product delete after event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $productId = $observer->getProduct()->getId();
            $productCollection = $this->mpProductFactory->create()
                                  ->getCollection()
                                  ->addFieldToFilter(
                                      'mageproduct_id',
                                      $productId
                                  );
            foreach ($productCollection as $product) {
                $this->mpProductFactory->create()
                ->load($product->getEntityId())->delete();
            }
        } catch (\Exception $e) {
            $this->mpHelper->logDataInLogger(
                "Observer_CatalogProductDeleteAfterObserver execute : ".$e->getMessage()
            );
            $this->messageManager->addError($e->getMessage());
        }
    }
}
