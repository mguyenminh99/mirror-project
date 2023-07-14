<?php
namespace XShoppingSt\Marketplace\Block\Order\Invoice;

class Totals extends \XShoppingSt\Marketplace\Block\Order\Totals
{
    /**
     * Get totals source object
     *
     * @return Order
     */
    public function getSource()
    {
        $collection = $this->orderCollection
        ->addFieldToFilter(
            'main_table.order_id',
            $this->getOrder()->getId()
        )->addFieldToFilter(
            'main_table.seller_id',
            $this->helper->getCustomerId()
        )->getSellerInvoiceTotals($this->getInvoice()->getId());
        return $collection;
    }

    /**
     * Retrieve current invoice model instance.
     */
    public function getInvoice()
    {
        return $this->_coreRegistry->registry('current_invoice');
    }

    /**
     * @return array
     */
    public function getLabelProperties()
    {
        $paymentCode = '';
        if ($this->_order->getPayment()) {
            $paymentCode = $this->getOrder()->getPayment()->getMethod();
        }
        if ($paymentCode == 'mpcashondelivery') {
            return 'colspan="7" class="mark"';
        }
        return 'colspan="6" class="mark"';
    }
}
