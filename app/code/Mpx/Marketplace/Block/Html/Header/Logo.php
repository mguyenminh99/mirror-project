<?php

namespace Mpx\Marketplace\Block\Html\Header;

/**
 * Logo page header block
 */
class Logo extends \Magento\Theme\Block\Html\Header\Logo
{
    /**
     * Current template name
     *
     * @var string
     */
    protected $_template = 'Magento_Theme::html/header/logo.phtml';

    /**
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    protected $_fileStorageHelper;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageHelper
     * @param \Magento\Framework\App\Request\Http $request
     * @param array $data
     */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context   $context,
        \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageHelper,
        \Magento\Framework\App\Request\Http                $request,
        array $data = []
    ) {
        $this->_fileStorageHelper = $fileStorageHelper;
        $this->request = $request;
        parent::__construct(
            $context,
            $fileStorageHelper,
            $data
        );
    }

    /**
     * Get Full Action Name
     *
     * @return string
     */

    public function getFullActionName()
    {
        $action = $this->request->getFullActionName();
        return $action;
    }
}
