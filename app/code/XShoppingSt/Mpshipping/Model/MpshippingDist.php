<?php
namespace XShoppingSt\Mpshipping\Model;

use XShoppingSt\Mpshipping\Api\Data\MpshippingDistInterface;
use Magento\Framework\DataObject\IdentityInterface;
use \Magento\Framework\Model\AbstractModel;

class MpshippingDist extends AbstractModel implements MpshippingDistInterface, IdentityInterface
{
    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'x_shopping_st_shipping_dist';
    /**
     * @var string
     */
    protected $_cacheTag = 'x_shopping_st_shipping_dist';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'x_shopping_st_shipping_dist';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\XShoppingSt\Mpshipping\Model\ResourceModel\MpshippingDist::class);
    }
    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getEntityId()];
    }
    /**
     * Get ID
     *
     * @return int|null
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }
    /**
     * Set ID
     *
     * @return \XShoppingSt\Mpshipping\Api\Data\MpshippingDistInterface
     */
    public function setEntityId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
    /**
     * Get Price From
     *
     * @return float|null
     */
    public function getPriceFrom()
    {
        return $this->getData(self::PRICE_FROM);
    }
    /**
     * Set Price From
     *
     * @return \XShoppingSt\Mpshipping\Api\Data\MpshippingDistInterface
     */
    public function setPriceFrom($priceFrom)
    {
        return $this->setData(self::PRICE_FROM, $priceFrom);
    }
    /**
     * Get Price To
     *
     * @return float|null
     */
    public function getPriceTo()
    {
        return $this->getData(self::PRICE_TO);
    }
    /**
     * Set Price To
     *
     * @return \XShoppingSt\Mpshipping\Api\Data\MpshippingDistInterface
     */
    public function setPriceTo($priceTo)
    {
        return $this->setData(self::PRICE_TO, $priceTo);
    }
    /**
     * Get Distance From
     *
     * @return float|null
     */
    public function getDistFrom()
    {
        return $this->getData(self::DIST_FROM);
    }
    /**
     * Set Distance From
     *
     * @return \XShoppingSt\Mpshipping\Api\Data\MpshippingDistInterface
     */
    public function setDistFrom($distFrom)
    {
        return $this->setData(self::DIST_FROM, $distFrom);
    }
    /**
     * Get Distance To
     *
     * @return float|null
     */
    public function getDistTo()
    {
        return $this->getData(self::DIST_TO);
    }
    /**
     * Set Distance To
     *
     * @return \XShoppingSt\Mpshipping\Api\Data\MpshippingDistInterface
     */
    public function setDistTo($distTo)
    {
        return $this->setData(self::DIST_TO, $distTo);
    }
    /**
     * Get Price
     *
     * @return float|null
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }
    /**
     * Set Price
     *
     * @return \XShoppingSt\Mpshipping\Api\Data\MpshippingDistInterface
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }
    /**
     * Get Seller Id
     *
     * @return int|null
     */
    public function getPartnerId()
    {
        return $this->getData(self::PARTNER_ID);
    }
    /**
     * Set Seller Id
     *
     * @return \XShoppingSt\Mpshipping\Api\Data\MpshippingDistInterface
     */
    public function setPartnerId($partnerId)
    {
        return $this->setData(self::PARTNER_ID, $partnerId);
    }
    /**
     * Get Method Id
     *
     * @return int|null
     */
    public function getShippingMethodId()
    {
        return $this->getData(self::SHIPPING_METHOD_ID);
    }
    /**
     * Set Method Id
     *
     * @return \XShoppingSt\Mpshipping\Api\Data\MpshippingDistInterface
     */
    public function setShippingMethodId($methodId)
    {
        return $this->setData(self::SHIPPING_METHOD_ID, $methodId);
    }
}
