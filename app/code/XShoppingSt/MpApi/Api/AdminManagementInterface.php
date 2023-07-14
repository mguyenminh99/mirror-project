<?php
namespace XShoppingSt\MpApi\Api;

interface AdminManagementInterface
{
    /**
     * depricated
     * get seller details.
     *
     * @api
     *
     * @return XShoppingSt\MpApi\Api\Data\ResponseInterface
     */
    public function getSellerList();

    /**
     * Interface for specific seller details.
     *
     * @api
     *
     * @param int $id seller id
     *
     * @return XShoppingSt\MpApi\Api\Data\ResponseInterface
     */
    public function getSeller($id);

    /**
     * get seller products.
     *
     * @api
     *
     * @param int $id seller id
     *
     * @return XShoppingSt\MpApi\Api\Data\ResponseInterface
     */
    public function getSellerProducts($id);

    /**
     * Interface for seller orders.
     *
     * @api
     *
     * @param int $id seller id
     *
     * @return XShoppingSt\MpApi\Api\Data\ResponseInterface
     */
    public function getSellerOrders($id);

    /**
     * Interface for order details.
     *
     * @api
     *
     * @param int $id seller id
     *
     * @return XShoppingSt\MpApi\Api\Data\ResponseInterface
     */
    public function getSellerSalesDetails($id);

    /**
     * Interface for paying the seller.
     *
     * @api
     *
     * @param int $id seller id
     *
     * @return XShoppingSt\MpApi\Api\Data\ResponseInterface
     */
    public function payToSeller($id);

    /**
     * Interface for assign product(s) to the seller.
     *
     * @api
     *
     * @param int $sellerId seller id
     *
     * @return XShoppingSt\MpApi\Api\Data\ResponseInterface
     */
    public function assignProduct($sellerId);

    /**
     * Interface for assign product(s) to the seller.
     *
     * @api
     *
     * @param int $sellerId seller id
     *
     * @return XShoppingSt\MpApi\Api\Data\ResponseInterface
     */
    public function unassignProduct($sellerId);
}
