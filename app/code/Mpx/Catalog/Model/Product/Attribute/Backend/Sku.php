<?php

namespace Mpx\Catalog\Model\Product\Attribute\Backend;

use Magento\Catalog\Model\Product;

/**
 * Catalog product SKU backend attribute model.
 */
class Sku extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{

    /**
     * Generate and set unique SKU to product
     *
     * @param Product $object
     * @return void
     */
    protected function _generateUniqueSku($object)
    {
        $attribute = $this->getAttribute();
        $entity = $attribute->getEntity();
        while (!$entity->checkAttributeUniqueValue($attribute, $object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('SKU Already Exist')
            );
        }
    }

    /**
     * Make SKU unique before save
     *
     * @param Product $object
     * @return $this
     */
    public function beforeSave($object)
    {
        $this->_generateUniqueSku($object);
        $this->trimValue($object);
        return parent::beforeSave($object);
    }

    /**
     * Remove extra spaces from attribute value before save.
     *
     * @param Product $object
     * @return void
     */
    private function trimValue($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        $value = $object->getData($attrCode);
        if ($value) {
            $object->setData($attrCode, trim($value));
        }
    }
}
