<?php

namespace Mpx\Marketplace\Block\Seller;

use Magento\Framework\App\Helper\Context;
use XShoppingSt\Marketplace\Model\SellerFactory as MpSeller;
use Mpx\Marketplace\Helper\Constant;

/**
 * Mpx Marketplace Sellerlist .
 */
class ListSeller extends \Magento\Framework\View\Element\Template
{

    /**
     * @var MpSeller
     */
    protected $mpSeller;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @param Context $context
     * @param MpSeller $mpSeller
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        MpSeller                                         $mpSeller,
        \Magento\Framework\App\Request\Http              $request,
        array                                            $data = []
    ) {
        $this->mpSeller = $mpSeller;
        $this->request = $request;

        parent::__construct($context, $data);
    }

    /**
     *  Get Seller Data
     *
     * @return \Magento\Framework\Data\Collection\AbstractDb|\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|null
     */
    public function getSellerData()
    {
        $sellerData = $this->mpSeller->create()->getCollection();
        $sellerData->addFieldToFilter('is_seller', Constant::SELLER_STATUS_OPENING);
        $sellerData->addFieldToFilter('store_id', ['eq' => 1]);
        return $sellerData;
    }

    /**
     * Get Full Action Name
     *
     * @return string
     */

    public function getFullActionName()
    {
        return $this->request->getFullActionName();
    }
}
