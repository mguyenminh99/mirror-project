<?php
namespace XShoppingSt\MpMassUpload\Block;

use Magento\Framework\View\Element\Html\Link\Current;
use XShoppingSt\Marketplace\Helper\Data;

class Link extends \Magento\Framework\View\Element\Html\Link\Current
{
    /**
     * @var \XShoppingSt\Marketplace\Helper\Data
     */
    protected $mpHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\DefaultPathInterface $defaultPath
     * @param \XShoppingSt\Marketplace\Helper\Data $mpHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\DefaultPathInterface $defaultPath,
        \XShoppingSt\Marketplace\Helper\Data $mpHelper,
        array $data = []
    ) {
            parent::__construct($context, $defaultPath, $data);
            $this->_mpHelper = $mpHelper;
    }
    /**
     * Render block HTML.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->_mpHelper->isSeller()) {
            return '';
        }
        return parent::_toHtml();
    }

    public function getCurrentUrl()
    {
        return $this->_urlBuilder->getCurrentUrl(); // Give the current url of recently viewed page
    }
}
