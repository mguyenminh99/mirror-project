<?php
namespace Mpx\ShipmentInstruction\Controller\ShipmentInstruction;

use Mpx\ShipmentInstruction\Service\ShipmentInstructionService;

class Create extends \Magento\Framework\App\Action\Action
{
    /**
     * @var ShipmentInstructionService
     */
    protected $shipmentInstructionService;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    public $request;

    /**
     * @param ShipmentInstructionService $shipmentInstructionService
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        ShipmentInstructionService $shipmentInstructionService,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->shipmentInstructionService = $shipmentInstructionService;
        $this->request = $request;
        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if( $this->shipmentInstructionService->createShipmentInstructionRecord($this->request->getParam('orderId')) ){
            $this->messageManager->addSuccessMessage(__('Create Shipping Instruction successfully'));
        }else{
            $this->messageManager->addErrorMessage(__('Exception occurred during create shipment instruction'));
        }
        $resultRedirect->setPath('shipmentinstruction/shipmentinstruction/index?order_id='.$this->request->getParam('increment_id'));
        return $resultRedirect;
    }

}
