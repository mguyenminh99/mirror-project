<?php

namespace Mpx\Marketplace\Controller\Order;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Customer\Model\Url as CustomerUrl;
use Mpx\Marketplace\Helper\CommonFunc;
use XShoppingSt\Marketplace\Helper\Data as HelperData;
use XShoppingSt\Marketplace\Model\SellerFactory as MpSellerModel;

/**
 *  Marketplace Order Print PDF Header Infomation Save Controller.
 */
class Printpdfinfo extends \XShoppingSt\Marketplace\Controller\Order\Printpdfinfo
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $_formKeyValidator;

    /**
     * @var CustomerUrl
     */
    protected $customerUrl;

    /**
     * @var HelperData
     */
    protected $helper;

    /**
     * @var MpSellerModel
     */
    protected $mpSellerModel;

    /**
     * @var CommonFunc
     */
    public $helperCommonFunc;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param FormKeyValidator $formKeyValidator
     * @param CustomerUrl $customerUrl
     * @param HelperData $helper
     * @param MpSellerModel $mpSellerModel
     * @param CommonFunc $helperCommonFunc
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        FormKeyValidator $formKeyValidator,
        CustomerUrl $customerUrl,
        HelperData $helper,
        MpSellerModel $mpSellerModel,
        CommonFunc $helperCommonFunc

    ) {
        $this->_customerSession = $customerSession;
        $this->_formKeyValidator = $formKeyValidator;
        $this->customerUrl = $customerUrl;
        $this->helper = $helper;
        $this->mpSellerModel = $mpSellerModel;
        $this->helperCommonFunc = $helperCommonFunc;
        parent::__construct(
            $context,
            $customerSession,
            $formKeyValidator,
            $customerUrl,
            $helper,
            $mpSellerModel
        );
    }

    /**
     * Order Print PDF Header Infomation Save action.
     *
     * @return \Magento\Framework\Controller\Result\RedirectFactory
     */
    public function execute()
    {
        $helper = $this->helper;
        $isPartner = $helper->isSeller();
        if ($isPartner == 1) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();

            if ($this->getRequest()->isPost()) {
                try {
                    if (!$this->_formKeyValidator->validate($this->getRequest())) {
                        return $this->resultRedirectFactory->create()->setPath(
                            '*/*/shipping',
                            ['_secure' => $this->getRequest()->isSecure()]
                        );
                    }
                    $fields = $this->getRequest()->getParams();
                    $sellerId = $this->_getSession()->getCustomerId();
                    $storeId = $helper->getCurrentStoreId();
                    $autoId = 0;
                    $collection = $this->mpSellerModel->create()
                        ->getCollection()
                        ->addFieldToFilter(
                            'seller_id',
                            $this->helperCommonFunc->getOriginSellerId($sellerId)
                        )
                        ->addFieldToFilter(
                            'store_id',
                            $storeId
                        );
                    foreach ($collection as $value) {
                        $autoId = $value->getId();
                    }
                    $sellerData = [];
                    if (!$autoId) {
                        $sellerDefaultData = [];
                        $collection = $this->mpSellerModel->create()
                            ->getCollection()
                            ->addFieldToFilter('seller_id', $sellerId)
                            ->addFieldToFilter('store_id', 0);
                        foreach ($collection as $value) {
                            $sellerDefaultData = $value->getData();
                            $value->setOthersInfo($fields['others_info']);
                            $value->save();
                        }
                        foreach ($sellerDefaultData as $key => $value) {
                            if ($key != 'entity_id') {
                                $sellerData[$key] = $value;
                            }
                        }
                    }

                    $value = $this->mpSellerModel->create()->load($autoId);
                    if (!empty($sellerData)) {
                        $value->addData($sellerData);
                    }
                    $value->setOthersInfo($fields['others_info']);
                    $value->setStoreId($storeId);
                    $value->save();
                    $this->messageManager->addSuccess(
                        __('Information was successfully saved')
                    );

                    return $this->resultRedirectFactory->create()->setPath(
                        '*/*/shipping',
                        ['_secure' => $this->getRequest()->isSecure()]
                    );
                } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());

                    return $this->resultRedirectFactory->create()->setPath(
                        '*/*/shipping',
                        ['_secure' => $this->getRequest()->isSecure()]
                    );
                }
            } else {
                return $this->resultRedirectFactory->create()->setPath(
                    '*/*/shipping',
                    ['_secure' => $this->getRequest()->isSecure()]
                );
            }
        } else {
            return $this->resultRedirectFactory->create()->setPath(
                'marketplace/account/becomeseller',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        }
    }
}
