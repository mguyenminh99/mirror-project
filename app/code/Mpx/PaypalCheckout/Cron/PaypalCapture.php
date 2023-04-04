<?php

namespace Mpx\PaypalCheckout\Cron;

use Magento\Framework\App\Area;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Transaction;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Setup\Exception;
use Magento\Store\Model\StoreManagerInterface;
use Mpx\PaypalCheckout\Model\Config;
use Mpx\PaypalCheckout\Model\ResourceModel\PaypalCheckoutInfo\Collection;
use Mpx\PaypalCheckout\Model\ResourceModel\PaypalCheckoutInfo\CollectionFactory as PaypalCheckoutCollection;
use Mpx\PaypalCheckout\Model\ResourceModel\PaypalCheckoutInfo as PaypalCheckoutInfoResourceModel;
use Psr\Log\LoggerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Mpx\PaypalCheckout\Model\PaypalCheckoutInfo as PaypalCheckoutModel;
use Mpx\PaypalCheckout\Model\PaypalCheckoutInfoFactory as PaypalCheckoutModelFactory;
use Magento\Framework\App\ResourceConnection;
use Webkul\Marketplace\Helper\Data as DataSeller;
use Webkul\Marketplace\Model\ResourceModel\Seller\CollectionFactory as SellerCollectionFactory;
use Magento\Sales\Model\Order;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Mpx\PaypalCheckout\Model\Payment\PaypalCheckout\Payment;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State as State;
use Mpx\Marketplace\Helper\CommonFunc as MpxMarketplaceHelper;

/**
 * Cron Job PaypalCapture
 *
 * Class PaypalCapture
 */
class PaypalCapture
{
    protected const LOG_LEVEL_INFO = 'INFO';
    protected const LOG_LEVEL_NOTICE = 'NOTICE';
    protected const LOG_LEVEL_WARNING = 'WARNING';
    protected const LOG_LEVEL_ERROR = 'ERROR';
    protected const PAYPAL_GENERATE_ACCESS_TOKEN_API_URL_LIVE = 'https://api-m.paypal.com/v1/oauth2/token';
    protected const PAYPAL_GENERATE_ACCESS_TOKEN_API_URL_SANDBOX = 'https://api-m.sandbox.paypal.com/v1/oauth2/token';
    protected const PAYPAL_CAPTURE_AUTHORIZED_PAYMENT_API_URL_LIVE = 'https://api-m.paypal.com/v2/payments/authorizations/{authorization_id}/capture';
    protected const PAYPAL_CAPTURE_AUTHORIZED_PAYMENT_API_URL_SANDBOX = 'https://api-m.sandbox.paypal.com/v2/payments/authorizations/{authorization_id}/capture';
    protected const PAYPAL_CAPTURE_API_RESULT_SUCCESS = 0;
    protected const PAYPAL_CAPTURE_API_RESULT_FAILED = -1;
    protected const PAYPAL_CAPTURE_API_RESULT_MAINTENANCE = -2;
    protected const PAYPAL_CAPTURE_API_RESULT_INTERNAL_SERVER_ERROR = -3;
    protected const PAYPAL_CAPTURE_API_RESULT_API_REQUEST_RATE_LIMIT_REACHED = -4;
    protected const PAYPAL_CAPTURE_API_RESULT_SERVER_TIMEOUT = -5;
    protected const PAYPAL_CAPTURE_API_RESULT_UNEXPECTED_ERROR = -6;
    protected const PAYPAL_CAPTURE_API_RESULT_CONSISTENCY_ERROR = -7;
    protected const HTTP_STATUS_CODE_SERVICE_UNAVAILABLE = 503;
    protected const STORE_GUIDE_PATH = 'store-guide';
    protected const ORDER_DETAIL_PATH = 'marketplace/order/view/id';
    protected const MARKETPLACE_STORE_ID = 1;
    protected const XML_PATH_EMAIL_PAYPAL_CAPTURE_FAILED_NOTICE = "paypal_capture_failed_notice";
    protected const XML_PATH_EMAIL_SYSTEM_NOTICE_MAIL = "system_notice_mail";

    /**
     * @var Config;
     */
    protected $config;

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var PaypalCheckoutCollection
     */
    protected $paypalCheckoutCollection;

    /**
     * @var PaypalCheckoutModel
     */
    protected $paypalCheckoutModel;

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var AdapterInterface
     */
    protected $dbConnection;

    /**
     * @var PaypalCheckoutModelFactory
     */
    protected $paypalCheckoutModelFactory;

    /**
     * @var PaypalCheckoutInfoResourceModel
     */
    protected $paypalCheckoutInfoResourceModel;

    /**
     * @var SellerCollectionFactory
     */
    protected $sellerCollectionFactory;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var CustomerCollectionFactory
     */
    protected $customerCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var InvoiceService
     */
    protected $invoiceService;

    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * @var DataSeller
     */
    protected $dataSeller;

    /**
     * @var Payment
     */
    protected $payment;

    /**
     * @var ScopeConfigInterface
     */
    public $scopeConfig;

    /**
     * @var MpxMarketplaceHelper
     */
    protected $mpxMarketplaceHelper;

    /**
     * @var State
     */
    protected $appState;

    /**
     * @param Config $config
     * @param Curl $curl
     * @param LoggerInterface $logger
     * @param DateTime $dateTime
     * @param Escaper $escaper
     * @param TransportBuilder $transportBuilder
     * @param PaypalCheckoutCollection $paypalCheckoutCollection
     * @param PaypalCheckoutModel $paypalCheckoutModel
     * @param ResourceConnection $resource
     * @param PaypalCheckoutModelFactory $paypalCheckoutModelFactory
     * @param PaypalCheckoutInfoResourceModel $paypalCheckoutInfoResourceModel
     * @param SellerCollectionFactory $sellerCollectionFactory
     * @param Order $order
     * @param CustomerCollectionFactory $customerCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param OrderRepositoryInterface $orderRepository
     * @param InvoiceService $invoiceService
     * @param Transaction $transaction
     * @param DataSeller $dataSeller
     * @param Payment $payment
     * @param MpxMarketplaceHelper $mpxMarketplaceHelper
     */
    public function __construct(
        Config                          $config,
        Curl                            $curl,
        LoggerInterface                 $logger,
        DateTime                        $dateTime,
        Escaper                         $escaper,
        TransportBuilder                $transportBuilder,
        PaypalCheckoutCollection        $paypalCheckoutCollection,
        PaypalCheckoutModel             $paypalCheckoutModel,
        ResourceConnection              $resource,
        PaypalCheckoutModelFactory      $paypalCheckoutModelFactory,
        PaypalCheckoutInfoResourceModel $paypalCheckoutInfoResourceModel,
        SellerCollectionFactory         $sellerCollectionFactory,
        Order                           $order,
        CustomerCollectionFactory       $customerCollectionFactory,
        StoreManagerInterface           $storeManager,
        StateInterface                  $inlineTranslation,
        OrderRepositoryInterface        $orderRepository,
        InvoiceService                  $invoiceService,
        Transaction                     $transaction,
        DataSeller                      $dataSeller,
        ScopeConfigInterface            $scopeConfig,
        Payment                         $payment,
        MpxMarketplaceHelper            $mpxMarketplaceHelper,
        State                           $appState
    )
    {
        $this->config = $config;
        $this->curl = $curl;
        $this->logger = $logger;
        $this->dateTime = $dateTime;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->paypalCheckoutCollection = $paypalCheckoutCollection;
        $this->paypalCheckoutModel = $paypalCheckoutModel;
        $this->resource = $resource;
        $this->dbConnection = $this->resource->getConnection();
        $this->paypalCheckoutModelFactory = $paypalCheckoutModelFactory;
        $this->paypalCheckoutInfoResourceModel = $paypalCheckoutInfoResourceModel;
        $this->sellerCollectionFactory = $sellerCollectionFactory;
        $this->order = $order;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->orderRepository = $orderRepository;
        $this->invoiceService = $invoiceService;
        $this->transaction = $transaction;
        $this->dataSeller = $dataSeller;
        $this->payment = $payment;
        $this->scopeConfig = $scopeConfig;
        $this->mpxMarketplaceHelper = $mpxMarketplaceHelper;
        $this->appState = $appState;
    }

    /**
     * Acquire the target data and execute PayPal API to capture
     *
     * @return void
     * @throws \JsonException
     */
    public function execute_paypal_capture_batch(): void
    {
        $enableBatch = $this->scopeConfig->getValue(
            'payment/paypal_checkout/enable_batch',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if (!$enableBatch) {
            return;
        }
        try {
            $this->write_log(self::LOG_LEVEL_INFO, '処理を開始します');
            $ret = $this->get_access_token($access_token);
            if (!$ret) {
                $this->write_log(self::LOG_LEVEL_INFO, 'Paypal Capture batch を終了します');
                exit(0);
            }
            $capture_target_list = $this->get_capture_target_list();
            $capture_success_count = 0;
            $capture_failed_count = 0;
            foreach ($capture_target_list as $capture_target) {
                $this->dbConnection->beginTransaction();
                $ret = $this->get_capture_target_record_by_id(
                    $capture_target->getData('paypal_checkout_info_id'),
                    $capture_target_detail
                );
                if (!$ret) {
                    $this->dbConnection->rollBack();
                    $this->write_log(self::LOG_LEVEL_INFO, 'Paypal Capture batch を終了します');
                    exit(0);
                }
                if (count($capture_target_detail) !== 1) {
                    continue;
                }
                $ret = $this->call_paypal_capture_api(
                    $access_token,
                    $capture_target_detail->getFirstItem()->getData('paypal_authorization_id'),
                    $capture_target_detail->getFirstItem()->getData('paypal_capture_amount'),
                    $capture_target_detail->getFirstItem()->getData('paypal_api_request_id'),
                    $capture_target_detail->getFirstItem()->getData('order_increment_id'),
                    $paypal_capture_api_result_code,
                    $paypal_capture_api_response
                );
                $paypal_capture_api_response = json_decode($paypal_capture_api_response, true);
                if ($ret) {
                    $capture_success_count++;
                    $ret = $this->update_paypal_checkout_info(
                        $capture_target->getData('paypal_checkout_info_id'),
                        'captured',
                        $paypal_capture_api_response['id'],
                        $paypal_capture_api_response['create_time'],
                        $capture_target_detail->getFirstItem()->getData('order_increment_id')
                    );
                    if (!$ret) {
                        $this->dbConnection->rollBack();
                        $this->write_log(self::LOG_LEVEL_INFO, 'Paypal Capture batch を終了します');
                        exit(0);
                    }
                    $ret = $this->create_magento_invoice(
                        $capture_target_detail->getFirstItem()->getData('order_increment_id'),
                        $paypal_capture_api_response['id']
                    );
                    if (!$ret) {
                        $this->dbConnection->rollBack();
                        $this->write_log(self::LOG_LEVEL_INFO, 'Paypal Capture batch を終了します');
                        exit(0);
                    }
                    $ret = $this->update_marketplace_orders(
                        $capture_target_detail->getFirstItem()->getData('order_increment_id')
                    );
                    if(!$ret){
                        $this->dbConnection->rollBack();
                        $this->write_log(self::LOG_LEVEL_INFO, 'Paypal Capture batch を終了します');
                        exit(0);
                    }
                    $this->dbConnection->commit();
                } else {
                    $capture_failed_count++;
                    //カードの不正利用等でcaptureに失敗した場合
                    if ($paypal_capture_api_result_code === self::PAYPAL_CAPTURE_API_RESULT_FAILED) {
                        $ret = $this->update_paypal_checkout_info(
                            $capture_target->getData('paypal_checkout_info_id'),
                            'capture_failed',
                            null,
                            null,
                            $capture_target_detail->getFirstItem()->getData('order_increment_id')
                        );
                        if (!$ret) {
                            $this->dbConnection->rollBack();
                            $this->write_log(self::LOG_LEVEL_INFO, 'Paypal Capture batch を終了します');
                            exit(0);
                        }
                        $this->dbConnection->commit();
                        $this->send_capture_failed_notice_mail($capture_target_detail->getFirstItem()->getData('order_increment_id'));
                    } elseif ($paypal_capture_api_result_code === self::PAYPAL_CAPTURE_API_RESULT_SERVER_TIMEOUT ||
                        $paypal_capture_api_result_code === self::PAYPAL_CAPTURE_API_RESULT_INTERNAL_SERVER_ERROR ||
                        $paypal_capture_api_result_code === self::PAYPAL_CAPTURE_API_RESULT_CONSISTENCY_ERROR) {
                        $this->dbConnection->rollBack();
                        continue;
                    } else {
                        $this->dbConnection->rollBack();
                        $this->write_log(self::LOG_LEVEL_INFO, 'Paypal Capture batch を終了します');
                        exit(0);
                    }
                }
            }
            $this->write_log(
                self::LOG_LEVEL_INFO,
                '処理結果 success : ' . $capture_success_count .
                ' failed : ' . $capture_failed_count
            );
        } catch (Exception $exception) {
            $this->write_log(
                self::LOG_LEVEL_ERROR,
                '予期しないエラーが発生したためバッチを終了します' . "\n" .
                $exception->getMessage()
            );
            exit(1);
        }
    }

    /**
     * Send email notification of failed PayPal Capture processing
     *
     * @param $order_increment_id
     * @return void
     */
    private function send_capture_failed_notice_mail($order_increment_id): void
    {
        try {
            $store = $this->storeManager->getStore();
            $baseUrl = $store->getBaseUrl();
            $mOrder = $this->dbConnection->getTableName('marketplace_orders');
            $order = $this->order->loadByIncrementId($order_increment_id);
            $transport = $this->transportBuilder;
            $sales_order_entity_id = $order->getId();
            $store_name = $this->getStoreName($mOrder, $sales_order_entity_id);
            $store_email_address = $this->getStoreEmailAddress($mOrder, $sales_order_entity_id);
            $store_guide_url = $baseUrl . self::STORE_GUIDE_PATH;
            $order_detail_url = $baseUrl . self::ORDER_DETAIL_PATH . '/' . $sales_order_entity_id;
            $email_template = self::XML_PATH_EMAIL_PAYPAL_CAPTURE_FAILED_NOTICE;
            $email_body = [
                'store_guide_url' => $store_guide_url,
                'store_name' => $store_name,
                'order_increment_id' => $order_increment_id,
                'order_detail_url' => $order_detail_url,
                'marketplace_name' => $this->mpxMarketplaceHelper->getMarketplaceName()
            ];
            $sender = [
                'email' => $this->mpxMarketplaceHelper->getFromMailAddress(),
                'name' => ''
            ];
            $this->inlineTranslation->suspend();
            $transport->setTemplateVars($email_body);
            $transport->setTemplateOptions(
                [
                    'area' => Area::AREA_FRONTEND,
                    'store' => $this->storeManager->getStore()->getId(),
                ]
            );
            $transport->setTemplateIdentifier($email_template);
            $transport->setFrom($sender);
            $transport->addTo($store_email_address);
            $transport->addBcc([
                $this->mpxMarketplaceHelper->getSystemAdminMailAddress(),
                $this->mpxMarketplaceHelper->getXsadminMailAddress()
            ]);
            $transport->getTransport()->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }
    }

    /**
     * Get Store Name
     *
     * @param $mOrder
     * @param $orderId
     * @return string
     */
    private function getStoreName($mOrder, $orderId): string
    {
        $shopTitle = $this->sellerCollectionFactory->create();
        $shopTitle->getSelect()
            ->joinInner(
                $mOrder . ' as MO',
                'main_table.seller_id = MO.seller_id'
            )
            ->where('MO.order_id =' . $orderId)
            ->where('main_table.store_id =' . self::MARKETPLACE_STORE_ID);
        return $shopTitle->getFirstItem()->getData('shop_title');
    }

    /**
     * Get Store Email Address
     *
     * @param $mOrder
     * @param $orderId
     * @return string
     */
    private function getStoreEmailAddress($mOrder, $orderId): string
    {
        $customer = $this->customerCollectionFactory->create();
        $customer->getSelect()
            ->joinInner(
                $mOrder . ' as MO',
                'e.entity_id = MO.seller_id'
            )
            ->where('MO.order_id =' . $orderId);
        return $customer->getFirstItem()->getData('email');
    }

    /**
     * Send notification emails related to system operation
     *
     * @param $log_level
     * @param $message
     * @return void
     */
    private function send_system_notice_mail($log_level, $message): void
    {
        try {
            $transport = $this->transportBuilder;
            $this->inlineTranslation->suspend();
            $transport->setTemplateIdentifier(self::XML_PATH_EMAIL_SYSTEM_NOTICE_MAIL);
            $sender = [
                'email' => $this->mpxMarketplaceHelper->getSystemNoticeMailFromAddress(),
                'name' => ''
            ];
            $emailBody = [
                'log_level' => $log_level,
                'marketplace_name' => $this->mpxMarketplaceHelper->getMarketplaceName(),
                'message' => $message
            ];
            $transport->setFrom($sender);
            $transport->addTo($this->mpxMarketplaceHelper->getSystemAdminMailAddress());
            $transport->setTemplateVars($emailBody);
            $transport->setTemplateOptions(
                [
                    'area' => Area::AREA_FRONTEND,
                    'store' => $this->storeManager->getStore()->getId(),
                ]
            );
            $transport->getTransport()->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }
        $this->inlineTranslation->resume();
    }

    /**
     * Update paypal_checkout_info record in database
     *
     * @param $paypal_checkout_info_id
     * @param $status
     * @param $paypal_capture_id
     * @param $paypal_captured_at
     * @param $order_increment_id
     * @return bool
     */
    private function update_paypal_checkout_info(
        $paypal_checkout_info_id,
        $status,
        $paypal_capture_id,
        $paypal_captured_at,
        $order_increment_id
    ): bool
    {
        try {
            $currentTime = $this->dateTime->gmtDate();
            $connection = $this->resource->getConnection();
            $paypalCheckoutInfo = $this->resource->getTableName('paypal_checkout_info');
            $paypal_capture_id = is_null($paypal_capture_id) ? 'null' : $connection->quote($paypal_capture_id);
            $paypal_captured_at = is_null($paypal_captured_at) ? 'null' : "'$paypal_captured_at'";
            $sql = "update " . $paypalCheckoutInfo . " set status = '$status',
                   paypal_capture_id = $paypal_capture_id ,
                   paypal_captured_at = $paypal_captured_at,
                   updated_at = '$currentTime'
                   where id = '$paypal_checkout_info_id'";
            $connection->query($sql);
        } catch (\Exception $exception) {
            $this->write_log(self::LOG_LEVEL_ERROR, 'paypal_checkout_infoの更新に失敗しました。' . "\n" .
                '注文番号：' . $order_increment_id . "\n" .
                'エラー内容：' . $exception->getMessage());
            return false;
        }
        return true;
    }


    /**
     * Get a list of capture targets from the database
     *
     * @return Collection
     * @throws Exception
     */
    private function get_capture_target_list(): Collection
    {
        try {
            $capture_target_list = $this->paypalCheckoutCollection->create();
            $capture_target_list
                ->addFieldToSelect("id", "paypal_checkout_info_id")
                ->getSelect()
                ->where('status = "' . $this->paypalCheckoutModel::PAYPAL_CHECKOUT_STATUS['UNPROCESSED'] .
                    '" AND action = "' . $this->paypalCheckoutModel::PAYPAL_CHECKOUT_ACTION['CAPTURE'] .
                    '" AND (UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(created_at)) > 3600')
                ->order('id ASC');
            $this->write_log(self::LOG_LEVEL_INFO, count($capture_target_list) . '件のcapture対象データを取得しました');
            return $capture_target_list;
        } catch (\Exception $exception) {
            throw new Exception('capture対象データリストの取得に失敗しました' . "\n" . $exception->getMessage());
        }
    }

    /**
     * Acquire the record to be captured from the database by specifying the id
     *
     * @param $paypal_checkout_info_id
     * @param $capture_target_detail
     * @return bool
     */
    private function get_capture_target_record_by_id($paypal_checkout_info_id, &$capture_target_detail): bool
    {
        try {
            $capture_target_detail = $this->paypalCheckoutCollection->create();
            $capture_target_detail
                ->addFieldToSelect("order_increment_id")
                ->addFieldToSelect("paypal_authorization_id")
                ->addFieldToSelect("paypal_capture_amount")
                ->addFieldToSelect("paypal_api_request_id")
                ->getSelect()
                ->where('status = "' . $this->paypalCheckoutModel::PAYPAL_CHECKOUT_STATUS['UNPROCESSED'] .
                    '" AND action = "' . $this->paypalCheckoutModel::PAYPAL_CHECKOUT_ACTION['CAPTURE'] .
                    '" AND (UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(created_at)) > 3600' .
                    ' AND id =' . $paypal_checkout_info_id)
                ->forUpdate();
        } catch (\Exception $exception) {
            $this->write_log(self::LOG_LEVEL_ERROR, 'capture対象レコードの取得に失敗しました' . "\n" .
                'paypal_checkout_info_id：' . $paypal_checkout_info_id . "\n" .
                'エラー内容：' . $exception->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Create Magento Invoice Data
     *
     * @param $order_increment_id
     * @param $transactionId
     * @return bool
     */
    private function create_magento_invoice($order_increment_id, $transactionId): bool
    {
        try {
            $orderInfo = $this->order->loadByIncrementId($order_increment_id);
            $orderId = $orderInfo->getId();
            $order = $this->orderRepository->get($orderId);
            if ($order->canInvoice()) {
                $invoice = $this->invoiceService->prepareInvoice($order);
                $this->payment->setPayPalCaptureTransactionId($order->getPayment(), $transactionId);
                $invoice->setRequestedCaptureCase("online");
                $invoice->register();
                $transactionSave = $this->transaction
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());
                $transactionSave->save();
                $order->addCommentToStatusHistory(
                    __('Notified customer about invoice creation #%1.', $invoice->getId())
                )->setIsCustomerNotified(true)->save();
            }
        } catch (\Exception $exception) {
            $this->write_log(self::LOG_LEVEL_ERROR, 'Invoiceデータの作成に失敗しました。' . "\n" .
                '注文番号：' . $order_increment_id . "\n" .
                'エラー内容：' . $exception->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Run PayPal Capture authorized payment API
     *
     * @param $access_token
     * @param $authorization_id
     * @param $capture_amount
     * @param $paypal_api_request_id
     * @param $order_increment_id
     * @param $result_code
     * @param $api_response
     * @return bool
     */
    private function call_paypal_capture_api(
        $access_token,
        $authorization_id,
        $capture_amount,
        $paypal_api_request_id,
        $order_increment_id,
        &$result_code,
        &$api_response
    ): bool
    {
        try {
            $api_response = $this->execute_capture_authorized_payment_api(
                $authorization_id,
                $access_token,
                $paypal_api_request_id,
                $capture_amount,
                $order_increment_id,
                $http_status_code
            );
        } catch (\Exception $exception) {
            if (preg_match('/^Operation timed out/', $exception->getMessage())) {
                $result_code = self::PAYPAL_CAPTURE_API_RESULT_SERVER_TIMEOUT;
                $this->write_log(self::LOG_LEVEL_ERROR, 'PayPal Capture authorized payment APIの実行がタイムアウトしました' . "\n" .
                    '注文番号：' . $order_increment_id);
                return false;
            }
            $result_code = self::PAYPAL_CAPTURE_API_RESULT_UNEXPECTED_ERROR;
            $this->write_log(self::LOG_LEVEL_ERROR, 'PayPal Capture authorized payment APIの実行に失敗しました' . "\n" .
                '注文番号：' . $order_increment_id . "\n" .
                'エラー内容：' . $exception->getMessage());
            return false;
        }
        $json_decoded_api_response = json_decode($api_response, true);
        if ($http_status_code === 200 || $http_status_code === 201) {
            if ($json_decoded_api_response['status'] === 'COMPLETED') {
                $result_code = self::PAYPAL_CAPTURE_API_RESULT_SUCCESS;
                return true;
            }
            $this->write_log(
                self::LOG_LEVEL_ERROR,
                "PayPal Capture処理に失敗しました" . "\n" .
                "注文番号：" . $order_increment_id . "\n" .
                "HTTPステータスコードとAPIレスポンス['STATUS']の整合性エラー
                     HTTPステータスコード：" . $http_status_code . "\n" .
                "APIレスポンス['STATUS']：" . $json_decoded_api_response['status']
            );
            $result_code = self::PAYPAL_CAPTURE_API_RESULT_CONSISTENCY_ERROR;
            return false;
        }
        if ($http_status_code === 422) {
            $this->write_log(
                self::LOG_LEVEL_NOTICE,
                'PayPal Capture処理に失敗しました' . "\n" .
                '注文番号：' . $order_increment_id . "\n" .
                'APIレスポンスHTTPステータスコード：' . $http_status_code . "\n" .
                'APIレスポンス：' . $api_response
            );
            $result_code = self::PAYPAL_CAPTURE_API_RESULT_FAILED;
            return false;
        }

        if ($http_status_code === 429) {
            $this->write_log(
                self::LOG_LEVEL_WARNING,
                'PayPal Capture authorized payment APIの実行時にAPIリクエスト回数制限エラーが発生しました' . "\n" .
                '注文番号：' . $order_increment_id
            );
            $result_code = self::PAYPAL_CAPTURE_API_RESULT_API_REQUEST_RATE_LIMIT_REACHED;
            return false;
        }

        if ($http_status_code === 500) {
            $this->write_log(
                self::LOG_LEVEL_ERROR,
                'PayPal Capture authorized payment APIの実行時にInternalServerErrorが発生しました' . "\n" .
                '注文番号：' . $order_increment_id . "\n" .
                'APIレスポンス：' . $api_response
            );
            $result_code = self::PAYPAL_CAPTURE_API_RESULT_INTERNAL_SERVER_ERROR;
            return false;
        }

        if ($http_status_code === 503) {
            $this->write_log(self::LOG_LEVEL_INFO, 'PayPalサーバーがメンテナンス中です');
            $result_code = self::PAYPAL_CAPTURE_API_RESULT_MAINTENANCE;
            return false;
        }

        $error_message = 'PayPal Capture authorized payment APIの実行時に予期しないエラーが発生しました' . "\n" .
            '注文番号：' . $order_increment_id . "\n" .
            'APIレスポンスHTTPステータスコード：' . $http_status_code . "\n" .
            'APIレスポンス：' . $api_response;
        $this->write_log(self::LOG_LEVEL_ERROR, $error_message);
        $result_code = self::PAYPAL_CAPTURE_API_RESULT_UNEXPECTED_ERROR;
        return false;
    }

    /**
     * Generate Capture Authorized Payment
     *
     * @param $authorization_id
     * @param $access_token
     * @param $paypal_api_request_id
     * @param $capture_amount
     * @param $order_increment_id
     * @param $http_status_code
     * @return string
     */
    private function execute_capture_authorized_payment_api($authorization_id, $access_token, $paypal_api_request_id, $capture_amount,$order_increment_id,&$http_status_code): string
    {
        $orderInfo = $this->order->loadByIncrementId($order_increment_id);
        $productId = $orderInfo->getAllItems()[0]->getData('product_id');
        $sellerId = $this->dataSeller->getSellerIdByProductId($productId);
        $marketplaceId = $this->mpxMarketplaceHelper->getMarketPlaceId();
        $sellerIdZeroFill = str_pad($sellerId, 3, "0", STR_PAD_LEFT);
        $invoiceID = $marketplaceId . "-" . $sellerIdZeroFill . "-" . $order_increment_id;
        if ($this->isProductionMode()) {
            $paypal_capture_authorized_payment_api_url = str_replace(
                '{authorization_id}',
                urlencode($authorization_id),
                self::PAYPAL_CAPTURE_AUTHORIZED_PAYMENT_API_URL_LIVE
            );
        } else {
            $paypal_capture_authorized_payment_api_url = str_replace(
                '{authorization_id}',
                urlencode($authorization_id),
                self::PAYPAL_CAPTURE_AUTHORIZED_PAYMENT_API_URL_SANDBOX
            );
        }
        $apiBody = [
            "amount" => [
                "value" => round($capture_amount),
                "currency_code" => "JPY"
            ],
            "invoice_id" => $invoiceID,
            "final_capture" => true
        ];
        $this->curl->addHeader("Content-Type", "application/json; charset=utf8");
        $this->curl->addHeader("Authorization", "Bearer " . $access_token);
        $this->curl->addHeader("PayPal-Request-Id", $paypal_api_request_id);
        $this->curl->addHeader("Prefer", "return=representation");
        $this->curl->setOption(CURLOPT_POSTFIELDS, json_encode($apiBody, true));
        $this->curl->post($paypal_capture_authorized_payment_api_url, []);
        $result = $this->curl->getBody();
        $http_status_code = $this->curl->getStatus();
        return $result;
    }

    /**
     * Get the access_token used for PayPal API authentication
     *
     * @param $access_token
     * @return bool
     * @throws Exception|\JsonException
     */
    private function get_access_token(&$access_token): bool
    {
        $paypal_client_id = $this->get_paypal_client_id();
        $paypal_client_secret = $this->get_paypal_client_secret();
        try {
            $api_response = $this->generate_access_token($paypal_client_id, $paypal_client_secret, $paypal_status_http);
        } catch (\Exception $exception) {
            if (preg_match('/^Operation timed out/', $exception->getMessage())) {
                $this->write_log(
                    self::LOG_LEVEL_ERROR,
                    'PayPal Generate_access_token APIの実行がタイムアウトしました'
                );
                return false;
            }
            throw new Exception('PayPal Generate_access_token APIの実行時にエラーが発生しました' . "\n" .
                $exception->getMessage());
        }
        $http_status_code = $paypal_status_http;
        if ($http_status_code === self::HTTP_STATUS_CODE_SERVICE_UNAVAILABLE) {
            $this->write_log(self::LOG_LEVEL_INFO, 'PayPalサーバーがメンテナンス中です');
            return false;
        }
        if ($http_status_code !== 200) {
            $this->write_log(
                self::LOG_LEVEL_ERROR,
                'PayPal API access_tokenの取得に失敗しました' . "\n" .
                'APIレスポンスHTTPステータスコード:' . $http_status_code . "\n" .
                'APIレスポンス： . ' . $api_response
            );
            return false;
        }
        $json_decoded_api_response = json_decode($api_response, true);
        if ($json_decoded_api_response['access_token']) {
            $access_token = $json_decoded_api_response['access_token'];
            $this->write_log(self::LOG_LEVEL_INFO, 'access tokenを取得しました');
            return true;
        }
        $this->write_log(
            self::LOG_LEVEL_ERROR,
            'PayPal API access_tokenの取得に失敗しました' . "\n" .
            'APIレスポンスにaccess_tokenがありません' . "\n" .
            'APIレスポンス： . ' . $api_response
        );
        return false;
    }

    /**
     * Get PayPal's CLIENT ID
     *
     * @return mixed
     */
    private function get_paypal_client_id()
    {
        return $this->config->getClientId();
    }

    /**
     * Get PayPal's CLIENT SECRET
     *
     * @return string
     */
    private function get_paypal_client_secret(): string
    {
        return $this->config->getSecret();
    }

    /**
     * Generate access token
     *
     * @param $paypal_client_id
     * @param $paypal_client_secret
     * @param $paypal_status_http
     * @return string
     */
    private function generate_access_token($paypal_client_id, $paypal_client_secret, &$paypal_status_http): string
    {
        $paypal_generate_access_token_api_url = $this->getPaypalGenerateAccessTokenApiUrl();
        $this->curl->addHeader("Content-Type", "application/x-www-form-urlencoded");
        $this->curl->setCredentials($paypal_client_id, $paypal_client_secret);
        $this->curl->setOption(CURLOPT_POSTFIELDS, 'grant_type=client_credentials&ignoreCache=true&return_authn_schemes=true&return_client_metadata=true&return_unconsented_scopes=true');
        $this->curl->post($paypal_generate_access_token_api_url, []);
        $result = $this->curl->getBody();
        $paypal_status_http = $this->curl->getStatus();
        return $result;
    }

    /**
     * Output log
     *
     * @param $log_level
     * @param $message
     * @return void
     */
    private function write_log($log_level, $message): void
    {
        $dateTimeAsTimeZone = $this->dateTime->gmtDate();
        ($this->logger)->log(
            $log_level,
            '[' . $dateTimeAsTimeZone . ']' . '[PayPal Capture Batch]' . '[' . $log_level . ']' .
            $message
        );
        if ($log_level === self::LOG_LEVEL_NOTICE ||
            $log_level === self::LOG_LEVEL_WARNING ||
            $log_level === self::LOG_LEVEL_ERROR) {
            $this->send_system_notice_mail($log_level, $message);
        }
    }

    /**
     * Update marketplace_orders record in database
     *
     * @param $order_increment_id
     * @return bool
     */
    private function update_marketplace_orders($order_increment_id): bool
    {
        try {
            $connection = $this->resource->getConnection();
            $marketplaceOrders = $this->resource->getTableName('marketplace_orders  ');
            $sql = "update " . $marketplaceOrders .
                "set order_status = 'complete', updated_at = current_timestamp
                 where order_id = (select entity_id from sales_order 
                 where increment_id =" . $order_increment_id . ")";
            $connection->query($sql);
        } catch (\Exception $exception) {
            $this->write_log(self::LOG_LEVEL_ERROR, 'marketplace_ordersの更新に失敗しました。' . "\n" .
                '注文番号：' . $order_increment_id . "\n" .
                'エラー内容：' . $exception->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    private function getPaypalGenerateAccessTokenApiUrl()
    {
        if ($this->isProductionMode()) {
            $paypal_generate_access_token_api_url = self::PAYPAL_GENERATE_ACCESS_TOKEN_API_URL_LIVE;
        } else {
            $paypal_generate_access_token_api_url = self::PAYPAL_GENERATE_ACCESS_TOKEN_API_URL_SANDBOX;
        }
        return $paypal_generate_access_token_api_url;
    }

    /**
     * @return bool
     */
    private function isProductionMode()
    {
        return $this->appState->getMode() === \Magento\Framework\App\State::MODE_PRODUCTION;
    }

}
