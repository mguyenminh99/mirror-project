<?php
namespace XShoppingSt\Marketplace\Block\Wysiwyg\Helper\Form\Gallery;
use XShoppingSt\Marketplace\Api\Data\WysiwygImageInterfaceFactory;

class Content extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\File\Size
     */
    protected $fileSize;

    /**
     * @var WysiwygImageInterfaceFactory
     */
    protected $wysiwygImage;

    /**
     * @var \XShoppingSt\Marketplace\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Backend\Block\Template\Context     $context
     * @param \Magento\Framework\File\Size                $fileSize
     * @param WysiwygImageInterfaceFactory                $wysiwygImage
     * @param \XShoppingSt\Marketplace\Helper\Data             $helper
     * @param array                                       $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\File\Size $fileSize,
        WysiwygImageInterfaceFactory $wysiwygImage,
        \XShoppingSt\Marketplace\Helper\Data $helper,
        array $data = []
    ) {
        $this->_fileSizeService = $fileSize;
        $this->wysiwygImage = $wysiwygImage;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }
    /**
     * @return \Magento\Framework\File\Size
     */
    public function getFileSizeService()
    {
        return $this->_fileSizeService;
    }
    /**
     * saveImageDesc function
     * @return \XShoppingSt\Marketplace\Model\WysiwygImage
     */
    public function saveImageDesc()
    {
        $sellerId = $this->helper->getCustomerId();
        $wysiwygImage = $this->wysiwygImage->create()
                    ->getCollection()
                    ->addFieldToFilter("seller_id",$sellerId);
        return $wysiwygImage;
    }
}
