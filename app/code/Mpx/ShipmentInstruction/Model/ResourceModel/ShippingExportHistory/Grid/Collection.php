<?php
namespace Mpx\ShipmentInstruction\Model\ResourceModel\ShippingExportHistory\Grid;

use Mpx\ShipmentInstruction\Model\ResourceModel\ShippingExportHistory\Collection as ShippingExportHistory;
use XShoppingSt\Marketplace\Helper\Data;

class Collection extends ShippingExportHistory implements \Magento\Framework\Api\Search\SearchResultInterface
{
    protected $aggregations;

    /**
     * @var Data
     */
    protected $helperData;

    // @codingStandardsIgnoreStart
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        $mainTable,
        $eventPrefix,
        $eventObject,
        $resourceModel,
        Data $helperData,
        $model = \Magento\Framework\View\Element\UiComponent\DataProvider\Document::class,
        $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->_eventPrefix = $eventPrefix;
        $this->helperData = $helperData;
        $this->_eventObject = $eventObject;
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
    }
    // @codingStandardsIgnoreEnd

    /**
     * @return \Magento\Framework\Api\Search\AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @param $aggregations
     * @return void
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * @param $limit
     * @param $offset
     * @return array
     */
    public function getAllIds($limit = null, $offset = null)
    {
        return $this->getConnection()->fetchCol($this->_getAllIdsSelect($limit, $offset), $this->_bindParams);
    }

    /**
     * Get search criteria.
     *
     * @return $this|\Magento\Framework\Api\Search\SearchCriteriaInterface
     */
    public function getSearchCriteria()
    {
        return $this;
    }

    /**
     * Set search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return $this|Collection
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * Set total count.
     *
     * @param $totalCount
     * @return $this|Collection
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * Set total count.
     *
     * @param array|null $items
     * @return $this|Collection
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

    /**
     * Render Filters Before
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $sellerId = $this->helperData->getCustomerId();
        $this->getSelect()
            ->join(
                'sales_shipment_instruction as ssi',
                'main_table.entity_id = ssi.csv_export_id AND ssi.seller_id='.$sellerId,
                []
            )->group('ssi.csv_export_id');

        parent::_renderFiltersBefore();
    }
}
