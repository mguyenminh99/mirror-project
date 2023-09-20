<?php
namespace Mpx\ShipmentInstruction\Controller\ShipmentInstruction;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Mpx\ShipmentInstruction\Service\ShipmentInstructionService;

class Delete extends Action
{
    /**
     * @var ShipmentInstructionService
     */
    protected $shipmentInstructionService;

    /**
     * @param Context $context
     */
    public function __construct(
        ShipmentInstructionService $shipmentInstructionService,
        Context $context
    ) {
        $this->shipmentInstructionService = $shipmentInstructionService;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if( $this->shipmentInstructionService->deleteRecord( $this->getRequest()->getParam('entity_id') ) ){
            $this->messageManager->addSuccessMessage(__('Delete Shipping Instruction successfully'));
        }else{
            $this->messageManager->addErrorMessage(__('Exception occurred during delete shipment instruction'));
        }
        return $resultRedirect->setPath('shipmentinstruction/shipmentinstruction/index/');
    }
}
