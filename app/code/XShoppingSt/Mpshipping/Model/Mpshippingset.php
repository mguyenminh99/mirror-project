<?php
namespace XShoppingSt\Mpshipping\Model;

use XShoppingSt\Mpshipping\Api\Data\MpshippingSetInterface;
use Magento\Framework\DataObject\IdentityInterface;
use \Magento\Framework\Model\AbstractModel;

class Mpshippingset extends AbstractModel implements MpshippingSetInterface, IdentityInterface
{
    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'x_shopping_st_shipping';
    /**
     * @var string
     */
    protected $_cacheTag = 'x_shopping_st_shipping';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'x_shopping_st_shipping';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\XShoppingSt\Mpshipping\Model\ResourceModel\Mpshippingset::class);
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
    public function setEntityId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
