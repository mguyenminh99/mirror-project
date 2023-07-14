<?php
namespace XShoppingSt\Marketplace\Model\Seller\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status is used tp get the seller available status
 */
class Status implements OptionSourceInterface
{
    /**
     * @var \XShoppingSt\Marketplace\Model\Seller
     */
    protected $marketplaceSeller;

    /**
     * Constructor
     *
     * @param \Magento\Cms\Model\Page $cmsPage
     */
    public function __construct(\XShoppingSt\Marketplace\Model\Seller $marketplaceSeller)
    {
        $this->marketplaceSeller = $marketplaceSeller;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->marketplaceSeller->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
