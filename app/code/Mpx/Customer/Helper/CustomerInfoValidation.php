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

/**
 * Helper Validate Postcode
 */
class CustomerInfoValidation extends AbstractHelper
{
    private const INVALID_POST_CODE = "invalid_format";
    private const INVALID_POST_CODE_ERROR_MESSAGE = "Postal code is not correct.";
    private const INVALID_PHONE_NUMBER = "invalid_format";
    private const INVALID_PHONE_NUMBER_ERROR_MESSAGE =
        "Phone number is not correct. The characters that can be used are numbers and hyphens.";

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
                'type' => self::INVALID_POST_CODE,
                'message' => self::INVALID_POST_CODE_ERROR_MESSAGE
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
                'type' => self::INVALID_PHONE_NUMBER,
                'message' => self::INVALID_PHONE_NUMBER_ERROR_MESSAGE
            ];
        }

        return $this->errors;
    }
}
