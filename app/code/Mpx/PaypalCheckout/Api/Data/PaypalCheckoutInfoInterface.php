<?php

namespace Mpx\PaypalCheckout\Api\Data;

interface PaypalCheckoutInfoInterface
{
    public const ID = 'id';
    public const ORDER_INCREMENT_ID = 'order_increment_id';
    public const PAYPAL_CAPTURE_ID = 'paypal_capture_id';
    public const PAYPAL_AUTHORIZATION_ID = 'paypal_authorization_id';
    public const PAYPAL_AUTHORIZATION_PERIOD = 'paypal_authorization_period';
    public const PAYPAL_HONOR_PERIOD = 'paypal_honor_period';
    public const PAYPAL_AUTHORIZED_AT = 'paypal_authorized_at';
    public const PAYPAL_CAPTURED_AT = 'paypal_captured_at';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const ACTION = 'action';
    public const STATUS = 'status';
    public const PAYPAL_API_REQUEST_ID = 'paypal_api_request_id';
    public const PAYPAL_AUTHORIZATION_AMOUNT = 'paypal_authorization_amount';
    public const PAYPAL_VOID_ID = 'paypal_void_id';
    public const PAYPAL_VOIDED_AT = 'paypal_voided_at';
    public const PAYPAL_CAPTURE_AMOUNT = 'paypal_capture_amount';
    public const PAYPAL_REFUND_AMOUNT = 'paypal_refund_amount';
    public const PAYPAL_REFUND_ID = 'paypal_refund_id';
    public const PAYPAL_REFUNDED_AT = 'paypal_refunded_at';

    /**
     * Get Order Increment Id
     *
     * @return string
     */
    public function getOrderIncrementId(): string;

    /**
     * Get PayPal Capture Id
     *
     * @return string
     */
    public function getPayPalCaptureId(): string;

    /**
     * Get PayPal Authorization ID
     *
     * @return string
     */
    public function getPayPalAuthorizationId(): string;

    /**
     * Get Authorization Period
     *
     * @return string
     */
    public function getPayPalAuthorizationPeriod(): string;

    /**
     * Get Honor Period
     *
     * @return string
     */
    public function getPayPalHonorPeriod(): string;

    /**
     * Get PayPal Authorized At
     *
     * @return string
     */
    public function getPayPalAuthorizedAt(): string;

    /**
     * Get Capture At
     *
     * @return string
     */
    public function getPayPalCapturedAt(): string;

    /**
     * Get Created At
     *
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * Get Updated At
     *
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * Get Action
     *
     * @return string
     */
    public function getAction(): string;

    /**
     * Get Status
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Get PayPal Api Request Id
     *
     * @return string
     */
    public function getPayPalApiRequestId(): string;

    /**
     * Get PayPal Authorization Amount
     *
     * @return string
     */
    public function getPayPalAuthorizationAmount(): string;

    /**
     * Get PayPal Void Id
     *
     * @return string
     */
    public function getPayPalVoidId(): string;

    /**
     * Get Paypal Void At
     *
     * @return string
     */
    public function getPayPalVoidedAt(): string;

    /**
     * Get Capture Amount
     *
     * @return string
     */
    public function getPayPalCapturedAmount(): string;

    /**
     * Get Refund Amount
     *
     * @return string
     */
    public function getPayPalRefundAmount(): string;

    /**
     * Get Refund Id
     *
     * @return string
     */
    public function getPayPalRefundId(): string;

    /**
     * Get Refunded Id
     *
     * @return string
     */
    public function getPayPalRefundedAt(): string;

    //Set Data PayPal Checkout Info

    /**
     * Set Order Increment ID
     *
     * @param $orderIncrementId
     * @return PaypalCheckoutInfoInterface
     */
    public function setOrderIncrementId($orderIncrementId): PaypalCheckoutInfoInterface;

    /**
     * Set PayPal Capture Id
     *
     * @param $paypalCaptureId
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalCaptureId($paypalCaptureId): PaypalCheckoutInfoInterface;

    /**
     * Set PayPal Authorization ID
     *
     * @param $paypalAuthorizationId
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalAuthorizationId($paypalAuthorizationId): PaypalCheckoutInfoInterface;

    /**
     * Set PayPal Authorization Period
     *
     * @param $paypalAuthorizationPeriod
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalAuthorizationPeriod($paypalAuthorizationPeriod): PaypalCheckoutInfoInterface;

    /**
     * Set Honor Period
     *
     * @param $paypalHonorPeriod
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalHonorPeriod($paypalHonorPeriod): PaypalCheckoutInfoInterface;

    /**
     * Set PayPal Authorized At
     *
     * @param $paypalAuthorizedAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalAuthorizedAt($paypalAuthorizedAt): PaypalCheckoutInfoInterface;

    /**
     * SetPayPal Captured At
     *
     * @param $paypalCapturedAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalCapturedAt($paypalCapturedAt): PaypalCheckoutInfoInterface;

    /**
     * Set Created At
     *
     * @param $createdAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setCreatedAt($createdAt): PaypalCheckoutInfoInterface;

    /**
     * Set Updated At
     *
     * @param $updatedAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setUpdatedAt($updatedAt): PaypalCheckoutInfoInterface;

    /**
     * Set Action
     *
     * @param $action
     * @return PaypalCheckoutInfoInterface
     */
    public function setAction($action): PaypalCheckoutInfoInterface;

    /**
     * Set Status
     *
     * @param $status
     * @return PaypalCheckoutInfoInterface
     */
    public function setStatus($status): PaypalCheckoutInfoInterface;

    /**
     * Set PayPal Api Request Id
     *
     * @param $paypalApiRequestId
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalApiRequestId($paypalApiRequestId): PaypalCheckoutInfoInterface;

    /**
     * Set PayPal Authorization Amount
     *
     * @param $paypalAuthorizationAmount
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalAuthorizationAmount($paypalAuthorizationAmount): PaypalCheckoutInfoInterface;

    /**
     * Set PayPal Void Id
     *
     * @param $paypalVoidId
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalVoidId($paypalVoidId): PaypalCheckoutInfoInterface;

    /**
     * Set PayPal Voided At
     *
     * @param $paypalVoidAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalVoidedAt($paypalVoidedAt): PaypalCheckoutInfoInterface;

    /**
     * Set Capture Amount
     *
     * @param $paypalCapturedAmount
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalCapturedAmount($paypalCapturedAmount): PaypalCheckoutInfoInterface;

    /**
     * Set Refund Amount
     *
     * @param $paypalRefundAmount
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalRefundAmount($paypalRefundAmount): PaypalCheckoutInfoInterface;

    /**
     * Set Refund Id
     *
     * @param $paypalRefundId
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalRefundId($paypalRefundId): PaypalCheckoutInfoInterface;

    /**
     * Set Refunded Id
     *
     * @param $paypalRefundedAt
     * @return PaypalCheckoutInfoInterface
     */
    public function setPayPalRefundedAt($paypalRefundedAt): PaypalCheckoutInfoInterface;
}
