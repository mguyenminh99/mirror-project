<?php
namespace XShoppingSt\MpApi\Model;

class Response extends \Magento\Framework\DataObject implements \XShoppingSt\MpApi\Api\Data\ResponseInterface
{

    /**
     * prepare api response .
     *
     * @return \XShoppingSt\MpApi\Api\Data\ResponseInterface
     */
    public function getResponse()
    {
        $data = $this->_data;
        return $data;
    }
}
