<?php
namespace XShoppingSt\Marketplace\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductFlagsRepositoryInterface
{
    /**
     * Save ProductFlag
     * @param \XShoppingSt\Marketplace\Api\Data\ProductFlagsInterface $productFlags
     * @return \XShoppingSt\Marketplace\Api\Data\ProductFlagsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \XShoppingSt\Marketplace\Api\Data\ProductFlagsInterface $productFlags
    );

    /**
     * Retrieve ProductFlag
     * @param int $entityId
     * @return \XShoppingSt\Marketplace\Api\Data\ProductFlagsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve ProductFlag matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \XShoppingSt\Marketplace\Api\Data\ProductFlagsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete ProductFlag
     * @param \XShoppingSt\Marketplace\Api\Data\ProductFlagsInterface $productFlags
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \XShoppingSt\Marketplace\Api\Data\ProductFlagsInterface $productFlags
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
