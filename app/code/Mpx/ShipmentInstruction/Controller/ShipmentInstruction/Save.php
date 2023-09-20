<?php
namespace Mpx\ShipmentInstruction\Controller\ShipmentInstruction;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Mpx\ShipmentInstruction\Model\ShipmentInstructionFactory;
use Mpx\ShipmentInstruction\Service\ShipmentInstructionService;

class Save extends Action
{
    /**
     * @var ShipmentInstructionService
     */
    protected $shipmentInstructionService;

    /**
     * @var ShipmentInstructionFactory
     */
    public $shipmentInstructionRepository;

    /**
     * @param Context $context
     */
    public function __construct(
        ShipmentInstructionService $shipmentInstructionService,
        Context $context,
        ShipmentInstructionFactory $shipmentInstructionRepository
    ) {
        $this->shipmentInstructionService = $shipmentInstructionService;
        $this->shipmentInstructionRepository = $shipmentInstructionRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $shipmentFactory = $this->shipmentInstructionRepository->create();
        $shipmentInstruction = $shipmentFactory->load($this->getRequest()->getParam('entity_id'));

        if ($this->getRequest()->getParam('form') === 'edit') {
            try {
                $this->shipmentInstructionService->saveShipmentInstructionData($shipmentInstruction, $this->getRequest());
                $this->messageManager->addSuccessMessage(__('Save Shipping Instruction successfully'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Exception occurred during save shipment instruction'));
            }
        } else {
            try{
                $this->shipmentInstructionService->saveDuplicatedShipmentInstructionRecord($shipmentInstruction, $this->getRequest());
                $this->messageManager->addSuccessMessage(__('Duplicate Shipping Instruction successfully'));
            }catch (\Exception $e){
                $this->messageManager->addErrorMessage(__('Exception occurred during duplicate shipment instruction'));
            }
        }
        $resultRedirect->setPath('*/*/edit/', ['id' => $shipmentInstruction->getEntityId()]);
        return $resultRedirect;
    }
}
