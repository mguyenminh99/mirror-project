<?php
namespace XShoppingSt\Marketplace\Ui\DataProvider\Product;

/**
 * Class CrossSellDataProvider
 */
class CrossSellDataProvider extends AbstractDataProvider
{
    /**
     * {@inheritdoc
     */
    protected function getLinkType()
    {
        return 'cross_sell';
    }
}
