<?php
namespace XShoppingSt\Mpshipping\ViewModel;

class LocationModel implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * Helper
     *
     * @var \XShoppingSt\Marketplace\Helper\Data
     */
    public $helper;

    /**
     * Mpshipping Helper
     *
     * @var \XShoppingSt\Mpshipping\Helper\Data
     */
    public $mpHelper;

    /**
     * @param \XShoppingSt\Mpshipping\Helper\Data $mpHelper
     * @param \XShoppingSt\Marketplace\Helper\Data $helper
     */
    public function __construct(
        \XShoppingSt\Mpshipping\Helper\Data $mpHelper,
        \XShoppingSt\Marketplace\Helper\Data $helper
    ) {
        $this->mpHelper = $mpHelper;
        $this->helper = $helper;
    }

    public function getMpHelper()
    {
        return $this->helper;
    }

    public function getHelper()
    {
        return $this->mpHelper;
    }
}
