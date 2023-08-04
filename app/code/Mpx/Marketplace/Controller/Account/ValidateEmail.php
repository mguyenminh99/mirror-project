<?php
namespace Mpx\Marketplace\Controller\Account;

use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Mpx\Marketplace\Service\Account\SubSellerService;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class ValidateEmail extends Action
{
    /**
     * @var SubSellerService
     */
    protected $subSellerService;

    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var JsonFactory
     */
    protected $jsonResultFactory;

    /**
     * @param Context $context
     * @param SubSellerService $subSellerService
     * @param CustomerFactory $customerFactory
     * @param StoreManagerInterface $storeManager
     * @param JsonFactory $jsonResultFactory
     */
    public function __construct(
        Context $context,
        SubSellerService $subSellerService,
        CustomerFactory $customerFactory,
        StoreManagerInterface $storeManager,
        JsonFactory $jsonResultFactory
    )
    {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->customerFactory = $customerFactory;
        $this->subSellerService = $subSellerService;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(){
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        $email = $this->_request->getParam('email');
        $customer = $this->customerFactory->create()
        ->setWebsiteId($websiteId)
        ->loadByEmail($email);

        $isEmailExists = $customer->getId() ? true : false;

        $result = $this->jsonResultFactory->create();
        return $result->setData(['is_email_exists' => $isEmailExists]);
    }
}
