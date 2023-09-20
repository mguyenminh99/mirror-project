<?php

namespace Mpx\ShipmentInstruction\Model;

use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface;
use Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionSearchResultInterface;
use Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionSearchResultInterfaceFactory as SearchResultFactory;
use Mpx\ShipmentInstruction\Model\ResourceModel\ShipmentInstruction as ShipmentInstructionResource;
use Mpx\ShipmentInstruction\Model\ResourceModel\ShipmentInstruction\CollectionFactory;

class ShipmentInstructionRepository implements \Mpx\ShipmentInstruction\Api\ShipmentInstructionRepositoryInterface
{
    /**
     * @var \Mpx\ShipmentInstruction\Model\ShipmentInstructionFactory
     */
    protected $shipmentInstructionFactory;

    /**
     * @var ShipmentInstructionResource
     */
    protected $shipmentInstructionResource;

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
        ShipmentInstructionFactory $shipmentInstructionFactory,
        ShipmentInstructionResource $shipmentInstructionResource,
        CollectionFactory $collectionFactory,
        SearchResultFactory $searchResultFactory,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->shipmentInstructionFactory = $shipmentInstructionFactory;
        $this->shipmentInstructionResource = $shipmentInstructionResource;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultFactory = $searchResultFactory;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();

    }

    /**
     * @param ShipmentInstructionInterface $shipmentInstruction
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface|void
     */
    public function save(ShipmentInstructionInterface $shipmentInstruction)
    {
        try {
            $this->shipmentInstructionResource->save($shipmentInstruction);
        } catch (Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $shipmentInstruction;
    }

    /**
     * @param int $id
     * @return ShipmentInstructionInterface
     * @throws NoSuchEntityException
     */
    public function get($id)
    {
        $shipmentInstruction = $this->shipmentInstructionFactory->create();
        $this->shipmentInstructionResource->load($shipmentInstruction, $id);
        if (!$shipmentInstruction->getId()) {
            throw new NoSuchEntityException(__('The shipment instruction with the "%1" ID doesn\'t exist.', $id));
        }
        return $shipmentInstruction;
    }

    public function delete(ShipmentInstructionInterface $shipmentInstruction)
    {
        try {
            $this->shipmentInstructionResource->delete($shipmentInstruction);
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
     * @return ShipmentInstructionSearchResultInterface
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
