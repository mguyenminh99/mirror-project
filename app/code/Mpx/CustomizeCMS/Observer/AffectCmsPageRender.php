<?php

namespace Mpx\CustomizeCMS\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;

class AffectCmsPageRender implements ObserverInterface
{
    public function execute(EventObserver $observer)
    {
        $page = $observer->getPage();
        if($page->getIdentifier() == 'home'){
            $page->setContent('');
            $page->setContentHeading('');
        }
        return $this;
    }
}
