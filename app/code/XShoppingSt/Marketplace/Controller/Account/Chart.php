<?php
namespace XShoppingSt\Marketplace\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;

/**
 * XShoppingSt Marketplace Chart Controller.
 */
class Chart extends \Magento\Customer\Controller\AbstractAccount
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \XShoppingSt\Marketplace\Block\Account\Dashboard\Diagrams
     */
    protected $diagrams;

    /**
     * @var \XShoppingSt\Marketplace\Block\Account\Dashboard\LocationChart
     */
    protected $locationChart;

    /**
     * @var \XShoppingSt\Marketplace\Block\Account\Dashboard\CategoryChart
     */
    protected $categoryChart;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;
    /**
     * @param Context                                                     $context
     * @param Session                                                     $customerSession
     * @param \XShoppingSt\Marketplace\Block\Account\Dashboard\Diagrams        $diagrams
     * @param \XShoppingSt\Marketplace\Block\Account\Dashboard\LocationChart   $locationChart
     * @param \XShoppingSt\Marketplace\Block\Account\Dashboard\CategoryChart   $categoryChart
     * @param \Magento\Framework\Json\Helper\Data                         $jsonHelper
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        \XShoppingSt\Marketplace\Block\Account\Dashboard\Diagrams $diagrams,
        \XShoppingSt\Marketplace\Block\Account\Dashboard\LocationChart $locationChart,
        \XShoppingSt\Marketplace\Block\Account\Dashboard\CategoryChart $categoryChart,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ) {
        $this->_customerSession = $customerSession;
        $this->diagrams = $diagrams;
        $this->locationChart = $locationChart;
        $this->categoryChart = $categoryChart;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context);
    }

    /**
     * Ask Query to seller action.
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $chartUrl = '';
        if ($data['chartType'] == 'diagram') {
            $chartUrl = $this->diagrams->getSellerStatisticsGraphUrl($data['dateType']);
        } elseif ($data['chartType'] == 'location') {
            $chartUrl = $this->locationChart->getSellerStatisticsGraphUrl($data['dateType']);
        } elseif ($data['chartType'] == 'category') {
            $chartUrl = $this->categoryChart->getSellerStatisticsGraphUrl($data['dateType']);
        }
        $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($chartUrl)
        );
    }
}
