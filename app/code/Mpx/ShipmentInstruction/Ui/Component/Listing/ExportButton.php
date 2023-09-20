<?php

namespace Mpx\ShipmentInstruction\Ui\Component\Listing;

/**
 * Class ExportButton
 */
class ExportButton extends \Magento\Ui\Component\ExportButton
{
    /**
     * @return void
     */
    public function prepare()
    {
        $context = $this->getContext();
        $config = $this->getData('config');
        unset($config['options']['xml']);
        if (isset($config['options']['csv'])) {
            $config['options']['csv']['url'] =  $config['options']['cvs']['url'];
            unset($config['options']['cvs']);
        }
        if (isset($config['options'])) {
            $options = [];
            foreach ($config['options'] as $option) {
                $additionalParams = $this->getAdditionalParams($config, $context);
                $option['url'] = $this->urlBuilder->getUrl($option['url'], $additionalParams);
                $options[] = $option;
            }
            $config['options'] = $options;
            $this->setData('config', $config);
        }
        parent::prepare();
    }
}
