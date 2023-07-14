<?php
namespace XShoppingSt\Marketplace\Block\Order\Invoice\Totals;

class Cod extends \XShoppingSt\Marketplace\Block\Order\Totals\Cod
{
    /**
     * Add Cod total string
     *
     * @param string $currencyRate
     * @param string $after
     */
    protected function _addCodCharges($currencyRate, $after = 'discount')
    {
        $parent = $this->getParentBlock();
        $invoiceId = $parent->getInvoice()->getId();
        $codchargesData = $this->orderCollection
        ->addFieldToFilter(
            'main_table.order_id',
            $this->getOrder()->getId()
        )->addFieldToFilter(
            'main_table.seller_id',
            $this->helper->getCustomerId()
        )->getTotalSellerInvoiceCodCharges($invoiceId);

        $codchargesTotal = $codchargesData[0]['cod_charges'];

        $codTotal = new \Magento\Framework\DataObject(
            [
                'code' => 'cod',
                'base_value' => $codchargesTotal,
                'value' => $this->helper->getCurrentCurrencyPrice($currencyRate, $codchargesTotal),
                'label' => __('Total COD Charges')
            ]
        );
        $this->getParentBlock()->addTotal($codTotal, $after);
        return $this;
    }
}
