<?php
namespace XShoppingSt\Marketplace\Block\Order\Additional;

class Info extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Sales\Model\Order\Item
     */
    protected $_item;

    /**
     * @param \Magento\Sales\Model\Order\Item $item
     * @return $this
     * @codeCoverageIgnore
     */
    public function setItem(\Magento\Sales\Model\Order\Item $item)
    {
        $this->_item = $item;
        return $this;
    }

    /**
     * @return \Magento\Sales\Model\Order\Item
     * @codeCoverageIgnore
     */
    public function getItem()
    {
        return $this->_item;
    }
}
