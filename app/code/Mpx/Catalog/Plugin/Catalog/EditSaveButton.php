<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Catalog
 * @author    Mpx
 */

namespace Mpx\Catalog\Plugin\Catalog;

use Magento\Catalog\Block\Adminhtml\Category\Edit\SaveButton;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Store\Model\ScopeInterface;
use Mpx\Marketplace\Helper\Constant;

/**
 * Plugin After Disabled Default Category for Button Save.
 */
class EditSaveButton
{

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Construct
     *
     * @param Http $request
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Http $request,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->request = $request;
    }

    /**
     * Plugin after get button save data
     *
     * @param Save $subject
     * @param array $result
     * @return array
     */
    public function afterGetButtonData(SaveButton $subject, array $result): array
    {
        if ($this->request->getParam('id') == Constant::DEFAULT_CATEGORY) {
            $result['disabled'] = true;
            return $result;
        }
        return $result;
    }
}
