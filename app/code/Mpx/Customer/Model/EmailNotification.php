<?php
namespace Mpx\Customer\Model;

use Magento\Customer\Model\CustomerRegistry;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Customer\Helper\View as CustomerViewHelper;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\User\Model\User;

/**
 * Customer email notification
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class EmailNotification extends \Magento\Customer\Model\EmailNotification
{
    /**
     * @var CustomerRegistry
     */
    private $customerRegistry;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var CustomerViewHelper
     */
    protected $customerViewHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataProcessor;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var SenderResolverInterface
     */
    private $senderResolver;

    /**
     * @var User
     */
    private $user;

    /**
     * @param CustomerRegistry $customerRegistry
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder $transportBuilder
     * @param CustomerViewHelper $customerViewHelper
     * @param DataObjectProcessor $dataProcessor
     * @param ScopeConfigInterface $scopeConfig
     * @param SenderResolverInterface|null $senderResolver
     * @param User $user
     */
    public function __construct(
        CustomerRegistry $customerRegistry,
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        CustomerViewHelper $customerViewHelper,
        DataObjectProcessor $dataProcessor,
        ScopeConfigInterface $scopeConfig,
        SenderResolverInterface $senderResolver = null,
        User $user
    ) {
        $this->customerRegistry = $customerRegistry;
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->customerViewHelper = $customerViewHelper;
        $this->dataProcessor = $dataProcessor;
        $this->scopeConfig = $scopeConfig;
        $this->senderResolver = $senderResolver ?: ObjectManager::getInstance()->get(SenderResolverInterface::class);
        $this->user = $user;
        parent::__construct(
            $customerRegistry,
            $storeManager,
            $transportBuilder,
            $customerViewHelper,
            $dataProcessor,
            $scopeConfig,
            $senderResolver
        );
    }

    /**
     * Send notification to customer when email or/and password changed
     *
     * @param CustomerInterface $savedCustomer
     * @param string $origCustomerEmail
     * @param bool $isPasswordChanged
     * @return void
     */
    public function credentialsChanged(
        CustomerInterface $savedCustomer,
        $origCustomerEmail,
        $isPasswordChanged = false
    ) {
        if ($origCustomerEmail != $savedCustomer->getEmail()) {
            if ($isPasswordChanged) {
                $this->emailAndPasswordChanged($savedCustomer, $origCustomerEmail);
                $this->emailAndPasswordChanged($savedCustomer, $savedCustomer->getEmail());
                return;
            }

            $this->emailChanged($savedCustomer, $origCustomerEmail);
            $this->emailChanged($savedCustomer, $savedCustomer->getEmail());
            return;
        }

        if ($isPasswordChanged) {
            $this->passwordReset($savedCustomer);
        }
    }

    /**
     * Send email to customer when his email and password is changed
     *
     * @param CustomerInterface $customer
     * @param string $email
     * @return void
     */
    private function emailAndPasswordChanged(CustomerInterface $customer, $email)
    {
        $storeId = $customer->getStoreId();
        if (!$storeId) {
            $storeId = $this->getWebsiteStoreId($customer);
        }

        $customerEmailData = $this->getFullCustomerObject($customer);

        $this->sendEmailTemplate(
            $customer,
            self::XML_PATH_CHANGE_EMAIL_AND_PASSWORD_TEMPLATE,
            self::XML_PATH_FORGOT_EMAIL_IDENTITY,
            ['customer' => $customerEmailData, 'store' => $this->storeManager->getStore($storeId)],
            $storeId,
            $email
        );
    }

    /**
     * Send email to customer when his email is changed
     *
     * @param CustomerInterface $customer
     * @param string $email
     * @return void
     */
    private function emailChanged(CustomerInterface $customer, $email)
    {
        $storeId = $customer->getStoreId();
        if (!$storeId) {
            $storeId = $this->getWebsiteStoreId($customer);
        }

        $customerEmailData = $this->getFullCustomerObject($customer);

        $this->sendEmailTemplate(
            $customer,
            self::XML_PATH_CHANGE_EMAIL_TEMPLATE,
            self::XML_PATH_FORGOT_EMAIL_IDENTITY,
            ['customer' => $customerEmailData, 'store' => $this->storeManager->getStore($storeId)],
            $storeId,
            $email
        );
    }

    /**
     * Send email to customer when his password is reset
     *
     * @param CustomerInterface $customer
     * @return void
     */
    private function passwordReset(CustomerInterface $customer)
    {
        $storeId = $customer->getStoreId();
        $xsAdminEmail = $this->user->loadByUsername('xs-admin')->getEmail();
        if (!$storeId) {
            $storeId = $this->getWebsiteStoreId($customer);
        }

        $customerEmailData = $this->getFullCustomerObject($customer);

        $this->sendEmailTemplate(
            $customer,
            self::XML_PATH_RESET_PASSWORD_TEMPLATE,
            self::XML_PATH_FORGOT_EMAIL_IDENTITY,
            ['customer' => $customerEmailData, 'store' => $this->storeManager->getStore($storeId),
                'xsAdminEmail' => $xsAdminEmail],
            $storeId
        );
    }

    /**
     * Send corresponding email template
     *
     * @param CustomerInterface $customer
     * @param string $template configuration path of email template
     * @param string $sender configuration path of email identity
     * @param array $templateParams
     * @param int|null $storeId
     * @param string $email
     * @return void
     * @throws \Magento\Framework\Exception\MailException
     */
    private function sendEmailTemplate(
        $customer,
        $template,
        $sender,
        $templateParams = [],
        $storeId = null,
        $email = null
    ) {
        $templateId = $this->scopeConfig->getValue($template, 'store', $storeId);
        if ($email === null) {
            $email = $customer->getEmail();
        }

        /** @var array $from */
        $from = $this->senderResolver->resolve(
            $this->scopeConfig->getValue($sender, 'store', $storeId),
            $storeId
        );

        $transport = $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(['area' => 'frontend', 'store' => $storeId])
            ->setTemplateVars($templateParams)
            ->setFrom($from)
            ->addTo($email, $this->customerViewHelper->getCustomerName($customer))
            ->getTransport();

        $transport->sendMessage();
    }

    /**
     * Create an object with data merged from Customer and CustomerSecure
     *
     * @param CustomerInterface $customer
     * @return \Magento\Customer\Model\Data\CustomerSecure
     */
    private function getFullCustomerObject($customer)
    {
        // No need to flatten the custom attributes or nested objects since the only usage is for email templates and
        // object passed for events
        $mergedCustomerData = $this->customerRegistry->retrieveSecureData($customer->getId());
        $customerData = $this->dataProcessor
            ->buildOutputDataArray($customer, \Magento\Customer\Api\Data\CustomerInterface::class);
        $mergedCustomerData->addData($customerData);
        $mergedCustomerData->setData('name', $this->customerViewHelper->getCustomerName($customer));
        return $mergedCustomerData;
    }

    /**
     * Get either first store ID from a set website or the provided as default
     *
     * @param CustomerInterface $customer
     * @param int|string|null $defaultStoreId
     * @return int
     */
    private function getWebsiteStoreId($customer, $defaultStoreId = null)
    {
        if ($customer->getWebsiteId() != 0 && empty($defaultStoreId)) {
            $storeIds = $this->storeManager->getWebsite($customer->getWebsiteId())->getStoreIds();
            $defaultStoreId = reset($storeIds);
        }
        return $defaultStoreId;
    }
}
