<?php
declare(strict_types=1);

namespace Mpx\PaypalCheckout\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Mpx\PaypalCheckout\Api\Data\PaypalCheckoutInfoInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class PaypalCheckoutInfo extends AbstractModel implements PaypalCheckoutInfoInterface, IdentityInterface
{
    /**
     * PayPal Action
     */
    public const PAYPAL_CHECKOUT_ACTION = [
        "CAPTURE" => "capture",
        "AUTHORIZE" => "authorize"
    ];

    /**
     * PaypalCheckout status
     */
    public const PAYPAL_CHECKOUT_STATUS = [
        "AUTHORIZED" => "authorized",
        "UNPROCESSED" => "unprocessed",
        'CAPTURED' => 'captured'
    ];

    /**
     * Cache tag paypal
     */
    public const CACHE_TAG = 'paypal_checkout_info';

    /**
     * @var DateTime
     */
    protected $time;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'paypal_checkout_info';

    /**
     * @param Context $context
     * @param Registry $registry
     * @param DateTime $time
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context          $context,
        Registry         $registry,
        DateTime         $time,
        AbstractResource $resource = null,
        AbstractDb       $resourceCollection = null,
        array            $data = []
    )
    {
        $this->time = $time;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Init resource model and id field
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(ResourceModel\PaypalCheckoutInfo::class);
        $this->setIdFieldName('id');
    }

    /**
     * Get By IncrementId
     *
     * @param $incrementId
     * @return PaypalCheckoutInfo
     */
    public function getByIncrementId($incrementId): PaypalCheckoutInfo
    {
        return $this->load($incrementId, PaypalCheckoutInfoInterface::ORDER_INCREMENT_ID);
    }

    /**
     * Get cache identities
     *
     * @return array
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get Order Increment Id
     *
     * @return string
     */
    public function getOrderIncrementId(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::ORDER_INCREMENT_ID);
    }

    /**
     * Get Paypal Order Id
     *
     * @return string
     */
    public function getPayPalOrderId(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_ORDER_ID);
    }

    /**
     * Get PayPal Capture Id
     *
     * @return string
     */
    public function getPayPalCaptureId(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_CAPTURE_ID);
    }

    /**
     * Get PayPal Authorization ID
     *
     * @return string
     */
    public function getPayPalAuthorizationId(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZATION_ID);
    }

    /**
     * Get Authorization Period
     *
     * @return string
     */
    public function getPayPalAuthorizationPeriod(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZATION_PERIOD);
    }

    /**
     * Get Honor Period
     *
     * @return string
     */
    public function getPayPalHonorPeriod(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_HONOR_PERIOD);
    }

    /**
     * Get PayPal Authorized At
     *
     * @return string
     */
    public function getPayPalAuthorizedAt(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZED_AT);
    }

    /**
     * Get Capture At
     *
     * @return string
     */
    public function getPayPalCapturedAt(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_CAPTURED_AT);
    }

    /**
     * Get Created At
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::CREATED_AT);
    }

    /**
     * Get Updated At
     *
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::UPDATED_AT);
    }

    /**
     * Get Action
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::ACTION);
    }

    /**
     * Get Status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::STATUS);
    }

    /**
     * Get PayPal Api Request Id
     *
     * @return string
     */
    public function getPayPalApiRequestId(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_API_REQUEST_ID);
    }

    /**
     * Get PayPal Authorization Amount
     *
     * @return string
     */
    public function getPayPalAuthorizationAmount(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZATION_AMOUNT);
    }

    /**
     * Get PayPal Void Id
     *
     * @return string
     */
    public function getPayPalVoidId(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_VOID_ID);
    }

    /**
     * Get PayPal Voided At
     *
     * @return string
     */
    public function getPayPalVoidedAt(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_VOIDED_AT);
    }

    /**
     * Get Capture Amount
     *
     * @return string
     */
    public function getPayPalCapturedAmount(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_CAPTURE_AMOUNT);
    }

    /**
     * Get Refund Amount
     *
     * @return string
     */
    public function getPayPalRefundAmount(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_REFUND_AMOUNT);
    }

    /**
     * Get Refund Id
     *
     * @return string
     */
    public function getPayPalRefundId(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_REFUND_ID);
    }

    /**
     * Get Refund At
     *
     * @return string
     */
    public function getPayPalRefundedAt(): string
    {
        return $this->getData(PaypalCheckoutInfoInterface::PAYPAL_REFUNDED_AT);
    }

    /**
     * Set Order Increment ID
     *
     * @param string $orderIncrementId
     * @return PaypalCheckoutInfoInterface
     */
    public function setOrderIncrementId($orderIncrementId): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::ORDER_INCREMENT_ID, $orderIncrementId);
    }


    /**
     * Set PayPal Order  ID
     *
     * @param string $paypalOrderId
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalOrderId($paypalOrderId): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_ORDER_ID, $paypalOrderId);
    }
    /**
     * Set PayPal Capture Id
     *
     * @param string $paypalCaptureId
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalCaptureId($paypalCaptureId): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_CAPTURE_ID, $paypalCaptureId);
    }

    /**
     * Set PayPal Authorization ID
     *
     * @param string $paypalAuthorizationId
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalAuthorizationId($paypalAuthorizationId): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZATION_ID, $paypalAuthorizationId);
    }

    /**
     * Set PayPal Authorization Period
     *
     * @param string $paypalAuthorizationPeriod
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalAuthorizationPeriod($paypalAuthorizationPeriod): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZATION_PERIOD, $paypalAuthorizationPeriod);
    }

    /**
     * Set Honor Period
     *
     * @param string $paypalHonorPeriod
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalHonorPeriod($paypalHonorPeriod): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_HONOR_PERIOD, $paypalHonorPeriod);
    }

    /**
     * Set PayPal Authorized At
     *
     * @param string $paypalAuthorizedAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalAuthorizedAt($paypalAuthorizedAt): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZED_AT, $paypalAuthorizedAt);
    }

    /**
     * SetPayPal Captured At
     *
     * @param string $paypalCapturedAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalCapturedAt($paypalCapturedAt): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_CAPTURED_AT, $paypalCapturedAt);
    }

    /**
     * Set Create At
     *
     * @param string $createdAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setCreatedAt($createdAt): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::CREATED_AT, $createdAt);
    }

    /**
     * Set Updated At
     *
     * @param string $updatedAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setUpdatedAt($updatedAt): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::UPDATED_AT, $updatedAt);
    }

    /**
     * Set Action
     *
     * @param string $action
     * @return PaypalCheckoutInfoInterface
     */
    public function setAction($action): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::ACTION, $action);
    }

    /**
     * Set Status
     *
     * @param string $status
     * @return PaypalCheckoutInfoInterface
     */
    public function setStatus($status): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::STATUS, $status);
    }

    /**
     * Set PayPal Api Request Id
     *
     * @param string $paypalApiRequestId
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalApiRequestId($paypalApiRequestId): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_API_REQUEST_ID, $paypalApiRequestId);
    }

    /**
     * Set PayPal Authorization Amount
     *
     * @param string $paypalAuthorizationAmount
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalAuthorizationAmount($paypalAuthorizationAmount): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_AUTHORIZATION_AMOUNT, $paypalAuthorizationAmount);
    }

    /**
     * Set PayPal Void Id
     *
     * @param string $paypalVoidId
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalVoidId($paypalVoidId): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_VOID_ID, $paypalVoidId);
    }

    /**
     * Set PayPal Voided At
     *
     * @param string $paypalVoidedAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalVoidedAt($paypalVoidedAt): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_VOIDED_AT, $paypalVoidedAt);
    }

    /**
     * Set Capture Amount
     *
     * @param string $paypalCapturedAmount
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalCapturedAmount($paypalCapturedAmount): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_CAPTURE_AMOUNT, $paypalCapturedAmount);
    }

    /**
     * Set Refund Amount
     *
     * @param string $paypalRefundAmount
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalRefundAmount($paypalRefundAmount): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_REFUND_AMOUNT, $paypalRefundAmount);
    }

    /**
     * Set Refund Id
     *
     * @param string $paypalRefundId
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalRefundId($paypalRefundId): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_REFUND_ID, $paypalRefundId);
    }

    /**
     * Set Refunded At
     *
     * @param string $paypalRefundedAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalRefundedAt($paypalRefundedAt): PaypalCheckoutInfoInterface
    {
        return $this->setData(PaypalCheckoutInfoInterface::PAYPAL_REFUNDED_AT, $paypalRefundedAt);
    }
}
