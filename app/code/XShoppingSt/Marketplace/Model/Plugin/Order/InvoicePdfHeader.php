<?php
namespace XShoppingSt\Marketplace\Model\Plugin\Order;

/**
 * Marketplace Order PDF InvoicePdfHeader Plugin
 */
class InvoicePdfHeader
{
    /**
     * @var \Magento\Sales\Model\Order\InvoiceFactory
     */
    protected $invoiceModel;

    /**
     * @var XShoppingSt\Marketplace\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Sales\Model\Order\InvoiceFactory $invoiceModel
     */
    public function __construct(
        \Magento\Sales\Model\Order\InvoiceFactory $invoiceModel,
        \XShoppingSt\Marketplace\Helper\Data $helper
    ) {
        $this->invoiceModel = $invoiceModel;
        $this->helper = $helper;
    }
    /**
     * Insert title and number for concrete document type
     *
     * @param  \Zend_Pdf_Page $page
     * @param  string $text
     * @return void
     */
    public function beforeInsertDocumentNumber(\XShoppingSt\Marketplace\Model\Order\Pdf\Invoice $pdfInvoice, $page, $text)
    {
        $invoiceArr = explode(__('Invoice # '), $text);
        $invoiceIncrementedId = $invoiceArr[1];
        $invoice = $this->invoiceModel->create()->loadByIncrementId($invoiceIncrementedId);
        $payment = $invoice->getOrder()->getPayment();
        if (!empty($payment->getMethodInstance())) {
            $method = $payment->getMethodInstance();
            $paymentInfo = $method->getTitle();
        } else {
            $paymentData = $invoice->getOrder()->getPayment()->getData();
            if (!empty($paymentData['additional_information']['method_title'])) {
                $paymentInfo = $paymentData['additional_information']['method_title'];
            } else {
                $paymentInfo = $paymentData['method'];
            }
        }
        /* Payment */
        $yPayments = $pdfInvoice->y + 65;
        $helper = $this->helper;
        if ($helper->getSellerProfileDisplayFlag()) {
            if (!$invoice->getOrder()->getIsVirtual()) {
                $paymentLeft = 35;
            } else {
                $yPayments = $yPayments +15;
                $paymentLeft = 285;
            }
        } else {
            $paymentLeft = 35;
        }
        foreach ($pdfInvoice->getString()->split($paymentInfo, 45, true, true) as $_value) {
            $page->drawText(strip_tags(trim($_value)), $paymentLeft, $yPayments, 'UTF-8');
            $yPayments -= 15;
        }
    }
}
