<?php
namespace XShoppingSt\MpApi\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface SaleslistInterface
{
  /**
   * get seller details.
   * @api
   * @param int $id
   * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
   * @return XShoppingSt\MpApi\Api\Data\SellerResultsInterface
   */
    public function getList($id, \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
