<?php
namespace XShoppingSt\Marketplace\Ui\DataProvider\Product;

/**
 * Class RelatedDataProvider
 */
class RelatedDataProvider extends AbstractDataProvider
{
    /**
     * {@inheritdoc
     */
    protected function getLinkType()
    {
        return 'relation';
    }
}
