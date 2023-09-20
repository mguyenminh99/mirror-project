<?php
namespace Mpx\ShipmentInstruction\Model\ResourceModel\ShipmentInstruction\Grid;

use Mpx\ShipmentInstruction\Model\ResourceModel\ShipmentInstruction\Collection as ShipmentInstruction;
use XShoppingSt\Marketplace\Helper\Data;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Session\SessionManagerInterface;
use Mpx\ShipmentInstruction\Helper\Constant;

class Collection extends ShipmentInstruction implements \Magento\Framework\Api\Search\SearchResultInterface
{
    protected $aggregations;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    // @codingStandardsIgnoreStart
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        Http $request,
        SessionManagerInterface $sessionManager,
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
        $this->request = $request;
        $this->sessionManager = $sessionManager;
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
        if (!$this->request->getParam('sorting')['field']) {
            $this->setOrder('increment_order_id', 'DESC');
            $this->setOrder('product_name', 'ASC');
            $this->setOrder('created_at', 'ASC');
        }
        $sellerId = $this->helperData->getCustomerId();
        if ($this->sessionManager->getData(Constant::SHIPMENT_PAGE_KEY) === Constant::SHIPMENT_INSTRUCTION_GRID_CODE) {
            $this->getSelect()->where("main_table.seller_id = ".$sellerId)
                ->joinLeft(
                    'sales_shipment_shipping_label_export_history as ssh',
                    'main_table.csv_export_id = ssh.entity_id',
                    [
                        'ssh.carrier_code as delivery_company',
                        'ssh.format as format'
                    ]
                );
        } else if ($this->sessionManager->getData(Constant::SHIPMENT_PAGE_KEY) === Constant::EXPORTED_SHIPMENT_INSTRUCTION_GRID_CODE) {
            $this->addFieldToFilter('seller_id', ['eq' => $sellerId])
                ->addFieldToFilter('csv_export_id', ['eq' => $this->sessionManager->getData(Constant::CSV_EXPORT_ID_KEY)]);
            $this->getSelect()
                ->joinLeft(
                    'sales_shipment_shipping_label_export_history as ssh',
                    'main_table.csv_export_id = ssh.entity_id',
                    [
                        'ssh.carrier_code as delivery_company',
                        'ssh.format as format'
                    ]
                );
        } else {
            $this->addFieldToFilter('seller_id', ['eq' => $sellerId])
                ->addFieldToFilter('csv_export_id', ['null' => true]);
        }
        parent::_renderFiltersBefore();
    }

    /**
     * Get Data For Sales Order Grid
     *
     * @return $this|Collection
     */
    protected function _initSelect()
    {
        $tableDescription = $this->getConnection()->describeTable($this->getMainTable());
        foreach ($tableDescription as $columnInfo) {
            $this->addFilterToMap($columnInfo['COLUMN_NAME'], 'main_table.' . $columnInfo['COLUMN_NAME']);
        }
        return parent::_initSelect(); // TODO: Change the autogenerated stub
    }
}
