<?php

namespace Mpx\ShipmentInstruction\Model;

use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface;
use Mpx\ShipmentInstruction\Api\Data\ShippingExportHistorySearchResultInterface;
use Mpx\ShipmentInstruction\Api\Data\ShippingExportHistorySearchResultInterfaceFactory as SearchResultFactory;
use Mpx\ShipmentInstruction\Model\ResourceModel\ShippingExportHistory as ShippingExportHistoryResource;
use Mpx\ShipmentInstruction\Model\ResourceModel\ShippingExportHistory\CollectionFactory;

class ShippingExportHistoryRepository implements \Mpx\ShipmentInstruction\Api\ShippingExportHistoryRepositoryInterface
{
    /**
     * @var ShippingExportHistoryFactory
     */
    protected $shippingExportHistoryFactory;

    /**
     * @var ShippingExportHistoryResource
     */
    protected $shippingExportHistoryResource;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var SearchResultFactory
     */
    protected $searchResultFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    public function __construct(
        ShippingExportHistoryFactory $shippingExportHistoryFactory,
        ShippingExportHistoryResource $shippingExportHistoryResource,
        CollectionFactory $collectionFactory,
        SearchResultFactory $searchResultFactory,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->shippingExportHistoryFactory = $shippingExportHistoryFactory;
        $this->shippingExportHistoryResource = $shippingExportHistoryResource;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultFactory = $searchResultFactory;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * @param ShippingExportHistoryInterface $shippingExportHistory
     * @return \Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface|void
     */
    public function save(ShippingExportHistoryInterface $shippingExportHistory)
    {
        try {
            $this->shippingExportHistoryResource->save($shippingExportHistory);
        } catch (Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $shippingExportHistory;
    }

    /**
     * @param int $id
     * @return ShippingExportHistoryInterface
     * @throws NoSuchEntityException
     */
    public function get($id)
    {
        $shippingExportHistory = $this->shippingExportHistoryFactory->create();
        $this->shippingExportHistoryResource->load($shippingExportHistory, $id);
        if (!$shippingExportHistory->getId()) {
            throw new NoSuchEntityException(__('The shipment instruction with the "%1" ID doesn\'t exist.', $id));
        }
        return $shippingExportHistory;
    }

    public function delete(ShippingExportHistoryInterface $shippingExportHistory)
    {
        try {
            $this->shippingExportHistoryResource->delete($shippingExportHistory);
        } catch (Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }
        return true;
    }

    /**
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        return $this->delete($this->get($id));
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return ShippingExportHistorySearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * get collection processor
     *
     * @return \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = ObjectManager::getInstance()->get(
                \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface::class
            );
        }
        return $this->collectionProcessor;
    }
}
