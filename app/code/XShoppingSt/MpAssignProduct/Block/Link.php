<?php
namespace XShoppingSt\MpAssignProduct\Block;

use Magento\Framework\View\Element\Html\Link\Current;
use XShoppingSt\Marketplace\Helper\Data as MpHelper;

class Link extends \Magento\Framework\View\Element\Html\Link\Current
{
    /**
     * @var \Magento\Framework\View\Element\Template\Context
     */
    protected $context;

    /**
     * @var \XShoppingSt\MpAssignProduct\Helper\Data
     */
    protected $defaultPathInterface;

    /**
     * @var \XShoppingSt\MpAssignProduct\Helper\Data
     */
    protected $helper;

    /**
     * @var \MpHelper
     */
    protected $mpHelper;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $pricingHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\DefaultPathInterface $defaultPathInterface
     * @param \XShoppingSt\MpAssignProduct\Helper\Data $helper
     * @param MpHelper $mpHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\DefaultPathInterface $defaultPathInterface,
        \XShoppingSt\MpAssignProduct\Helper\Data $helper,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        MpHelper $mpHelper,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->pricingHelper = $pricingHelper;
        $this->mpHelper = $mpHelper;
        parent::__construct($context, $defaultPathInterface, $data);
    }

    /**
     * Render block HTML.
     *
     * @return string
     */
    protected function _toHtml()
    {
        $helper = $this->helper;
        if (!$helper->isSeller()) {
            return '';
        }
        return parent::_toHtml();
    }

    /**
     * Get current Url
     *
     * @return url
     */
    public function getCurrentUrl()
    {
        return $this->_urlBuilder->getCurrentUrl();
    }

    /**
     * Get Helper Object
     *
     * @return object
     */
    public function getHelperObject($helper = '')
    {
        if ($helper == 'marketplaceHelper') {
            return $this->mpHelper;
        } elseif ($helper == 'pricingHelper') {
            return $this->pricingHelper;
        } else {
            return $this->helper;
        }
    }
}
