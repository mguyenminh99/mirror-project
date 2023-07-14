<?php
namespace XShoppingSt\MpAssignProduct\Model;

use XShoppingSt\MpAssignProduct\Api\Data\ItemsInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Associates extends \Magento\Framework\Model\AbstractModel implements ItemsInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    const NOROUTE_ENTITY_ID = 'no-route';

    /**
     * Assign Product Items cache tag.
     */
    const CACHE_TAG = 'mpassignproduct_associates';

    /**
     * @var string
     */
    protected $_cacheTag = 'mpassignproduct_associates';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'mpassignproduct_associates';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(\XShoppingSt\MpAssignProduct\Model\ResourceModel\Associates::class);
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
            return $this->noRoutePreorder();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route Items.
     *
     * @return \XShoppingSt\MpAssignProduct\Model\Items
     */
    public function noRouteItems()
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
     * @return \XShoppingSt\MpAssignProduct\Api\Data\ItemsInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
