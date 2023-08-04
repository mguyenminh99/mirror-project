<?php

namespace Mpx\Marketplace\Controller\Account;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use XShoppingSt\Marketplace\Model\SellerFactory;
use Magento\Authorization\Model\UserContextInterface;
use Mpx\Marketplace\Service\Account\SubSellerService;

class Save extends Action
{
    /**
     * @var SubSellerService
     */
    protected $subSellerService;

    /**
     * @var UserContextInterface
     */
    protected $userContext;

    /**
     * @var SellerFactory
     */
    protected $sellerFactory;

    /**
     * @param SellerFactory $sellerFactory
     * @param Context $context
     * @param UserContextInterface $userContext
     * @param SubSellerService $subSellerService
     */
    public function __construct(
        SellerFactory $sellerFactory,
        Context $context,
        UserContextInterface $userContext,
        SubSellerService $subSellerService
    )
    {
        parent::__construct($context);
        $this->sellerFactory = $sellerFactory;
        $this->userContext = $userContext;
        $this->subSellerService = $subSellerService;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $sellerId = $this->userContext->getUserId();
        $currentSeller = $this->sellerFactory->create()->load($sellerId, 'seller_id');
        if($this->subSellerService->saveSubSeller($this->getRequest(), $currentSeller, $currentSeller->getSellerId())){
            $this->messageManager->addSuccessMessage(__('Create Sub Account successfully'));
            return $resultRedirect->setPath('*/*/createseller');
        }else{
            $this->messageManager->addErrorMessage(__('An exception occurred while creating the Sub Account.'));
            return $resultRedirect->setPath('*/*/createseller');
        }
    }
}
