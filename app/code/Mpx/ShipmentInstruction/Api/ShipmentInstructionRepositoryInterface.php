<?php
namespace Mpx\ShipmentInstruction\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionSearchResultInterface;

interface ShipmentInstructionRepositoryInterface
{
    /**
     * save shipment instruction
     *
     * @param \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface $shipmentInstruction
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface $shipmentInstruction
     */
    public function save(\Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface $shipmentInstruction);

    /**
     * Retrieve shipment instruction
     *
     * @param int $id
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id);

    /**
     * delete shipment instruction
     *
     * @param \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface $shipmentInstruction
     * @return bool
     */
    public function delete(\Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface $shipmentInstruction);

    /**
     * delete shipment instruction by id
     *
     * @param int $id
     * @return bool true on success
     */
    public function deleteById($id);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return ShipmentInstructionSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
