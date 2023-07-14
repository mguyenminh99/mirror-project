<?php
namespace XShoppingSt\Marketplace\Model\Rewrite\Backend\View\Result;

class Redirect extends \Magento\Backend\Model\View\Result\Redirect
{
    /**
     * @var string
     */
    public $_path;

    /**
     * Set Path
     *
     * @param string $path
     * @param array $params
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function setPath($path, array $params = [])
    {
        $this->_path = $path;
        return parent::setPath($path, $params);
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }
}
