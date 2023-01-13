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
                'Mpx_Customer/js/validation-mixins/edit-customer-validation': true,
                'Mpx_Customer/js/validation-postcode/custom-validate-postcode': true,
                'Mpx_Customer/js/validation-telephone/custom-validate-telephone': true
            },
        }
    },
}
