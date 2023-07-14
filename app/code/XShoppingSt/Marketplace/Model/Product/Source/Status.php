<?php
namespace XShoppingSt\Marketplace\Model\Product\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status is used tp get the product available status
 */
class Status implements OptionSourceInterface
{
    /**
     * @var \XShoppingSt\Marketplace\Model\Product
     */
    protected $marketplaceProduct;

    /**
     * Constructor
     *
     * @param \Magento\Cms\Model\Page $cmsPage
     */
    public function __construct(\XShoppingSt\Marketplace\Model\Product $marketplaceProduct)
    {
        $this->marketplaceProduct = $marketplaceProduct;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->marketplaceProduct->getAvailableStatuses();
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
