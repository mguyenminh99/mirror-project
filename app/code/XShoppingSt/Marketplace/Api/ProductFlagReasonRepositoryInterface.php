<?php
namespace XShoppingSt\Marketplace\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductFlagReasonRepositoryInterface
{
    /**
     * Save ProductFlag
     * @param \XShoppingSt\Marketplace\Api\Data\ProductFlagReasonInterface $productFlagReason
     * @return \XShoppingSt\Marketplace\Api\Data\ProductFlagReasonInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \XShoppingSt\Marketplace\Api\Data\ProductFlagReasonInterface $productFlagReason
    );

    /**
     * Retrieve ProductFlag
     * @param int $entityId
     * @return \XShoppingSt\Marketplace\Api\Data\ProductFlagReasonInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve ProductFlag matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \XShoppingSt\Marketplace\Api\Data\ProductFlagSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete ProductFlag
     * @param \XShoppingSt\Marketplace\Api\Data\ProductFlagReasonInterface $productFlagReason
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \XShoppingSt\Marketplace\Api\Data\ProductFlagReasonInterface $productFlagReason
    );

    /**
     * Delete ProductFlag by ID
     * @param string $entityId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
