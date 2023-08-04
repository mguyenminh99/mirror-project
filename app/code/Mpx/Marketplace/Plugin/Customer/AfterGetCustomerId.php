<?php

namespace Mpx\Marketplace\Plugin\Customer;
use Mpx\Marketplace\Helper\CommonFunc;

class AfterGetCustomerId
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
    )
    {
        $this->helperCommonFunc = $helperCommonFunc;
    }

    /**
     * @param \XShoppingSt\Marketplace\Helper\Data $subject
     * @param $result
     * @return mixed
     */
    public function afterGetCustomerId(\XShoppingSt\Marketplace\Helper\Data $subject, $result)
    {
        return $this->helperCommonFunc->getOriginSellerId($result);
    }
}
