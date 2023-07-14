<?php
namespace XShoppingSt\Mpshipping\Model\ResourceModel\Mpshipping;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface     $entityFactory
     * @param \Psr\Log\LoggerInterface                                      $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface  $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface                     $eventManager
     * @param \Magento\Store\Model\StoreManagerInterface                    $storeManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null           $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null     $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
        $this->_storeManager = $storeManager;
    }
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \XShoppingSt\Mpshipping\Model\Mpshipping::class,
            \XShoppingSt\Mpshipping\Model\ResourceModel\Mpshipping::class
        );
    }
}
