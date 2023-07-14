<?php
/**
 * Marketplace block for fieldset of configurable product.
 */

namespace XShoppingSt\Marketplace\Block\Product\Steps;

class Summary extends \Magento\Ui\Block\Component\StepsWizard\StepAbstract
{
    public function getCaption()
    {
        return __('Summary');
    }
}
