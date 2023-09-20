<?php
namespace Mpx\ShipmentInstruction\Service\ShipmentInstruction;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use XShoppingSt\MpApi\Model\Seller\SellerManagement;

class B2CloudExport extends Template
{
    /**
     * @var SellerManagement
     */
    protected $sellerManagement;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param SellerManagement $sellerManagement
     * @param ScopeConfigInterface $scopeConfig
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        SellerManagement $sellerManagement,
        ScopeConfigInterface $scopeConfig,
        Template\Context $context,
        array $data = []
    ) {
        $this->sellerManagement = $sellerManagement;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * Prepare export data format
     *
     * @param $shipmentInstruction
     * @param $shippingSettingData
     * @param $sellerId
     * @return array
     */
    public function prepareExportData($shipmentInstruction, $shippingSettingData, $sellerId){
        $seller = $this->sellerManagement->getSeller($sellerId);
        $deliveryFormat = $this->getRequest()->getParam('delivery_format');
        $deliveryStreetData = explode("\n", $shipmentInstruction->getDestinationStreet());
        $data = [
            "Customer Control Number" => '',
            "Invoice Type" => $deliveryFormat,
            "Cool category" => '',
            "Invoice number" => '',
            "Estimated ship date" => date('Y/m/d', strtotime($this->getRequest()->getParam('estimated_ship_date'))),
            "Estimated delivery date" => '',
            "Delivery time zone" => '',
            "Delivery code" => '',
            "Delivery phone number" => $shipmentInstruction->getDestinationTelephone(),
            "Delivery address phone number branch number" => '',
            "Delivery postal code" => $shipmentInstruction->getDestinationPostcode(),
            "Delivery address" => str_replace([' ', '　', "\n"], "", $shipmentInstruction->getDestinationRegion() .
                $shipmentInstruction->getDestinationCity() . $deliveryStreetData[0]),
            "Destination Apartment Name" => isset($deliveryStreetData[1]) ? str_replace([' ', '　', "\n"], "", $deliveryStreetData[1]) : "",
            "Delivery Company-Department 1" => '',
            "Delivery Company-Department 2" => '',
            "Delivery Name" => $shipmentInstruction->getDestinationCustomerName(),
            "Addressee name (kana)" => '',
            "Title" => '',
            "Requester code" => '',
            "Requester phone number" => $shippingSettingData->getTelephone(),
            "Requester phone number branch number" => '',
            "Requester postal code" => $shippingSettingData->getPostalCode(),
            "Requester Address" => str_replace([' ', '　', "\n"], "", $shippingSettingData->getRegion() . $shippingSettingData->getCity() .
                $shippingSettingData->getStreet()[0]),
            "Client apartment" => str_replace([' ', '　', "\n"], "",$shippingSettingData->getStreet()[1]),
            "Requester name" => $seller['item']['shop_title'],
            "Requester Name (Kana)" => '',
            "Product name code 1" => '',
            "Item 1" => $this->scopeConfig->getValue("mpx_web/general/marketplaceName"),
            "Product name code 2" => '',
            "Product Name 2" => '',
            "Handling 1" => '',
            "Handling 2" => '',
            "Article" => '',
            "Collect cash on delivery amount (tax included)" => '',
            "Consumption tax, etc." => '',
            "Stop" => '',
            "Office code" => '',
            "Issue number" => '',
            "Number display flag" => '',
            "Billing Customer Code" => $shippingSettingData->getData('b2cloud_billing_customer_code'),
            "Billing Classification Code" => $shippingSettingData->getData('b2cloud_billing_classification_code'),
            "Fare control number" => $shippingSettingData->getData('b2cloud_fare_control_number'),
            "Kuroneko Web Collect data registration" => '',
            "Kuroneko Web Collect Merchant Number" => '',
            "Kuroneko web collect application reception number 1" => '',
            "Kuroneko Web Collect application reception number 2" => '',
            "Kuroneko Web Collect application reception number 3" => '',
            "Scheduled delivery e-mail usage category" => '',
            "Scheduled delivery e-mail e-mail address" => '',
            "Input model" => '',
            "Expected delivery email message" => '',
            "Delivery Completion Email Usage Category" => '',
            "Delivery completion e-mail e-mail address" => '',
            "Delivery complete email message" => '',
            "Kuroneko storage agency usage category" => '',
            "Reserved" => '',
            "Receipt agency billing amount (tax included)" => '',
            "Amount of consumption tax, etc. in receipt agency" => '',
            "Billing billing postal code" => '',
            "Billing Address" => '',
            "Receipt billing address (apartment name)" => '',
            "Receiving agency billing company - department name 1" => '',
            "Receiving agency billing company - department name 2" => '',
            "Receipt Agency Billing Name (Kanji)" => '',
            "Receipt Agency Billing Name (Kana)" => '',
            "Receipt agency contact name (Kanji)" => '',
            "Receipt agency contact zip code" => '',
            "Collection agency contact address" => '',
            "Receipt agency contact address 1" => '',
            "Telephone number for collection agent inquiries" => '',
            "Receipt agent management number" => '',
            "Receipt agent name" => '',
            "Remarks on storage agent" => '',
            "Multiple linking key" => '',
            "Search key title 1" => '',
            "Search key 1" => '',
            "Search key title 2" => '',
            "Search key 2" => '',
            "Search key title 3" => '',
            "Search key 3" => '',
            "Search key title 4" => '',
            "Search key 4" => '',
            "Search key title 5" => '',
            "Search key 5" => '',
            "Reserved 1" => '',
            "Reserved 2" => '',
            "Scheduled mail usage category" => '',
            "E-mail address for scheduled mailing" => '',
            "Scheduled mail message" => '',
            "Posting Completion Mail (Delivery Address) Usage Classification" => '',
            "Posting Completion Mail (Delivery Address) e-mail address" => '',
            "Posting completion mail (addressee) mail message" => '',
            "Posting completion mail (to the requester) use category" => '',
            "Posting Completion E-mail (for requester) e-mail address" => '',
            "Posting Completion Mail (To Requester) Mail Message" => ''
        ];

        return $data;
    }

    /**
     * Get csv export header
     *
     * @return string[]
     */
    public function getHeader() {
        return $headers = [
            'お客様管理番号',
            '送り状種類',
            'クール区分',
            '伝票番号',
            '出荷予定日',
            'お届け予定日',
            '配達時間帯',
            'お届け先コード',
            'お届け先電話番号',
            'お届け先電話番号枝番',
            'お届け先郵便番号',
            'お届け先住所',
            'お届け先アパートマンション名',
            'お届け先会社-部門１',
            'お届け先会社-部門２',
            'お届け先名',
            'お届け先名(ｶﾅ)',
            '敬称',
            'ご依頼主コード',
            'ご依頼主電話番号',
            'ご依頼主電話番号枝番',
            'ご依頼主郵便番号',
            'ご依頼主住所',
            'ご依頼主アパートマンション',
            'ご依頼主名',
            'ご依頼主名(ｶﾅ)',
            '品名コード１',
            '品名１',
            '品名コード２',
            '品名２',
            '荷扱い１',
            '荷扱い２',
            '記事',
            'ｺﾚｸﾄ代金引換額（税込)',
            '内消費税額等',
            '止置き',
            '営業所コード',
            '発行枚数',
            '個数口表示フラグ',
            '請求先顧客コード',
            '請求先分類コード',
            '運賃管理番号',
            'クロネコwebコレクトデータ登録',
            'クロネコwebコレクト加盟店番号',
            'クロネコwebコレクト申込受付番号１',
            'クロネコwebコレクト申込受付番号２',
            'クロネコwebコレクト申込受付番号３',
            'お届け予定ｅメール利用区分',
            'お届け予定ｅメールe-mailアドレス',
            '入力機種',
            'お届け予定ｅメールメッセージ',
            'お届け完了ｅメール利用区分',
            'お届け完了ｅメールe-mailアドレス',
            'お届け完了ｅメールメッセージ',
            'クロネコ収納代行利用区分',
            '予備',
            '収納代行請求金額(税込)',
            '収納代行内消費税額等',
            '収納代行請求先郵便番号',
            '収納代行請求先住所',
            '収納代行請求先住所（アパートマンション名）',
            '収納代行請求先会社-部門名１',
            '収納代行請求先会社-部門名２',
            '収納代行請求先名(漢字)',
            '収納代行請求先名(カナ)',
            '収納代行問合せ先名(漢字)',
            '収納代行問合せ先郵便番号',
            '収納代行問合せ先住所',
            '収納代行問合せ先住所（アパートマンション名）',
            '収納代行問合せ先電話番号',
            '収納代行管理番号',
            '収納代行品名',
            '収納代行備考',
            '複数口くくりキー',
            '検索キータイトル1',
            '検索キー1',
            '検索キータイトル2',
            '検索キー2',
            '検索キータイトル3',
            '検索キー3',
            '検索キータイトル4',
            '検索キー4',
            '検索キータイトル5',
            '検索キー5',
            '予備',
            '予備',
            '投函予定メール利用区分',
            '投函予定メールe-mailアドレス',
            '投函予定メールメッセージ',
            '投函完了メール（お届け先宛）利用区分',
            '投函完了メール（お届け先宛）e-mailアドレス',
            '投函完了メール（お届け先宛）メールメッセージ',
            '投函完了メール（ご依頼主宛）利用区分',
            '投函完了メール（ご依頼主宛）e-mailアドレス',
            '投函完了メール（ご依頼主宛）メールメッセージ'
        ];
    }
}
