<?php
namespace XShoppingSt\MpAssignProduct\Block\Product;

class Add extends \Magento\Framework\View\Element\Template
{
    /**
     * @return $this
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Assign Product'));
    }
}
