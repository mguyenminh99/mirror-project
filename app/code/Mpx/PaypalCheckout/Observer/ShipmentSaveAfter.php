<?php

namespace Mpx\PaypalCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\Order;
use Mpx\PaypalCheckout\Model\PaypalCheckoutInfoFactory;
use Mpx\PaypalCheckout\Model\ResourceModel\PaypalCheckoutInfo;
use Magento\Framework\Message\ManagerInterface;
use Mpx\PaypalCheckout\Model\PaypalCheckoutInfo as PaypalCheckoutModel;
use Psr\Log\LoggerInterface;
use Magento\Framework\DataObject\IdentityService;
use Mpx\PaypalCheckout\Model\ResourceModel\PaypalCheckoutInfo\CollectionFactory as PaypalCheckoutCollection;
use Mpx\PaypalCheckout\Api\Data\PaypalCheckoutInfoInterface;

/**
 * Save all shipment at
 *
 * class ShipmentSaveAfter
 */
class ShipmentSaveAfter implements ObserverInterface
{
    /**
     * @var PaypalCheckoutInfo
     */
    protected $paypalCheckoutAction = PaypalCheckoutModel::PAYPAL_CHECKOUT_ACTION;

    /**
     * @var PaypalCheckoutInfo
     */
    protected $paypalCheckoutStatus = PaypalCheckoutModel::PAYPAL_CHECKOUT_STATUS;

    /**
     * Error Message PayPal Checkout
     */
    protected const ERROR_MESSAGE_PAYPAL_CHECKOUT_SHIPMENT = "No paypal_checkout_info record found during authorization";

    /**
     * @var DateTime
     */
    protected $timezoneInterface;

    /**
     * @var PaypalCheckoutInfoFactory
     */
    private $_paypalCheckoutInfoFactory;

    /**
     * @var PaypalCheckoutInfo
     */
    private $_paypalCheckoutInfoResource;

    /**
     * @var ManagerInterface
     */
    protected $_message;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var IdentityService
     */
    protected $identityService;

    /**
     * @var PaypalCheckoutCollection
     */
    protected $paypalCheckoutCollection;

    /**
     * @param PaypalCheckoutInfoFactory $paypalCheckoutInfoFactory
     * @param PaypalCheckoutInfo $paypalCheckoutInfoResource
     * @param DateTime $timezoneInterface
     * @param LoggerInterface $logger
     * @param ManagerInterface $_message
     * @param DateTime $dateTime
     * @param IdentityService $identityService
     * @param PaypalCheckoutCollection $paypalCheckoutCollection
     */
    public function __construct(
        PaypalCheckoutInfoFactory $paypalCheckoutInfoFactory,
        PaypalCheckoutInfo        $paypalCheckoutInfoResource,
        DateTime                  $timezoneInterface,
        LoggerInterface           $logger,
        ManagerInterface          $_message,
        DateTime                  $dateTime,
        IdentityService           $identityService,
        PaypalCheckoutCollection  $paypalCheckoutCollection
    )
    {
        $this->_paypalCheckoutInfoFactory = $paypalCheckoutInfoFactory;
        $this->_paypalCheckoutInfoResource = $paypalCheckoutInfoResource;
        $this->timezoneInterface = $timezoneInterface;
        $this->logger = $logger;
        $this->_message = $_message;
        $this->dateTime = $dateTime;
        $this->identityService = $identityService;
        $this->paypalCheckoutCollection = $paypalCheckoutCollection;
    }

    /**
     * Save After Data Shipment
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $shipment = $observer->getShipment();
        $order = $shipment->getOrder();

        $payment_method = $order->getPayment()->getMethod();
        if($payment_method !== PaypalCheckoutInfoInterface::PAYPAL_CHECKOUT && $payment_method !== PaypalCheckoutInfoInterface::PAYPAL_CREDIT_CARD){
            return;
        }

        if ($this->is_exists_shipped_item($order)) {
            $order_increment_id = $order->getIncrementId();
            if (!$this->is_exists_paypal_checkout_info_for_capture(
                $order_increment_id,
                $is_exists_paypal_checkout_info,
                $error_message
            )) {
                throw new \RuntimeException(
                    "is_exists_paypal_checkout_info_for_capture is failed!\n" . $error_message
                );
            }
            if (!$is_exists_paypal_checkout_info) {
                if (!$this->create_paypal_checkout_info($order_increment_id, $error_message)) {
                    throw new \RuntimeException(
                        "create_paypal_checkout_info is failed!\n" . $error_message
                    );
                }
            }
        }
    }

    /**
     * Determine if there are any products that have been shipped
     *
     * @param Order $order
     * @return bool
     */
    private function is_exists_shipped_item(Order $order): bool
    {
        $orderItems = $order->getAllVisibleItems();
        foreach ($orderItems as $item) {
            if (round($item->getQtyShipped()) > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if a paypal_checkout_info record for capture exists
     *
     * @param $order_increment_id
     * @param $result
     * @param $error_message
     * @return bool
     */
    private function is_exists_paypal_checkout_info_for_capture($order_increment_id, &$result, &$error_message): bool
    {
        try {
            $paypal_checkout_info = $this->paypalCheckoutCollection->create();
            $paypal_checkout_info
                ->getSelect()
                ->where('action = "' . $this->paypalCheckoutAction['CAPTURE'] .
                    '" AND order_increment_id = ' . $order_increment_id);
            if ($paypal_checkout_info->count() === 0) {
                $result = false;
            } else {
                $result = true;
            }
            return true;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            return false;
        }
    }

    /**
     * Generate PayPal-Request-Id
     *
     * @return string
     */
    private function generate_paypal_request_id(): string
    {
        return $this->identityService->generateId();
    }

    /**
     * Create paypal_checkout_info record for capture
     *
     * @param $order_increment_id
     * @param $error_message
     * @return bool
     */
    private function create_paypal_checkout_info($order_increment_id, &$error_message): bool
    {
        try {
            $paypal_checkout_info_authorized = $this->paypalCheckoutCollection->create();
            $paypal_checkout_info_authorized
                ->addFieldToSelect("paypal_authorization_amount","authorization_amount")
                ->addFieldToSelect("paypal_authorization_id", "authorization_id")
                ->addFieldToSelect("paypal_order_id")
                ->getSelect()
                ->where('action = "' . $this->paypalCheckoutAction['AUTHORIZE'] .
                    '" AND status = "' . $this->paypalCheckoutStatus['AUTHORIZED'] .
                    '" AND order_increment_id = ' . $order_increment_id);
            if (!$paypal_checkout_info_authorized->getData()) {
                $error_message = self::ERROR_MESSAGE_PAYPAL_CHECKOUT_SHIPMENT;
                return false;
            }
            $paypalAuthorizationId = $paypal_checkout_info_authorized->getFirstItem()
                ->getData()['authorization_id'];
            $paypalCaptureAmount = $paypal_checkout_info_authorized->getFirstItem()
                ->getData()['authorization_amount'];
            $paypal_request_id = $this->generate_paypal_request_id();
            $papalCheckoutModel = $this->_paypalCheckoutInfoFactory->create();
            $papalCheckoutModel->setOrderIncrementId($order_increment_id);
            $papalCheckoutModel->setAction($this->paypalCheckoutAction['CAPTURE']);
            $papalCheckoutModel->setStatus($this->paypalCheckoutStatus['UNPROCESSED']);
            $papalCheckoutModel->setPayPalOrderId($paypal_checkout_info_authorized->getFirstItem()->getData()['paypal_order_id']);
            $papalCheckoutModel->setPayPalApiRequestId($paypal_request_id);
            $papalCheckoutModel->setPayPalAuthorizationId($paypalAuthorizationId);
            $papalCheckoutModel->setPayPalCapturedAmount($paypalCaptureAmount);
            $papalCheckoutModel->setCreatedAt($this->dateTime->gmtTimestamp());
            $this->_paypalCheckoutInfoResource->save($papalCheckoutModel);
            return true;
        } catch (\Exception $exception) {
            $error_message = $exception->getMessage();
            return false;
        }
    }
}
