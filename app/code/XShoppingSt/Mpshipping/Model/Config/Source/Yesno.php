<?php
namespace XShoppingSt\Mpshipping\Model\Config\Source;

class Yesno implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return options array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
          [
              'value' => 'yes',
              'label' => __('Yes')
          ],[
              'value' => 'no',
              'label' => __('No')
          ]
        ];
    }
}
