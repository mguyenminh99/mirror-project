<?php
namespace Mpx\Sales\Block\Adminhtml\Order;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ButtonNewOrder implements ButtonProviderInterface
{
    /**
     * Hidden Create New Order Button
     *
     * Return []
     */
    public function getButtonData()
    {
        return [];
    }
}
