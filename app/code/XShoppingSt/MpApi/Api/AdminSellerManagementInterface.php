<?php
namespace XShoppingSt\MpApi\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface AdminSellerManagementInterface
{
    /**
     * get seller details.
     *
     * @api
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return XShoppingSt\MpApi\Api\Data\AdminSellerResultsInterface
     */
    public function getSellerList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
