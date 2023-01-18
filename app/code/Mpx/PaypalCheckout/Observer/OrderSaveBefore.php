<?php

namespace Mpx\PaypalCheckout\Observer;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\Order;
use Magento\Store\Model\ScopeInterface;
use Mpx\PaypalCheckout\Model\PaypalCheckoutInfoFactory;
use Mpx\PaypalCheckout\Model\PaypalCheckoutInfoRepository;
use Mpx\PaypalCheckout\Model\ResourceModel\PaypalCheckoutInfo\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Mpx\PaypalCheckout\Model\PaypalCheckoutInfo as PaypalCheckoutInfoModel;


/**
 * Class OrderSaveBefore
 */
class OrderSaveBefore implements ObserverInterface
{
    public const CODE = 'paypal_checkout';
    public const INTENT_AUTHORIZE = 'AUTHORIZE';
    public const PAYPAL_AUTHORIZATION_PERIOD = 'authorization_period';
    public const PAYPAL_AUTHORIZATION_HONOR_PERIOD = 'honor_period';
    public const PAYPAL_METHOD = ['paypal_checkout', 'paypalcc'];
    public const FORMAT_DATE = "Y-m-d H:i:s";

    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * @var PaypalCheckoutInfoFactory
     */
    protected $paypalCheckoutInfoFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var DateTime
     */
    protected $time;

    /**
     * @var PaypalCheckoutInfoRepository
     */
    protected $paypalCheckoutInfoRepository;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var PaypalCheckoutInfoModel
     */
    protected $paypalCheckoutInfoModel;

    /**
     * @param PaypalCheckoutInfoFactory $paypalCheckoutInfoFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param PaypalCheckoutInfoRepository $paypalCheckoutInfoRepository
     * @param Session $checkoutSession
     * @param CollectionFactory $collectionFactory
     * @param DateTime $time
     * @param PaypalCheckoutInfoModel $paypalCheckoutInfoModel
     */
    public function __construct(
        PaypalCheckoutInfoFactory    $paypalCheckoutInfoFactory,
        ScopeConfigInterface         $scopeConfig,
        PaypalCheckoutInfoRepository $paypalCheckoutInfoRepository,
        Session                      $checkoutSession,
        CollectionFactory            $collectionFactory,
        DateTime                     $time,
        PaypalCheckoutInfoModel      $paypalCheckoutInfoModel
    )
    {
        $this->paypalCheckoutInfoFactory = $paypalCheckoutInfoFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->paypalCheckoutInfoRepository = $paypalCheckoutInfoRepository;
        $this->_checkoutSession = $checkoutSession;
        $this->collectionFactory = $collectionFactory;
        $this->time = $time;
        $this->paypalCheckoutInfoModel = $paypalCheckoutInfoModel;
    }

    /**
     * Save Data table PayPal_Checkout_Info
     *
     * @param Observer $observer
     * @return void
     * @throws CouldNotSaveException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer): void
    {
        /** @var $order Order */
        $order = $observer->getOrder();
        $paypalCheckoutModel = $this->paypalCheckoutInfoModel;
        $method = $order->getPayment()->getMethod();
        if (!in_array($method, self::PAYPAL_METHOD)) {
            return;
        }
        if (!$order->isObjectNew()) {
            return;
        }
        $paypalCheckoutInfo = $this->paypalCheckoutInfoFactory->create();
        $orderIncrementId = $order->getIncrementId();
        $payment = $order->getPayment();
        $settlementAmount = $payment->getAdditionalInformation('settlement_amount');
        $createTime = $payment->getAdditionalInformation('create_time');
        $authorizationPeriod = $this->getAuthorizationPeriod($createTime);
        $honorPeriod = $this->getHonorPeriod($createTime);
        $paypalCheckoutInfo->setOrderIncrementId($orderIncrementId);
        $paypalCheckoutInfo->setPayPalOrderId($payment->getAdditionalInformation('order_id'));
        if ($payment->getAdditionalInformation('intent') === self::INTENT_AUTHORIZE) {
            $authorizationID = $payment->getAdditionalInformation('authorization_id');
            $paypalCheckoutInfo->setPayPalAuthorizationId($authorizationID);
            $paypalCheckoutInfo->setPayPalAuthorizationPeriod($authorizationPeriod);
            $paypalCheckoutInfo->setPayPalHonorPeriod($honorPeriod);
            $paypalCheckoutInfo->setStatus($paypalCheckoutModel::PAYPAL_CHECKOUT_STATUS['AUTHORIZED']);
            $paypalCheckoutInfo->SetAction($paypalCheckoutModel::PAYPAL_CHECKOUT_ACTION['AUTHORIZE']);
            $paypalCheckoutInfo->setPayPalAuthorizedAt($createTime);
            $paypalCheckoutInfo->setPayPalAuthorizationAmount($settlementAmount);
        } else {
            $paypalCheckoutInfo->setPayPalCapturedAmount($settlementAmount);
            $capturedID = $payment->getAdditionalInformation('captured_id');
            $paypalCheckoutInfo->setPayPalCaptureId($capturedID);
            $paypalCheckoutInfo->setStatus($paypalCheckoutModel::PAYPAL_CHECKOUT_STATUS['CAPTURED']);
            $paypalCheckoutInfo->SetAction($paypalCheckoutModel::PAYPAL_CHECKOUT_ACTION['CAPTURE']);
            $paypalCheckoutInfo->setPayPalCapturedAt($createTime);
        }
        $this->paypalCheckoutInfoRepository->save($paypalCheckoutInfo);
    }

    /**
     * Get Config Value
     *
     * @param $field
     * @return mixed
     */
    public function getConfigValue($field)
    {
        return $this->_scopeConfig->getValue(
            $this->_preparePathConfig($field),
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Patch Config
     *
     * @param $field
     * @return string
     */
    protected function _preparePathConfig($field): string
    {
        return sprintf('payment/%s/%s', self::CODE, $field);
    }

    /**
     * Format Date
     *
     * @param $date
     * @return string
     */
    public function formatDate($date): string
    {

        return date(self::FORMAT_DATE, $date);
    }

    /**
     * Get Authorization Period
     *
     * @param $createTime
     * @return string
     */
    public function getAuthorizationPeriod($createTime): string
    {
        $configPeriod = $this->getConfigValue(self::PAYPAL_AUTHORIZATION_PERIOD);
        $authorizationPeriod = strtotime($createTime . ' + ' . $configPeriod . 'days');
        return $this->formatDate($authorizationPeriod);
    }

    /**
     * Get Honor Period
     *
     * @param $createTime
     * @return string
     */
    public function getHonorPeriod($createTime): string
    {
        $configHonorPeriod = $this->getConfigValue(self::PAYPAL_AUTHORIZATION_HONOR_PERIOD);
        $authorizationPeriod = strtotime($createTime . ' + ' . $configHonorPeriod . 'days');
        return $this->formatDate($authorizationPeriod);
    }
}
