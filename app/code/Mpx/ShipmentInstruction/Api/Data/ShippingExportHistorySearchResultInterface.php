<?php
namespace Mpx\ShipmentInstruction\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ShippingExportHistorySearchResultInterface extends SearchResultsInterface
{
    /**
     * Get items
     *
     * @return \Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface[]
     */
    public function getItems();

    /**
     * Set items
     *
     * @param \Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
