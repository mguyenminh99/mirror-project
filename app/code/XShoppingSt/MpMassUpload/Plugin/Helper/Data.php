<?php
namespace XShoppingSt\MpMassUpload\Plugin\Helper;

class Data
{

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_authSession;

    /**
     * @param \Magento\Backend\Model\Auth\Session $authSession
     */
    public function __construct(
        \Magento\Backend\Model\Auth\Session $authSession
    ) {
        $this->_authSession = $authSession;
    }

    /**
     * function to run to change the retun data of afterIsSeller.
     *
     * @param \XShoppingSt\Marketplace\Helper\Data $helperData
     * @param array $result
     *
     * @return bool
     */
    public function afterIsSeller(\XShoppingSt\Marketplace\Helper\Data $helperData, $result)
    {
        if (!empty($this->_authSession->getUser())) {
            $result = 1;
        }
        return $result;
    }

    /**
     * function to run to change the retun data of afterIsRightSeller.
     *
     * @param \XShoppingSt\Marketplace\Helper\Data $helperData
     * @param array $result
     *
     * @return bool
     */
    public function afterIsRightSeller(\XShoppingSt\Marketplace\Helper\Data $helperData, $result)
    {
        if (!empty($this->_authSession->getUser())) {
            $result = 1;
        }
        return $result;
    }

    /**
     * function to run to change the return data of afterIsSeller.
     *
     * @param \XShoppingSt\Marketplace\Helper\Data $helperData
     * @param array $result
     *
     * @return bool
     */
    public function afterGetControllerMappedPermissions(
        \XShoppingSt\Marketplace\Helper\Data $helperData,
        $result
    ) {
        $result['mpmassupload/product/finish'] = 'mpmassupload/product/view';
        $result['mpmassupload/product/options'] = 'mpmassupload/product/view';
        $result['mpmassupload/product/profile'] = 'mpmassupload/product/view';
        $result['mpmassupload/product/run'] = 'mpmassupload/product/view';
        $result['mpmassupload/product/upload'] = 'mpmassupload/product/view';
        $result['mpmassupload/dataflow_profile/delete'] = 'mpmassupload/dataflow/profile';
        $result['mpmassupload/dataflow_profile/edit'] = 'mpmassupload/dataflow/profile';
        $result['mpmassupload/dataflow_profile/massDelete'] = 'mpmassupload/dataflow/profile';
        $result['mpmassupload/dataflow_profile/save'] = 'mpmassupload/dataflow/profile';
        return $result;
    }
}
