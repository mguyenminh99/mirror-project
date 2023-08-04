<?php

namespace Mpx\MarketplaceBaseShipping\Controller\Shipping;

use XShoppingSt\MarketplaceBaseShipping\Api\ShippingSettingRepositoryInterface;
use XShoppingSt\MarketplaceBaseShipping\Api\Data\ShippingSettingInterfaceFactory;
use Magento\Customer\Api\Data\RegionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Customer\Api\Data\RegionInterfaceFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Model\Session;
use Magento\Directory\Helper\Data as HelperData;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Mpx\Marketplace\Helper\CommonFunc;

/**
 * Shipping address
 */
class FormPost extends \XShoppingSt\MarketplaceBaseShipping\Controller\Shipping\FormPost
{
    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var Mapper
     */
    private $shippingSettingMapper;

    /**
     * @var CommonFunc
     */
    public $helperCommonFunc;


    /**
     * @param Context $context
     * @param Session $customerSession
     * @param FormKeyValidator $formKeyValidator
     * @param FormFactory $formFactory
     * @param ShippingSettingRepositoryInterface $addressRepository
     * @param ShippingSettingInterfaceFactory $addressDataFactory
     * @param RegionInterfaceFactory $regionDataFactory
     * @param DataObjectProcessor $dataProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param RegionFactory $regionFactory
     * @param HelperData $helperData
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        FormKeyValidator $formKeyValidator,
        ShippingSettingRepositoryInterface $shippingSettingRepository,
        ShippingSettingInterfaceFactory $shippingSettingDataFactory,
        RegionInterfaceFactory $regionDataFactory,
        DataObjectProcessor $dataProcessor,
        DataObjectHelper $dataObjectHelper,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        RegionFactory $regionFactory,
        HelperData $helperData,
        CommonFunc $helperCommonFunc

    ) {
        $this->regionFactory = $regionFactory;
        $this->helperData = $helperData;
        $this->shippingSettingRepository = $shippingSettingRepository;
        $this->shippingSettingDataFactory = $shippingSettingDataFactory;
        $this->customerSession = $customerSession;
        $this->_formKeyValidator = $formKeyValidator;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->helperCommonFunc = $helperCommonFunc;
        parent::__construct($context,
            $customerSession,
            $formKeyValidator,
            $shippingSettingRepository,
            $shippingSettingDataFactory,
            $regionDataFactory,
            $dataProcessor,
            $dataObjectHelper,
            $resultForwardFactory,
            $resultPageFactory,
            $regionFactory,
            $helperData);
    }

    /**
     * Extract address from request
     *
     * @return \XShoppingSt\MarketplaceBaseShipping\Api\Data\ShippingSettingInterface
     */
    protected function _extractSetting()
    {
        $existingData = $this->getExistingAddressData();
        if (empty($existingData)) {
            $existingData = $this->getRequest()->getParams();
        }

        $this->updateRegionData($existingData);

        $attributeValues = $this->getRequest()->getParams();

        $this->updateRegionData($attributeValues);

        $shippingDataObject = $this->shippingSettingDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $shippingDataObject,
            array_merge($existingData, $attributeValues),
            \XShoppingSt\MarketplaceBaseShipping\Api\Data\ShippingSettingInterface::class
        );

        $shippingDataObject->setSellerId($this->helperCommonFunc->getOriginSellerId($this->customerSession->getCustomerId()));

        return $shippingDataObject;
    }

    /**
     * Retrieve existing address data
     *
     * @return array
     * @throws \Exception
     */
    protected function getExistingAddressData()
    {
        $existingData = [];
        if ($sellerId = $this->helperCommonFunc->getOriginSellerId($this->helperCommonFunc->getCustomerId())) {
            $existing = $this->shippingSettingRepository->getBySellerId($sellerId);
            if ($existing->getId() && $existing->getSellerId() !== $this->helperCommonFunc->getOriginSellerId($this->helperCommonFunc->getCustomerId())) {
                throw new CouldNotSaveException(__('We can\'t save the origin address.'));
            }
            if ($existing->getId()) {
                $existingData = $this->getShippingSettingMapper()->toFlatArray($existing);
            }
        }
        return $existingData;
    }

    /**
     * Process address form save
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $redirectUrl = null;

        if (!$this->getRequest()->isPost()) {
            $this->customerSession->setAddressFormData($this->getRequest()->getPostValue());
            return $this->resultRedirectFactory->create()->setUrl(
                $this->_redirect->error($this->_buildUrl('*/*/'))
            );
        }
        try {
            $settingModel = $this->_extractSetting();

            $this->shippingSettingRepository->save($settingModel);
            $this->messageManager->addSuccess(__('You saved the shipping origin address.'));
            $url = $this->_buildUrl('*/*/index', ['_secure' => true]);
            return $this->resultRedirectFactory->create()->setUrl($this->_redirect->success($url));
        } catch (InputException $e) {
            $this->messageManager->addError($e->getMessage());
            foreach ($e->getErrors() as $error) {
                $this->messageManager->addError($error->getMessage());
            }
        } catch (CouldNotSaveException $e) {
            $redirectUrl = $this->_buildUrl('*/*/index');
            $this->messageManager->addException($e);
        }

        $url = $redirectUrl;
        if (!$redirectUrl) {
            $this->_getSession()->setAddressFormData($this->getRequest()->getPostValue());
            $url = $this->_buildUrl('*/*/index');
        }

        return $this->resultRedirectFactory->create()->setUrl($this->_redirect->error($url));
    }

    /**
     * Get Shipping Setting Mapper instance
     *
     * @return Mapper
     */
    private function getShippingSettingMapper()
    {
        if ($this->shippingSettingMapper === null) {
            $this->shippingSettingMapper = ObjectManager::getInstance()->get(
                \XShoppingSt\MarketplaceBaseShipping\Model\ShippingSetting\Mapper::class
            );
        }
        return $this->shippingSettingMapper;
    }

}
