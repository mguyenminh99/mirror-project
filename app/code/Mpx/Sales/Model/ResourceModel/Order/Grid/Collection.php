<?php

namespace Mpx\Sales\Model\ResourceModel\Order\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;

/**
 * Order grid collection
 */
class Collection extends SearchResult
{
    /**
     * Initialize dependencies.
     *
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param string $mainTable
     * @param string $resourceModel
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger        $logger,
        FetchStrategy $fetchStrategy,
        EventManager  $eventManager,
        $mainTable = 'marketplace_orders',
        $resourceModel = \Magento\Sales\Model\ResourceModel\Order::class
    ) {
        $this->_mainTable = $mainTable;
        $this->_entityFactory = $entityFactory;
        $this->_logger = $logger;
        $this->_eventManager = $eventManager;
        $this->_resourceModel = $resourceModel;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }

    /**
     * Get Data For Sales Order Grid
     *
     * @return $this|Collection
     */
    protected function _initSelect(): Collection
    {
        parent::_initSelect();
        $sellerOrder = $this->getTable('sales_order_grid');
        $mUserData = $this->getTable('marketplace_userdata');
        $mSaleslist = $this->getTable('marketplace_saleslist');
        $this->getSelect()
            ->joinLeft(
                ['st' => $mUserData],
                'st.seller_id = main_table.seller_id',
                'st.shop_title'
            )
            ->joinLeft(
                $mSaleslist . ' as ms',
                'main_table.order_id = ms.order_id AND main_table.seller_id = ms.seller_id',
                [
                    "magerealorder_id" => "magerealorder_id",
                    "magebuyer_id" => "magebuyer_id",
                    "currency_rate" => "currency_rate",
                    "paid_status" => "paid_status",
                    "cpprostatus" => "cpprostatus",
                    'SUM(ms.total_tax) AS total_tax',
                    'SUM(ms.total_commission) AS total_commission'
                ]
            )
            ->columns(
                [
                    'SUM(actual_seller_amount) AS actual_seller_amount',
                    'SUM(actual_seller_amount) AS purchased_actual_seller_amount',
                    'SUM(applied_coupon_amount) AS applied_coupon_amount'
                ]
            )
            ->joinRight(
                $sellerOrder . ' as ogf',
                'main_table.order_id = ogf.entity_id'
            )
            ->group('ogf.entity_id');
        $tableDescription = $this->getConnection()->describeTable($this->getMainTable());
        foreach ($tableDescription as $columnInfo) {
            $this->addFilterToMap($columnInfo['COLUMN_NAME'], 'main_table.' . $columnInfo['COLUMN_NAME']);
        }
        return $this;
    }
}
