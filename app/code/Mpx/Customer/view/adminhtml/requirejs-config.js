/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Customer
 * @author    Mpx
 */

var config = {
    config: {
        mixins: {
            'mage/validation': {
                'Mpx_Customer/js/validation-mixins/edit-customer-validation': true
            },
        }
    },
    map: {
        "*": {
            'Magento_Customer/js/form/components/insert-form': 'Mpx_Customer/js/form/components/insert-form'
        }
    }
}
