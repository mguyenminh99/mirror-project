<?php
namespace XShoppingSt\Marketplace\Model\ResourceModel;

class WysiwygImage extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
     /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('wk_mp_wysiwyg_image', 'entity_id');
    }

}
