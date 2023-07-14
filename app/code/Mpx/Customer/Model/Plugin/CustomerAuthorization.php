<?php
namespace Mpx\Customer\Model\Plugin;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Integration\Api\AuthorizationServiceInterface as AuthorizationService;
use XShoppingSt\Marketplace\Model\ResourceModel\Seller\CollectionFactory as SellerCollection;

/**
 *  Class CustomerAuthorization
 */
class CustomerAuthorization
{
    /**
     * @var UserContextInterface
     */
    protected $userContext;

    /**
     * @var SellerCollection
     */
    protected $sellerCollection;

    /**
     * @param UserContextInterface $userContext
     * @param SellerCollection $sellerCollection
     */
    public function __construct(
        UserContextInterface $userContext,
        SellerCollection $sellerCollection
    )
    {
        $this->userContext = $userContext;
        $this->sellerCollection = $sellerCollection;
    }

    /**
     * @param \Magento\Framework\Authorization $subject
     * @param \Closure $proceed
     * @param $resource
     * @param $privilege
     * @return mixed|true
     */
    public function aroundIsAllowed(
        \Magento\Framework\Authorization $subject,
        \Closure $proceed,
        $resource,
        $privilege = null
    )
    {
        if ($this->isAllowedLoggedInUser($resource))
        {
            return true;
        } else {
            return $proceed($resource, $privilege);
        }
    }

    /**
     * @return mixed
     */
    private function isSeller()
    {
        return $this->sellerCollection->create()
                ->addFieldToFilter('seller_id', $this->userContext->getUserId())
                ->getLastItem()
                ->getIsSeller();
    }

    /**
     * Check if resource for which access is needed has self permissions and seller login
     * @param $resource
     * @return bool
     */
    private function isAllowedLoggedInUser($resource)
    {
        return $resource == AuthorizationService::PERMISSION_SELF
            && $this->userContext->getUserId()
            && $this->userContext->getUserType() === UserContextInterface::USER_TYPE_CUSTOMER
            || ($resource === "Magento_Catalog::products" && ($this->isSeller()));
    }
}
