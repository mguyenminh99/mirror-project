<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Catalog
 * @author    Mpx
 */

namespace Mpx\Catalog\Plugin\Catalog;

use Magento\Catalog\Model\ResourceModel\Category;
use Magento\Framework\App\Request\Http;

class IncludeHiddenCategory
{
    /**
     * @var Category\TreeFactory
     */

    protected $_categoryTreeFactory;

    /**
     * @param Category\TreeFactory $categoryTreeFactory
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\TreeFactory $categoryTreeFactory,
        Http $request
    ) {
        $this->_categoryTreeFactory = $categoryTreeFactory;
        $this->request = $request;
    }

    /**
     * Retrieve categories
     *
     * @param Category $subject
     * @param callable $process
     * @param integer $parent
     * @param integer $recursionLevel
     * @param boolean|string $sorted
     * @param boolean $asCollection
     * @param boolean $toLoad
     * @return Category\Collection|\Magento\Framework\Data\Tree\Node\Collection
     */
    public function aroundGetCategories(
        Category $subject,
        callable $process,
        $parent,
        $recursionLevel = 0,
        $sorted = false,
        $asCollection = false,
        $toLoad = true
    ) {

        $tree = $this->_categoryTreeFactory->create();

        /* @var $tree \Magento\Catalog\Model\ResourceModel\Category\Tree */
        $nodes = $tree->loadNode($parent)->loadChildren($recursionLevel)->getChildren();

        if ($this->request->getFullActionName() == 'marketplace_product_add' ||
            $this->request->getFullActionName() == 'marketplace_product_edit' ) {

            $tree->getCollection()->addAttributeToFilter('is_active', 1);
            $tree->addCollectionData(null, $sorted, $parent, $toLoad, false);
        } else {
            $tree->addCollectionData(null, $sorted, $parent, $toLoad, true);
        }

        if ($asCollection) {
            return $tree->getCollection();
        }
        return $nodes;
    }
}
