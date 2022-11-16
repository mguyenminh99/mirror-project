<?php

namespace Mpx\LoginAsCustomer\Plugin\Button;

use \Magento\Backend\Block\Widget\Button\Toolbar\Interceptor;
use \Magento\Framework\View\Element\AbstractBlock;
use \Magento\Backend\Block\Widget\Button\ButtonList;

class ToolbarPlugin extends \Magefan\LoginAsCustomer\Plugin\Button\ToolbarPlugin
{

    /**
     * @param Interceptor $subject
     * @param AbstractBlock $context
     * @param ButtonList $buttonList
     */
    public function beforePushButtons(
        Interceptor   $subject,
        AbstractBlock $context,
        ButtonList    $buttonList
    )
    {
        $order = false;
        $nameInLayout = $context->getNameInLayout();

        if ('sales_order_edit' == $nameInLayout) {
            $order = $context->getOrder();
        } elseif ('sales_invoice_view' == $nameInLayout) {
            $order = $context->getInvoice()->getOrder();
        } elseif ('sales_shipment_view' == $nameInLayout) {
            $order = $context->getShipment()->getOrder();
        } elseif ('sales_creditmemo_view' == $nameInLayout) {
            $order = $context->getCreditmemo()->getOrder();
        }
        if ($order) {
            if ($this->isAllowed()) {
                if (!empty($order['customer_id'])) {
                    $buttonUrl = $context->getUrl('loginascustomer/login/login', [
                        'customer_id' => $order['customer_id']
                    ]);
                } elseif (\Magefan\Community\Model\UrlChecker::showUrl($this->urlInterface->getCurrentUrl())) {
                    $buttonUrl = $context->getUrl('loginascustomer/guest/convert');
                    $buttonList->add(
                        'guest_to_customer',
                        ['label' => __('Convert Guest to Customer'), 'onclick' => 'window.open(\'' . $buttonUrl . '\')', 'class' => 'reset'],
                        -1
                    );
                }
            }
        }
    }
}
