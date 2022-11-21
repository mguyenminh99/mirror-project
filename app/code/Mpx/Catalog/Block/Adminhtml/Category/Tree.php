<?php

/**
 * Categories tree block
 */
namespace Mpx\Catalog\Block\Adminhtml\Category;

class Tree extends \Magento\Catalog\Block\Adminhtml\Category\Tree
{

    /**
     * Hidden button Add Root Category
     *
     * @return $this|\Magento\Catalog\Block\Adminhtml\Category\Tree|Tree
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->unsetChild('add_root_button');
        return $this;
    }
}
