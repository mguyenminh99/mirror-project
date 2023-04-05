<?php

namespace Mpx\Checkout\Plugin\Block\Checkout;

class AttributeMerger
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     *
     * separate street line
     *
     * @param \Magento\Checkout\Block\Checkout\AttributeMerger $subject
     * @param $result
     * @return mixed
     */
    public function afterMerge(\Magento\Checkout\Block\Checkout\AttributeMerger $subject, $result)
    {
        if (isset($result["street"]['children']) && $result["street"]['children']) {

            // separate street line
            unset($result["street"]['config']);
            unset($result["street"]['label']);
            $result["street"]['dataScope'] = 'shippingAddress.street';
            $result["street"]['required'] = false;
            if (isset($result["street"]['children'][0])) {
                $result["street"]["children"][0]['label'] = __('Personal Address');
                $result["street"]["children"][0]['config']['customScope'] = 'shippingAddress';
                unset($result["street"]["children"][0]['additionalClasses']);
            }
            if (isset($result["street"]['children'][1])) {
                $result["street"]["children"][1]['label'] = __('Building Address');
                $result["street"]["children"][1]['config']['customScope'] = 'shippingAddress';
                unset($result["street"]["children"][1]['additionalClasses']);
            }
            //end separate street line
        }
        return $result;
    }
}
