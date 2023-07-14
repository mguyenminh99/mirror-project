<?php
namespace XShoppingSt\MpTimeDelivery\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for time slots search results.
 *
 * @api
 */
interface TimeslotConfigSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Time Slot Config list.
     *
     * @return \XShoppingSt\MpTimeDelivery\Api\Data\TimeslotConfigInterface[]
     */
    public function getItems();

    /**
     * Set Time Slot Config list.
     *
     * @param  \XShoppingSt\MpTimeDelivery\Api\Data\TimeslotConfigInterface[] $items
     * @return \XShoppingSt\MpTimeDelivery\Api\Data\TimeslotConfigSearchResultsInterface
     */
    public function setItems(array $items);
}
