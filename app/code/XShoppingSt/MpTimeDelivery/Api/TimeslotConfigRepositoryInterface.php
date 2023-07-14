<?php
namespace XShoppingSt\MpTimeDelivery\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * time slots block CRUD interface.
 *
 * @api
 */
interface TimeslotConfigRepositoryInterface
{
    /**
     * Save TimeSlot Configuration.
     *
     * @param  \XShoppingSt\MpTimeDelivery\Api\Data\TimeslotConfigInterface $items
     * @return \XShoppingSt\MpTimeDelivery\Api\Data\TimeslotConfigInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\TimeslotConfigInterface $items);

    /**
     * Retrieve TimeSlot Configuration.
     *
     * @param  int $id
     * @return \XShoppingSt\MpTimeDelivery\Api\Data\TimeslotConfigInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($id);

    /**
     * Retrieve TimeSlot Configuration matching the specified criteria.
     *
     * @param  \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \XShoppingSt\MpTimeDelivery\Api\Data\TimeslotConfigSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete TimeSlot Configuration.
     *
     * @param  \XShoppingSt\MpTimeDelivery\Api\Data\TimeslotConfigInterface $item
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\TimeslotConfigInterface $item);

    /**
     * Delete TimeSlot Configuration by ID.
     *
     * @param  int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id);
}
