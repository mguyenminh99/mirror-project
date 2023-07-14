<?php
namespace XShoppingSt\MarketplaceBaseShipping\Block;

class Dimension extends \Magento\Framework\View\Element\Template
{
    private $helperData;
    public function __construct(
        \XShoppingSt\MarketplaceBaseShipping\Helper\Data $helperData
    ) {
        $this->helperData= $helperData;
    }
    public function getDimensionsUnit()
    {
        return $this->helperData->getDimensionsUnit();
    }
}
