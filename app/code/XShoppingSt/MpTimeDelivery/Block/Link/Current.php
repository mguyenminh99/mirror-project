<?php
namespace XShoppingSt\MpTimeDelivery\Block\Link;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\DefaultPathInterface;
use XShoppingSt\Marketplace\Helper\Data;

class Current extends \Magento\Framework\View\Element\Html\Link\Current
{
    /**
     * @var \XShoppingSt\Marketplace\Helper\Data
     */
    protected $helper;

    /**
     * Constructor
     *
     * @param Context               $context
     * @param DefaultPathInterface  $defaultPath
     * @param Data                  $marketplaceHelper
     * @param array                 $data
     */
    public function __construct(
        Context $context,
        DefaultPathInterface $defaultPath,
        Data $marketplaceHelper,
        array $data = []
    ) {
        $this->helper = $marketplaceHelper;
        parent::__construct($context, $defaultPath, $data);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        $isPartner= $this->helper->isSeller();
        $html = '';
        if ($isPartner) {
            return parent::_toHtml();
        }
        return $html;
    }
}
