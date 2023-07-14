<?php
namespace XShoppingSt\Marketplace\Block;

/*
 * XShoppingSt Marketplace Seller Location Block
 */
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use XShoppingSt\Marketplace\Helper\Data as MpHelper;

class Location extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \XShoppingSt\Marketplace\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;

    /**
     * @var \Magento\Customer\Model\Sessiom
     */
    protected $session;

    /**
     * @var \Magento\Framework\Stdlib\StringUtils
     */
    protected $stringUtils;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \XShoppingSt\Marketplace\Helper\Data                  $helper
     * @param Customer                                         $customer
     * @param \Magento\Framework\Stdlib\StringUtils            $stringUtils
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \XShoppingSt\Marketplace\Helper\Data $helper,
        Customer $customer,
        \Magento\Customer\Model\Session $session,
        \Magento\Framework\Stdlib\StringUtils $stringUtils,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->Customer = $customer;
        $this->Session = $session;
        $this->stringUtils = $stringUtils;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $partner = $this->getProfileDetail();
        if ($partner) {
            $title = $partner->getShopTitle();
            if (!$title) {
                $title = __('Marketplace Seller Location');
            }
            $this->pageConfig->getTitle()->set($title);
            $description = $partner->getMetaDescription();
            if ($description) {
                $this->pageConfig->setDescription($description);
            } else {
                $this->pageConfig->setDescription(
                    $this->stringUtils->substr($partner->getCompanyDescription(), 0, 255)
                );
            }
            $keywords = $partner->getMetaKeywords();
            if ($keywords) {
                $this->pageConfig->setKeywords($keywords);
            }

            $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
            if ($pageMainTitle && $title) {
                $pageMainTitle->setPageTitle($title);
            }

            $this->pageConfig->addRemotePageAsset(
                $this->_urlBuilder->getCurrentUrl(''),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
        }

        return $this;
    }

    /**
     * Get Seller Profile Details
     *
     * @return \XShoppingSt\Marketplace\Model\Seller | bool
     */
    public function getProfileDetail()
    {
        return $this->helper->getProfileDetail(MpHelper::URL_TYPE_LOCATION);
    }
}
