<?php
namespace Mpx\Sales\Plugin\Order;

use Magento\Sales\Block\Adminhtml\Order\View;

class AfterView
{
    /**
     * Remove button
     *
     * @param View $subject
     * @return null
     */
    public function beforeSetLayout(View $subject)
    {
        $subject->removeButton('order_edit');
        $subject->removeButton('order_cancel');
        $subject->removeButton('send_notification');
        $subject->removeButton('order_creditmemo');
        $subject->removeButton('void_payment');
        $subject->removeButton('order_hold');
        $subject->removeButton('order_unhold');
        $subject->removeButton('accept_payment');
        $subject->removeButton('deny_payment');
        $subject->removeButton('get_review_payment_update');
        $subject->removeButton('order_invoice');
        $subject->removeButton('order_ship');
        $subject->removeButton('order_reorder');
        return null;
    }
}
