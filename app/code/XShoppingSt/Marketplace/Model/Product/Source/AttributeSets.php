<?php
namespace XShoppingSt\Marketplace\Model\Product\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class AttributeSets is used tp get attribute sets
 */
class AttributeSets implements OptionSourceInterface
{
    /**
     * @var \XShoppingSt\Marketplace\Helper\Data
     */
    protected $marketplaceHelper;

    /**
     * Constructor
     *
     * @param \XShoppingSt\Marketplace\Helper\Data $marketplaceHelper
     */
    public function __construct(
        \XShoppingSt\Marketplace\Helper\Data $marketplaceHelper
    ) {
        $this->marketplaceHelper = $marketplaceHelper;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->marketplaceHelper->getAllowedSets();
        return $availableOptions;
    }
}
