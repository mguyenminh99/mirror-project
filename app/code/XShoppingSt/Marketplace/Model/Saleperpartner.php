<?php
namespace XShoppingSt\Marketplace\Model;

use Magento\Framework\Model\AbstractModel;
use XShoppingSt\Marketplace\Api\Data\SaleperpartnerInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Marketplace Saleperpartner Model.
 *
 * @method \XShoppingSt\Marketplace\Model\ResourceModel\Saleperpartner _getResource()
 * @method \XShoppingSt\Marketplace\Model\ResourceModel\Saleperpartner getResource()
 */
class Saleperpartner extends AbstractModel implements SaleperpartnerInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    const NOROUTE_ENTITY_ID = 'no-route';

    /**
     * Marketplace Saleperpartner cache tag.
     */
    const CACHE_TAG = 'marketplace_saleperpartner';

    /**
     * @var string
     */
    protected $_cacheTag = 'marketplace_saleperpartner';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'marketplace_saleperpartner';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \XShoppingSt\Marketplace\Model\ResourceModel\Saleperpartner::class
        );
    }

    /**
     * Load object data.
     *
     * @param int|null $id
     * @param string   $field
     *
     * @return $this
     */
    public function load($id, $field = null)
    {
        if ($id === null) {
            return $this->noRouteSaleperpartner();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route Saleperpartner.
     *
     * @return \XShoppingSt\Marketplace\Model\Saleperpartner
     */
    public function noRouteSaleperpartner()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
    }

    /**
     * Get identities.
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    /**
     * Get ID.
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * Set ID.
     *
     * @param int $id
     *
     * @return \XShoppingSt\Marketplace\Api\Data\SaleperpartnerInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
