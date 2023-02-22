<?php

namespace Mpx\MpMassUpload\Plugin;

use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;
use Webkul\MpMassUpload\Controller\ProfileListing\ProfileList;

class AfterProfileList
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $_url;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_session;

    /**
     * @var \Webkul\MpMassUpload\Helper\Data
     */
    protected $_massUploadHelper;

    /**
     * @var \Webkul\Marketplace\Helper\Data
     */
    protected $marketplaceHelper;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @param PageFactory $resultPageFactory
     * @param \Magento\Customer\Model\Url $url
     * @param \Magento\Customer\Model\Session $session
     * @param \Webkul\MpMassUpload\Helper\Data $massUploadHelper
     * @param \Webkul\Marketplace\Helper\Data $marketplaceHelper
     * @param \Webkul\MpMassUpload\Api\ProfileRepositoryInterface $ProfileRepository
     * @param FileFactory $fileFactory
     * @param RedirectFactory $redirectFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\Url $url,
        \Magento\Customer\Model\Session $session,
        \Webkul\MpMassUpload\Helper\Data $massUploadHelper,
        \Webkul\Marketplace\Helper\Data $marketplaceHelper,
        \Webkul\MpMassUpload\Api\ProfileRepositoryInterface $ProfileRepository,
        FileFactory $fileFactory,
        RedirectFactory            $redirectFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_url = $url;
        $this->_session = $session;
        $this->_massUploadHelper = $massUploadHelper;
        $this->_marketplaceHelper = $marketplaceHelper;
        $this->fileFactory = $fileFactory;
        $this->_profileRepository = $ProfileRepository;
        $this->resultRedirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;

    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function afterExecute(ProfileList $subject, $result)
    {
        if ($this->_massUploadHelper->isSeller()
            && !empty($subject->getRequest()->getParam('profile_list'))) {
            $path = 'mpmassupload/product/view';
        } else {
            $path = 'marketplace/account/becomeseller';
        }
        return $result->setPath(
            $path,
            ['_secure' => $subject->getRequest()->isSecure()]
        );
    }
}
