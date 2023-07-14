<?php
namespace Mpx\MpMassUpload\Controller\Product;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;

class Upload extends \XShoppingSt\MpMassUpload\Controller\Product\Upload
{
    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $_url;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_session;

    /**
     * @var \XShoppingSt\MpMassUpload\Helper\Data
     */
    protected $_massUploadHelper;

    public function __construct(
        Context $context,
        \Magento\Customer\Model\Url $url,
        \Magento\Customer\Model\Session $session,
        \XShoppingSt\MpMassUpload\Helper\Data $massUploadHelper)
    {
        parent::__construct($context, $url, $session, $massUploadHelper);
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $helper = $this->_massUploadHelper;
        $validateData = $this->validateUploadedFiles();
        if (!$validateData[0]['error']) {
            $productType = $validateData[0]['type'];
            $fileName = $validateData[0]['csv'];
            $fileData = $validateData[0]['csv_data'];
            $result = $helper->saveProfileData(
                $productType,
                $fileName,
                $fileData,
                $validateData[0]['extension']
            );
            $uploadCsv = $helper->uploadCsv($result, $validateData[0]['extension'], $fileName);
            if ($uploadCsv['error']) {
                $this->messageManager->addError(__($uploadCsv['msg']));
                return $this->resultRedirectFactory->create()->setPath('mpmassupload/product/view/');
            }
            if (empty($validateData[1])) {
                $uploadZip = $helper->uploadZip($result, $fileData);
                if ($uploadZip['error']) {
                    $this->messageManager->addError(__($uploadZip['msg']));
                    return $this->resultRedirectFactory->create()->setPath('mpmassupload/product/view/');
                }
            }
            $isDownloadableAllowed = $helper->isProductTypeAllowed('downloadable');
            if ($productType == 'downloadable' && $isDownloadableAllowed) {
                $uploadLinks = $helper->uploadLinks($result, $fileData);
                if ($uploadLinks['error']) {
                    $this->messageManager->addError(__($uploadLinks['msg']));
                    return $this->resultRedirectFactory->create()->setPath('mpmassupload/product/view/');
                }
                if ($this->getRequest()->getParam('is_link_samples')) {
                    $uploadLinkSamples = $helper->uploadLinkSamples($result, $fileData);
                    if ($uploadLinkSamples['error']) {
                        $this->messageManager->addError(__($uploadLinkSamples['msg']));
                        return $this->resultRedirectFactory->create()->setPath('mpmassupload/product/view/');
                    }
                }
                if ($this->getRequest()->getParam('is_samples')) {
                    $uploadSamples = $helper->uploadSamples($result, $fileData);
                    if ($uploadSamples['error']) {
                        $this->messageManager->addError(__($uploadSamples['msg']));
                        return $this->resultRedirectFactory->create()->setPath('mpmassupload/product/view/');
                    }
                }
            }
            return $this->resultRedirectFactory->create()->setPath('mpmassupload/product/profile/id/' . $result['id']);
        } else {
            return $this->resultRedirectFactory->create()->setPath('mpmassupload/product/view');
        }
    }
}
