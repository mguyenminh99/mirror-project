<?php

namespace Mpx\Customer\Controller\Account;

use Magento\Customer\Model\Session;
use Mpx\Marketplace\Helper\CommonFunc as MpxHelperData;


class LoginPost
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var MpxHelperData
     */
    protected $mpxHelperData;

    public function __construct(
        Session          $customerSession,
        MpxHelperData    $mpxHelperData
    ) {
        $this->session = $customerSession;
        $this->mpxHelperData = $mpxHelperData;
    }

    /**
     * @param \Magento\Customer\Controller\Account\LoginPost $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(\Magento\Customer\Controller\Account\LoginPost $subject,  $result)
    {
        if ($this->session->isLoggedIn()) {
            if ($this->mpxHelperData->isSellerLogin()) {
                $path = 'marketplace/account/dashboard';
            } else {
                $path = 'customer/account';
            }
        } else {
            $path = 'customer/account/login';
        }
        return $result->setPath(
            $path,
            ['_secure' => $subject->getRequest()->isSecure()]
        );
    }
}
