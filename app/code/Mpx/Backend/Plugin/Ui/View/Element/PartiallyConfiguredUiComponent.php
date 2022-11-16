<?php

declare(strict_types=1);

namespace Mpx\Backend\Plugin\Ui\View\Element;

use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Ui\Component\Form\Field;

class PartiallyConfiguredUiComponent
{
    /**
     * Filter out fields without declared form element.
     *
     * @param UiComponentInterface $component
     * @param UiComponentInterface[] $children
     * @return UiComponentInterface[]
     */

    public function afterGetChildComponents(
        UiComponentInterface $component,
        array $children
    ) {
        $configuredChildren = [];
        foreach ($children as $key => $child) {
            if ($child instanceof Field && null === $child->getData('config/formElement')) {
                continue;
            }
            $configuredChildren[$key] = $child;
        }
        return $configuredChildren;
    }
}
