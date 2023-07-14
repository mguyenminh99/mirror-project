<?php
namespace XShoppingSt\Marketplace\Model\Feedback\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status is used tp get the Feedback available status
 */
class Status implements OptionSourceInterface
{
    /**
     * @var \XShoppingSt\Marketplace\Model\Feedback
     */
    protected $marketplaceFeedback;

    /**
     * Constructor
     *
     * @param \Magento\Cms\Model\Page $cmsPage
     */
    public function __construct(\XShoppingSt\Marketplace\Model\Feedback $marketplaceFeedback)
    {
        $this->marketplaceFeedback = $marketplaceFeedback;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->marketplaceFeedback->getAvailableStatuses();
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
