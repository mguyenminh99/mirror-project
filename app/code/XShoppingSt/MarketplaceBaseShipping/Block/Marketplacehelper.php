<?php
namespace XShoppingSt\MarketplaceBaseShipping\Block;

class Marketplacehelper extends \Magento\Framework\View\Element\Template
{
    private $helperData;
    public function __construct(
        \XShoppingSt\Marketplace\Helper\Data $helperData
    ) {
        $this->helperData= $helperData;
    }
    public function helperObj()
    {
        return $this->helperData ;
    }
}
