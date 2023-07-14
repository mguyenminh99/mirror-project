<?php
namespace Mpx\MpMassUpload\Plugin;

use Magento\Catalog\Model\ProductFactory;
use XShoppingSt\MpMassUpload\Helper\Data;

class AfterExistingProductDataMapping
{
    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @param ProductFactory $productFactory
     */
    public function __construct(
        ProductFactory $productFactory
    ) {
        $this->productFactory = $productFactory;
    }

    /**
     * @inheritdoc
     */
    public function afterExistingProductDataMapping(Data $subject, $data): array
    {
        $taxClassId = $this->productFactory->create()->load($data['product_id'])->getTaxClassId();
        if ($taxClassId) {
            $data['product']['tax_class_id'] = $taxClassId;
        }
        return $data;
    }
}
