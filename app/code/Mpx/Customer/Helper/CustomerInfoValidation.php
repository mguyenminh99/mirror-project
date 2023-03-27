<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Customer
 * @author    Mpx
 */

namespace Mpx\Customer\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Message\ManagerInterface;
use Mpx\Customer\Helper\Constant;

/**
 * Helper Validate Postcode
 */
class CustomerInfoValidation extends AbstractHelper
{

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param ManagerInterface $messageManager
     * @param Context $context
     */
    public function __construct(
        ManagerInterface       $messageManager,
        Context                $context
    ) {
        $this->messageManager = $messageManager;
        parent::__construct($context);
    }

    /**
     * Set error messages to message manager
     *
     * @param array $errors
     * @return void
     */
    public function setErrorMessageToMessageManager(array $errors): void
    {
        if (!empty($errors)) {
            foreach ($errors as $error) {
                if (isset($error['message'])) {
                    $this->messageManager
                        ->addErrorMessage(__($error['message']));
                }
            }
        }
    }

    /**
     * Validate Post Code
     *
     * @param $postCode
     * @return array
     */
    public function validatePostCode($postCode)
    {
        if (!preg_match('/^[0-9]{3}-?[0-9]{4}$/', $postCode)) {
            $this->errors[] = [
                'type' => Constant::INVALID_POST_CODE,
                'message' => Constant::INVALID_POST_CODE_ERROR_MESSAGE
            ];
        }

        return $this->errors;
    }

    /**
     * Validate Phone Number
     *
     * @param $phoneNumber
     * @return array
     */
    public function validatePhoneNumber($phoneNumber)
    {
        if (!preg_match('/^[0-9\-]*$/', $phoneNumber)) {
            $this->errors[] = [
                'type' => Constant::INVALID_PHONE_NUMBER,
                'message' => Constant::INVALID_PHONE_NUMBER_ERROR_MESSAGE
            ];
        }

        return $this->errors;
    }
}
