<?php

namespace Mpx\Customize404Page\Block;

use Magento\Framework\View\Element\Template;

class PageNotFound extends Template
{
    /**
     * @param Template\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        Template\Context                                                   $context,
        \Magento\Framework\App\Config\ScopeConfigInterface                 $scopeConfig,
        array                                                              $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Content Title Value
     *
     * @return mixed
     */
    public function getContentTitle()
    {
        $contentTitle = $this->scopeConfig->getValue(
            'customize_404_page/content_title/content',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $contentTitle;
    }

    /**
     * Get Content Body Value
     *
     * @return mixed
     */
    public function getContentBody()
    {
        $contentBody = $this->scopeConfig->getValue(
            'customize_404_page/content_body/content',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $contentBody;
    }
}
