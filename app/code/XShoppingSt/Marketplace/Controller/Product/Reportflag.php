<?php
namespace XShoppingSt\Marketplace\Controller\Product;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use XShoppingSt\Marketplace\Helper\Data as HelperData;
use XShoppingSt\Marketplace\Helper\Email as MpEmailData;
use Magento\Framework\Json\Helper\Data as JsonHelper;

/**
 * XShoppingSt Marketplace Reportflag controller.
 */
class Reportflag extends Action
{
    /**
     * @var HelperData
     */
    protected $helper;

    /**
     * @var MpEmailData
     */
    protected $mpEmailHelper;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \XShoppingSt\Marketplace\Model\ProductFlagsFactory
     */
    protected $productFlags;

    /**
     * @param Context  $context
     * @param HelperData  $helper
     * @param MpEmailData  $mpEmailHelper
     * @param JsonHelper  $jsonHelper
     */
    public function __construct(
        Context $context,
        HelperData $helper,
        MpEmailData $mpEmailHelper,
        JsonHelper $jsonHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \XShoppingSt\Marketplace\Model\ProductFlagsFactory $productFlags
    ) {
        $this->helper = $helper;
        $this->mpEmailHelper = $mpEmailHelper;
        $this->jsonHelper = $jsonHelper;
        $this->date = $date;
        $this->productFlags = $productFlags;
        parent::__construct($context);
    }

    /**
     * Report Flag for Product
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $helper = $this->helper;
        $data = $this->getRequest()->getParams();
        if ($data['seller_id']) {
            if (!isset($data['reason'])) {
                $data['reason'] = "";
            } elseif ($data['reason'] == "other_value") {
                $data['reason'] = $data['flag_other_reason'];
            }
            $data['created_at'] = $this->date->gmtDate();
            try {
                $productFlagModel = $this->productFlags->create()
                              ->addData($data)
                              ->save();
                $senderInfo = [
                'name' => $data['name'],
                'email' => $data['email']
                ];
                $receiverInfo = [
                'name' => $helper->getAdminName(),
                'email' => $helper->getAdminEmailId()
                ];
                $emailTemplateVariables['admin_name'] = $helper->getAdminName();
                $emailTemplateVariables['product_name'] = $data['product_name'];
                $emailTemplateVariables['reporter_name'] = $data['name'];
                $emailTemplateVariables['reporter_email'] = $data['email'];
                $emailTemplateVariables['reason'] = $data['reason'];
                $this->mpEmailHelper->sendProductFlagMail(
                    $emailTemplateVariables,
                    $senderInfo,
                    $receiverInfo
                );
            } catch (\Exception $e) {
                $helper->logDataInLogger("Product_ReportflagController_Execute : ".$e->getMessage());
            }
        }
        $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode('true')
        );
    }
}
