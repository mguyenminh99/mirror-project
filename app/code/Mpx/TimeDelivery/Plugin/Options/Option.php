<?php
namespace Mpx\TimeDelivery\Plugin\Options;

use Mpx\Marketplace\Helper\CommonFunc;

class Option
{
    /**
     * @var CommonFunc
     */
    public $helperCommonFunc;

    /**
     * @param CommonFunc $helperCommonFunc
     */
    public function __construct(
        CommonFunc $helperCommonFunc
    ) {
        $this->helperCommonFunc = $helperCommonFunc;
    }

    /**
     * @param \XShoppingSt\MpTimeDelivery\Block\Options\Option $subject
     * @param $customerId
     * @return mixed
     */
    public function afterGetCurrentCustomerId(\XShoppingSt\MpTimeDelivery\Block\Options\Option $subject, $customerId)
    {
        return $this->helperCommonFunc->getOriginSellerId($customerId);
    }

}
