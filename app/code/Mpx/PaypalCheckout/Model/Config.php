<?php

namespace Mpx\PaypalCheckout\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Payment\Model\Method\AbstractMethod;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\State as State;

/**
 * Config model that is aware of all \Mpx\PaypalCheckout payment methods
 *
 * Works with PayPal Commerce Platform-specific system configuration
 */
class Config
{
    const PAYMENT_CODE                    = 'paypal_checkout';
    const CONFIG_XML_CLIENT_ID            = 'client_id';
    const CONFIG_XML_TITLE                = 'title';
    const CONFIG_XML_CREDIT_CARD_TITLE    = 'credit_card_title';
    const CONFIG_XML_INTENT               = 'payment_action';
    const CONFIG_XML_CURRENCY_CODE        = 'currency';
    const CONFIG_XML_COUNTRY_CODE         = 'country_code';
    const CONFIG_XML_OUTPUT_DEBUG_LOG     = 'output_debug_log';
    const CONFIG_XML_ACTIVE_CARD          = 'enabled_card';
    const CONFIG_XML_SECRET               = 'Secret';
    const CONFIG_XML_CLIENT_ID_SANBOX     = 'client_id_sandbox';
    const CONFIG_XML_SECRET_SANBOX        = 'secret_sandbox';

    /**
     * @var ScopeConfigInterface
     */
    public $_scopeConfig;

    /** @var LoggerInterface */
    public $_logger;

    /**
     * @var State
     */
    protected $appState;


    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface      $logger
     * @param State                $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        LoggerInterface      $logger,
        State                $appState
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_logger      = $logger;
        $this->appState     = $appState;
    }

    /**
     * Check whether method active in configuration.
     *
     * @param string $method Method code
     * @return bool
     */
    public function isMethodActive($method)
    {
        $isEnabled = $this->_scopeConfig->isSetFlag(
            'payment/' . $method . '/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $isEnabled;
    }

    /**
     * @param $config
     * @return mixed
     */
    public function getConfigValue($config)
    {
        return $this->_scopeConfig->getValue(
            $this->_preparePathConfig($config),
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve config flag by path and scope
     *
     * @param string $flag
     * @return bool
     */
    public function isSetFlag($flag): bool
    {
        return $this->_scopeConfig->isSetFlag(
            $this->_preparePathConfig($flag),
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $config
     * @param $code
     * @return string
     */
    protected function _preparePathConfig($config, $code = self::PAYMENT_CODE): string
    {
        return sprintf("payment/%s/%s", $code, $config);
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        if ($this->appState->getMode() == \Magento\Framework\App\State::MODE_PRODUCTION) {
            return $this->getConfigValue(self::CONFIG_XML_CLIENT_ID);
        } else {
            return $this->getConfigValue(self::CONFIG_XML_CLIENT_ID_SANBOX);
        }
    }

    /**
     * Get Secret
     *
     * @return mixed
     */
    public function getSecret()
    {
        if ($this->appState->getMode() == \Magento\Framework\App\State::MODE_PRODUCTION) {
            return $this->getConfigValue(self::CONFIG_XML_SECRET);
        } else {
            return $this->getConfigValue(self::CONFIG_XML_SECRET_SANBOX);
        }
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->getConfigValue(self::CONFIG_XML_CURRENCY_CODE);
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->getConfigValue(self::CONFIG_XML_COUNTRY_CODE);
    }

    /**
     * @return mixed
     */
    public function getIntent()
    {
        if ($this->getConfigValue(self::CONFIG_XML_INTENT) === AbstractMethod::ACTION_AUTHORIZE_CAPTURE) {
            return 'capture';
        }

        return $this->getConfigValue(self::CONFIG_XML_INTENT);
    }

    /**
     * @return mixed
     */
    public function getActiveCard()
    {
        return $this->getConfigValue(self::CONFIG_XML_ACTIVE_CARD);
    }
}
