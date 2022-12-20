<?php

namespace Mpx\PaypalCheckout\Api;

use \Mpx\PaypalCheckout\Api\Data\PaypalCheckoutInfoInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface PaypalCheckoutInfoRepositoryInterface
{
    /**
     * Save PayPal Checkout Info.
     *
     * @param PaypalCheckoutInfoInterface $object
     * @return PaypalCheckoutInfoInterface
     */
    public function save(PaypalCheckoutInfoInterface $object): PaypalCheckoutInfoInterface;

    /**
     * Retrieve PayPal Checkout Info.
     *
     * @param int $id
     * @return PaypalCheckoutInfoInterface
     */
    public function getById(int $id): PaypalCheckoutInfoInterface;

    /**
     * Retrieve Checkout Info list.
     *
     * @param SearchCriteriaInterface $criteria
     * @return PaypalCheckoutInfoInterface
     */
    public function getList(SearchCriteriaInterface $criteria): PaypalCheckoutInfoInterface;

    /**
     * Delete PayPal Checkout Info.
     *
     * @param PaypalCheckoutInfoInterface $object
     * @return bool true on success
     */
    public function delete(PaypalCheckoutInfoInterface $object): bool;

    /**
     * Delete PayPal Checkout Info by ID.
     *
     * @param int $id
     * @return bool true on success
     */
    public function deleteById(int $id): bool;
}
