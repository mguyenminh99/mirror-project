<?php

namespace Mpx\PaypalJs\Api\Data;

interface PaypalAuthorizationInfoInterface
{
    const ID = 'id';
    const ORDER_INCREMENT_ID = 'order_increment_id';
    const PAYPAL_AUTHORIZATION_ID = 'paypal_authorization_id';
    const PAYPAL_AUTHORIZATION_PERIOD = 'paypal_authorization_period';
    const PAYPAL_HONOR_PERIOD = 'paypal_honor_period';
    const PAYPAL_STATUS = 'paypal_status';
    const CREATE_AT = 'create_at';
    const UPDATE_AT = 'update_at';
    const PAYPAL_AUTHORIZED_AT = 'paypal_authorized_at';

    /**
     * Get ID
     *
     * @return int
     */
    public function getId();

    /**
     * Get order increment id
     *
     * @return int
     */
    public function getOrderIncrementId();

    /**
     * Get authorization id
     *
     * @return string
     */
    public function getPayPalAuthorizationId();

    /**
     * Get Authorization Period
     *
     * @return string
     */
    public function getPayPalAuthorizationPeriod();

    /**
     * Get Honor Period
     *
     * @return string
     */
    public function getPayPalHonorPeriod();

    /**
     * Get status
     *
     * @return string
     */
    public function getPayPalStatus();


    /**
     * Get create at
     *
     * @return string
     */
    public function getCreateAt();

    /**
     * Get update at
     *
     * @return string
     */
    public function getUpdateAt();

    /**
     * Get authorize at
     *
     * @return mixed
     */
    public function getPayPalAuthorizeAt();

    /**
     * Set Order Increment ID
     *
     * @param $orderIncrementId
     * @return PaypalAuthorizationInfoInterface
     */
    public function setOrderIncrementId($orderIncrementId);

    /**
     * Set authorization id
     *
     * @param $authorizationId
     * @return PaypalAuthorizationInfoInterface
     */
    public function setPayPalAuthorizationId($authorizationId);

    /**
     * Set authorization id
     *
     * @param $authorizationPeriod
     * @return PaypalAuthorizationInfoInterface
     */
    public function setPayPalAuthorizationPeriod($authorizationPeriod);

    /**
     * Set honor period
     *
     * @param $honorPeriod
     * @return PaypalAuthorizationInfoInterface
     */
    public function setPayPalHonorPeriod($honorPeriod);

    /**
     * Set status
     *
     * @param $status
     * @return PaypalAuthorizationInfoInterface
     */
    public function setPayPalStatus($status);


    /**
     * Set create at
     *
     * @param $createAt
     * @return PaypalAuthorizationInfoInterface
     */
    public function setCreateAt($createAt);

    /**
     * Set update at
     *
     * @param $updateAt
     * @return PaypalAuthorizationInfoInterface
     */
    public function setUpdateAt($updateAt);

    /**
     * Set Authorize At
     *
     * @param $authorizeAt
     * @return mixed
     */
    public function setPayPalAuthorizeAt($authorizeAt);

}
