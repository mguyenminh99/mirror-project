<?php

namespace Mpx\PaypalJs\Model;

use Magento\Customer\Model\Session;
use Mpx\PaypalJs\Logger\Handler;

/**
 * Class PayPalJsConfigProvider
 * get config PayPal push checkout config
 */
class PayPalJsConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    const BASE_URL_SDK = 'https://www.paypal.com/sdk/js?';

    const SDK_CONFIG_CLIENT_ID  = 'client-id';
    const SDK_CONFIG_CURRENCY   = 'currency';
    const SDK_CONFIG_DEBUG      = 'debug';
    const SDK_CONFIG_COMPONENTS = 'components';
    const SDK_CONFIG_LOCALE     = 'locale';
    const SDK_CONFIG_INTENT     = 'intent';
    const LENGTH_IDENTIFIER = 15;

    /**
     * @var string
     */
    protected $_payment_code = Config::PAYMENT_CODE;
    /**
     * @var array
     */
    protected $_params = [];

    /** @var Config */
    protected $_paypalConfig;

    /** @var Session */
    protected $_customerSession;

    /** @var \Magento\Checkout\Model\Session */
    protected $_checkoutSession;

    /** @var Handler */
    protected $_logger;

    /**
     * @param Config $paypalConfig
     * @param Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param Handler $logger
     */
    public function __construct(
        Config                          $paypalConfig,
        Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        Handler    $logger
    ) {
        $this->_paypalConfig    = $paypalConfig;
        $this->_customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;
        $this->_logger          = $logger;
    }

    /**
     * @return array|\array[][]
     */
    public function getConfig(): array
    {
        if (!$this->_paypalConfig->isMethodActive($this->_payment_code)) {
            return [];
        }
        $quote = $this->_checkoutSession->getQuote();
        $config = [
            'payment' => [
                $this->_payment_code => [
                    'title' => $this->_paypalConfig->getConfigValue(Config::CONFIG_XML_TITLE),
                    'urlSdk' => $this->getUrlSdk(),
                    'customer' => [
                        'id' => $this->validateCustomerId(),
                    ],
                    self::SDK_CONFIG_INTENT => $this->_paypalConfig->getIntent(),
                    self::SDK_CONFIG_DEBUG => $this->_paypalConfig->isSetFLag(Config::CONFIG_XML_DEBUG_MODE),
                    'activeCard' => $this->_paypalConfig->getActiveCard(),
                    'reserved_order_id' => $quote->getReservedOrderId(),
                    'credit_card_title' => $this->_paypalConfig->getConfigValue(Config::CONFIG_XML_CREDIT_CARD_TITLE),
                    self::SDK_CONFIG_CURRENCY   => $this->_paypalConfig->getCurrency(),
                ]
            ]
        ];
        $this->_logger->debug(__METHOD__ . ' | CONFIG ' . json_encode($config, true));

        return $config;
    }

    /**
     * @return string
     */
    public function getUrlSdk(): string
    {
        $this->buildParams();

        return self::BASE_URL_SDK . http_build_query($this->_params);
    }

    /**
     * Build params for js sdk
     */
    private function buildParams(): void
    {
        $this->_params = [
            self::SDK_CONFIG_CLIENT_ID  => $this->_paypalConfig->getClientId(),
            self::SDK_CONFIG_CURRENCY   => $this->_paypalConfig->getCurrency(),
            self::SDK_CONFIG_DEBUG      => $this->_paypalConfig
                                                ->isSetFLag(Config::CONFIG_XML_DEBUG_MODE) ? 'true' : 'false',
            self::SDK_CONFIG_COMPONENTS => 'hosted-fields,buttons,funding-eligibility',
            self::SDK_CONFIG_LOCALE     => 'ja_JP',
            self::SDK_CONFIG_INTENT     => $this->_paypalConfig->getIntent(),
        ];
    }

    /**
     * @return int|void|null
     */
    private function validateCustomerId()
    {
        if ($this->_customerSession->isLoggedIn()) {
            return $this->_customerSession->getCustomerId();
        }
    }
}
