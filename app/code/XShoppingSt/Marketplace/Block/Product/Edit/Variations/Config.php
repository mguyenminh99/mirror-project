<?php
namespace XShoppingSt\Marketplace\Block\Product\Edit\Variations;

/**
 * Marketplace catalog super product configurable.
 */
class Config extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry                      $registry
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }

    public function getSellerProduct()
    {
        return $this->_registry->registry('product');
    }
}
