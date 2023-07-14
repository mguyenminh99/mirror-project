<?php
namespace XShoppingSt\Marketplace\Controller\Account\Dashboard;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result;
use Magento\Framework\Encryption\Helper\Security;
use XShoppingSt\Marketplace\Helper\Dashboard\Data as MpDashboardHelper;
use XShoppingSt\Marketplace\Helper\Data as MpHelper;

/**
 * XShoppingSt Marketplace Account Dashboard Tunnel Controller.
 */
class Tunnel extends Action
{
    /**
     * @var Result\RawFactory
     */
    protected $_resultRawFactory;

    /**
     * @var MpDashboardHelper
     */
    protected $mpDashboardHelper;

    /**
     * @var \Magento\Framework\HTTP\ZendClient
     */
    protected $httpZendClient;

    /**
     * @var MpHelper
     */
    protected $mpHelper;

    /**
     * @param Context           $context
     * @param Result\RawFactory $resultRawFactory
     * @param MpDashboardHelper $mpDashboardHelper
     * @param \Magento\Framework\HTTP\ZendClient $httpZendClient
     * @param MpHelper $mpHelper
     */
    public function __construct(
        Context $context,
        Result\RawFactory $resultRawFactory,
        MpDashboardHelper $mpDashboardHelper,
        \Magento\Framework\HTTP\ZendClient $httpZendClient,
        MpHelper $mpHelper
    ) {
        parent::__construct($context);
        $this->_resultRawFactory = $resultRawFactory;
        $this->mpDashboardHelper = $mpDashboardHelper;
        $this->httpZendClient = $httpZendClient;
        $this->mpHelper = $mpHelper;
    }

    /**
     * Request to get seller statistics graph image to the web-service.
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $errorMessage = __('invalid request');
        $httpCode = 400;
        $getEncodedParamData = $this->_request->getParam('param_data');
        $getEncryptedHashData = $this->_request->getParam('encrypted_data');
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->_resultRawFactory->create();
        if ($getEncodedParamData && $getEncryptedHashData) {
            /** @var $helper \XShoppingSt\Marketplace\Helper\Dashboard\Data */
            $helper = $this->mpDashboardHelper;
            $newEncryptedHashData = $helper->getChartEncryptedHashData($getEncodedParamData);
            if (Security::compareStrings($newEncryptedHashData, $getEncryptedHashData)) {
                $params = null;
                $paramsJson = base64_decode(urldecode($getEncodedParamData));
                if ($paramsJson) {
                    $params = json_decode($paramsJson, true);
                }
                if ($params) {
                    try {
                        /** @var $httpZendClient \Magento\Framework\HTTP\ZendClient */
                        $httpZendClient = $this->httpZendClient;
                        $response = $httpZendClient->setUri(
                            \XShoppingSt\Marketplace\Block\Account\Dashboard\Diagrams::GOOGLE_API_URL
                        )->setParameterGet(
                            $params
                        )->setConfig(
                            ['timeout' => 5]
                        )->request(
                            'GET'
                        );
                        $responseHeaders = $response->getHeaders();
                        $resultRaw->setHeader('Content-type', $responseHeaders['Content-type'])
                            ->setContents($response->getBody());

                        return $resultRaw;
                    } catch (\Exception $e) {
                        $this->mpHelper->logDataInLogger(
                            "controller_account_dashboard_tunnel execute : ".$e->getMessage()
                        );
                        $errorMessage = __('see error log for details');
                        $httpCode = 503;
                    }
                }
            }
        }
        $resultRaw->setHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->setHttpResponseCode($httpCode)
            ->setContents(__('Service unavailable: %1', $errorMessage));

        return $resultRaw;
    }
}
