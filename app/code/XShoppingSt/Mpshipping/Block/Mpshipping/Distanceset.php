<?php
namespace XShoppingSt\Mpshipping\Block\Mpshipping;

use Magento\Catalog\Block\Product\AbstractProduct;
use XShoppingSt\Mpshipping\Model\MpshippingmethodFactory;

class Distanceset extends AbstractProduct
{
    /**
     * @var XShoppingSt\Mpshipping\Helper\Data
     */
    protected $_mpshippingHelperData;
    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    protected $localeCurrency;
    /**
     * @var XShoppingSt\Marketplace\Helper\Data
     */
    protected $_mpHelperData;
    /**
     * @var \XShoppingSt\Mpshipping\Model\MpshippingDistFactory
     */
    protected $mpShippingDist;
    /**
     * @var XShoppingSt\Mpshipping\Model\MpshippingmethodFactory
     */
    protected $_mpshippingMethod;
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \XShoppingSt\Mpshipping\Helper\Data $mpshippingHelperData
     * @param \Magento\Framework\Locale\CurrencyInterface $localeCurrency
     * @param \XShoppingSt\Marketplace\Helper\Data $mpHelperData
     * @param \XShoppingSt\Mpshipping\Model\MpshippingDistFactory $mpShippingDist
     * @param MpshippingmethodFactory $shippingmethodFactory
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \XShoppingSt\Mpshipping\Helper\Data $mpshippingHelperData,
        \Magento\Framework\Locale\CurrencyInterface $localeCurrency,
        \XShoppingSt\Marketplace\Helper\Data $mpHelperData,
        \XShoppingSt\Mpshipping\Model\MpshippingDistFactory $mpShippingDist,
        MpshippingmethodFactory $shippingmethodFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_mpshippingHelperData = $mpshippingHelperData;
        $this->localeCurrency = $localeCurrency;
        $this->_mpHelperData = $mpHelperData;
        $this->mpShippingDist = $mpShippingDist;
        $this->_mpshippingMethod = $shippingmethodFactory;
        $this->jsonHelper = $jsonHelper;
    }
    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->_mpshippingHelperData->getPartnerId();
    }
    /**
     * getDistShippingCollection function
     *
     * @return \XShoppingSt\Mpshipping\Model\ResourceModel\MpshippingDist\Collection
     */
    public function getDistShippingCollection()
    {
        $paramData = $this->getRequest()->getParams();
        $filter = $paramData['s'] ?? '';
        $filterPriceFrom = $paramData['price_from'] ?? '';
        $filterPriceTo = $paramData['price_to'] ?? '';
        $distanceFrom = $paramData['distance_from'] ?? '';
        $distanceTo = $paramData['distance_to'] ?? '';
        $partnerId = $this->getCustomerId();
        $methodId = 0;
        if ($filter) {
            $methodId = $this->getMethodIdByName($filter);
        }
        $shippingSetCollection = $this->mpShippingDist
            ->create()
            ->getCollection()
            ->addFieldToFilter('partner_id', ['eq'=>$partnerId]);
        if ($filter) {
            $shippingSetCollection->addFieldToFilter('shipping_method_id', ['eq',$methodId]);
        }
        if ($filterPriceFrom) {
            $shippingSetCollection->addFieldToFilter('price_from', ['gteq',$filterPriceFrom]);
        }
        if ($filterPriceTo) {
            $shippingSetCollection->addFieldToFilter('price_to', ['lteq',$filterPriceTo]);
        }
        if ($distanceFrom) {
            $shippingSetCollection->addFieldToFilter('dist_from', ['gteq',$distanceFrom]);
        }
        if ($distanceTo) {
            $shippingSetCollection->addFieldToFilter('dist_to', ['lteq',$distanceTo]);
        }
        return $shippingSetCollection;
    }
    /**
     * getMethodIdByName function
     *
     * @param string $methodName
     * @return int
     */
    public function getMethodIdByName($methodName)
    {
        $methodId= 0;
        $shippingMethodModel = $this->_mpshippingMethod->create()
                                  ->getCollection()
                                  ->addFieldToFilter('method_name', ['eq'=>$methodName]);
        foreach ($shippingMethodModel as $shippingMethod) {
            $methodId = $shippingMethod->getEntityId();
        }
        return $methodId;
    }
    /**
     * getShippingMethodName function
     *
     * @param int $shippingMethodId
     * @return string
     */
    public function getShippingMethodName($shippingMethodId)
    {
        $methodName = '';
        $shippingMethodModel = $this->_mpshippingMethod->create()
            ->getCollection()
            ->addFieldToFilter('entity_id', $shippingMethodId);
        foreach ($shippingMethodModel as $shippingMethod) {
            $methodName = $shippingMethod->getMethodName();
        }
        return $methodName;
    }
    /**
     * getBaseCurrencySymbol function
     *
     * @return string
     */
    public function getBaseCurrencySymbol()
    {
        $currencycode = $this->_storeManager->getStore()->getBaseCurrencyCode();
        $currency = $this->localeCurrency->getCurrency($currencycode);
        return $currency->getSymbol() ? $currency->getSymbol() : $currency->getShortName();
    }
    /**
     * [Get Json Helper]
     */
    public function getJsonHelper()
    {
        return $this->jsonHelper;
    }
}
