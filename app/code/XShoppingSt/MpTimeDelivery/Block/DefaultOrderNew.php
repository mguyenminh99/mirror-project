<?php
namespace XShoppingSt\MpTimeDelivery\Block;

use Magento\Framework\View\Element\Template;

class DefaultOrderNew extends \Magento\Sales\Block\Order\Email\Items\Order\DefaultOrder
{

     /**
      * Get config
      *
      * @param  string $path
      * @return string|null
      */
    public function getConfig($path)
    {
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
