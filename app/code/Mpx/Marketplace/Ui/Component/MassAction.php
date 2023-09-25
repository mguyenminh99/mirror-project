<?php
namespace Mpx\Marketplace\Ui\Component;

class MassAction extends \Magento\Ui\Component\MassAction
{
    public function prepare()
    {
        parent::prepare();
        $config = $this->getConfiguration();
        $config['actions'] = [];
        $this->setData('config', (array)$config);
    }
}
