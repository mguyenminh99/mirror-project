<?php

namespace Mpx\Smtp\Plugin\Helper;

class IgnoreDecryptPassword
{
    /**
     * @param \Mageplaza\Smtp\Helper\Data $subject
     * @param \Closure $proceed
     * @param $storeId
     * @param $decrypt
     * @return mixed
     */
    public function aroundGetPassword(\Mageplaza\Smtp\Helper\Data $subject, \Closure $proceed, $storeId, $decrypt = true)
    {
        $decrypt = false;

        return $proceed($storeId, $decrypt);
    }
}
