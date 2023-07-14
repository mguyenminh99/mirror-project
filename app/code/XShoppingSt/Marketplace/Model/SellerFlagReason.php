<?php
namespace XShoppingSt\Marketplace\Model;

use XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

class SellerFlagReason extends \Magento\Framework\Model\AbstractModel implements SellerFlagReasonInterface
{
    /**
     * Marketplace SellerFlagReason cache tag.
     */
    const CACHE_TAG = 'marketplace_sellerflagreason';

    /**
     * @var string
     */
    protected $_cacheTag = 'marketplace_sellerflagreason';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'marketplace_sellerflagreason';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\XShoppingSt\Marketplace\Model\ResourceModel\SellerFlagReason::class);
    }

    /**
     * Get entity_id
     * @return string
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set entity_id
     * @param string $entityId
     * @return \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get reason
     * @return string
     */
    public function getReason()
    {
        return $this->getData(self::REASON);
    }

    /**
     * Set reason
     * @param string $reason
     * @return \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface
     */
    public function setReason($reason)
    {
        return $this->setData(self::REASON, $reason);
    }

    /**
     * Get CreatedAt
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set CreatedAt
     * @param string $timestamp
     * @return \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface
     */
    public function setCreatedAt($timestamp)
    {
        return $this->setData(self::CREATED_AT, $timestamp);
    }

    /**
     * Get UpdatedAt
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set UpdatedAt
     * @param string $timestamp
     * @return \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface
     */
    public function setUpdatedAt($timestamp)
    {
        return $this->setData(self::UPDATED_AT, $timestamp);
    }

    /**
     * Get status
     * @return int
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set status
     * @param int $status
     * @return \XShoppingSt\Marketplace\Api\Data\SellerFlagReasonInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
}
