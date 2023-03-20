<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Catalog
 * @author    Mpx
 */

namespace Mpx\Catalog\Plugin\Catalog;

use Mpx\Marketplace\Helper\Constant;

/**
 * Disabled Root Category.
 */
class DisableDefaultCategory
{

    /**
     * @var \Magento\Framework\App\RequestInterface
     * @since 101.0.0
     */
    protected $request;

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * Plugin After Disabled Default Category
     *
     * @param DataProvider $subject
     * @param array $result
     * @return array
     */
    public function afterPrepareMeta(\Magento\Catalog\Model\Category\DataProvider $subject, array $result)
    {
        foreach ($result as $fieldSet => $fields) {
            foreach ($fields as $field) {
                foreach ($field as $key => $data) {
                    if ($this->request->getParam('id') == Constant::DEFAULT_CATEGORY
                        && isset($result[$fieldSet]['children'])) {
                        $result[$fieldSet]['children'][$key]['arguments']['data']['config']['required'] = 1;
                        $result[$fieldSet]['children'][$key]['arguments']['data']['config']['disabled'] = 1;
                        $result[$fieldSet]['children'][$key]['arguments']['data']['config']['serviceDisabled'] = true;
                    }
                }
            }
        }

        return  $result;
    }
}
