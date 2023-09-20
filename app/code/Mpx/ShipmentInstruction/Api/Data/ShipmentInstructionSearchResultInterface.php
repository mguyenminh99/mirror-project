<?php
namespace Mpx\ShipmentInstruction\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ShipmentInstructionSearchResultInterface extends SearchResultsInterface
{
    /**
     * Get items
     *
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface[]
     */
    public function getItems();

    /**
     * Set items
     *
     * @param \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
