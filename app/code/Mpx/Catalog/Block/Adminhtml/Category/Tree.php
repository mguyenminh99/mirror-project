<?php

/**
 * Categories tree block
 */
namespace Mpx\Catalog\Block\Adminhtml\Category;

class Tree extends \Magento\Catalog\Block\Adminhtml\Category\Tree
{

    /**
     * Get tree json
     *
     * @param mixed|null $parenNodeCategory
     * @return string
     */
    public function getTreeJson($parenNodeCategory = null)
    {
        $rootArray = $this->_getNodeJson($this->getRoot($parenNodeCategory));
        $rootArray['children']['0']['cls'] = 'hidden';
        $json = $this->_jsonEncoder->encode(isset($rootArray['children']) ? $rootArray['children'] : []);
        return $json;
    }

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
