<?php
namespace Mpx\ShipmentInstruction\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Mpx\ShipmentInstruction\Api\Data\ShippingExportHistorySearchResultInterface;

interface ShippingExportHistoryRepositoryInterface
{
    /**
     * Save Shipping Export History
     *
     * @param \Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface $shippingExportHistory
     * @return \Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface $shippingExportHistory
     */
    public function save(\Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface $shippingExportHistory);

    /**
     * Retrieve Shipping Export History
     *
     * @param int $id
     * @return \Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id);

    /**
     * Delete Shipping Export History
     *
     * @param \Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface $shippingExportHistory
     * @return bool
     */
    public function delete(\Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface $shippingExportHistory);

    /**
     * Delete Shipping Export History by id
     *
     * @param int $id
     * @return bool true on success
     */
    public function deleteById($id);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return ShippingExportHistorySearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
