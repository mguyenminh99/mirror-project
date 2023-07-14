<?php
namespace XShoppingSt\Marketplace\Model\Plugin\Customer;

/**
 * Marketplace Customer Session Plugin.
 */
class Session
{
    /**
     * @var \XShoppingSt\Marketplace\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @var \XShoppingSt\Marketplace\Model\ControllersRepository
     */
    private $controllersRepository;

    /**
     * @param \XShoppingSt\Marketplace\Helper\Data $helper
     * @param \XShoppingSt\Marketplace\Model\ControllersRepository $controllersRepository
     */
    public function __construct(
        \XShoppingSt\Marketplace\Helper\Data $helper,
        \Magento\Framework\UrlInterface $urlBuilder,
        \XShoppingSt\Marketplace\Model\ControllersRepository $controllersRepository
    ) {
        $this->_helper = $helper;
        $this->_urlBuilder = $urlBuilder;
        $this->controllersRepository = $controllersRepository;
    }

    /**
     * Insert title and number for concrete document type.
     *
     * @param string $url
     */
    public function beforeAuthenticate(
        \Magento\Customer\Model\Session $session,
        $url = null
    ) {
        if ($this->_helper->getIsSeparatePanel()) {
            $controllerMappedPaths = $this->_helper->getControllerMappedPermissions();
            $currentUrl = $this->_urlBuilder->getCurrentUrl();
            foreach ($controllerMappedPaths as $key => $value) {
                if (strpos($currentUrl, $key) !== false) {
                    $url = $this->_urlBuilder->getUrl("marketplace/account/login");
                }
            }
            if ($url !== null) {
                foreach ($this->controllersRepository->getList() as $coll) {
                    if (gettype($coll['controller_path']) === "string" && strpos($currentUrl, $coll['controller_path']) !== false) {
                        $url = $this->_urlBuilder->getUrl("marketplace/account/login");
                    }
                }
            }
        }
        return [$url];
    }
}
