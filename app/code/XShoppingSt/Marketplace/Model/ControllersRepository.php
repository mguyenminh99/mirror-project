<?php
namespace XShoppingSt\Marketplace\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use XShoppingSt\Marketplace\Api\Data\ControllersInterface;
use XShoppingSt\Marketplace\Model\ResourceModel\Controllers\CollectionFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class ControllersRepository implements \XShoppingSt\Marketplace\Api\ControllersRepositoryInterface
{
    /**
     * @var ControllersFactory
     */
    protected $_controllersFactory;

    /**
     * @var Controllers[]
     */
    protected $_instancesById = [];

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param ControllersFactory    $controllersFactory
     * @param CollectionFactory     $collectionFactory
     */
    public function __construct(
        ControllersFactory $controllersFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->_controllersFactory = $controllersFactory;
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($controllersId)
    {
        $controllersData = $this->_controllersFactory->create();
        /* @var \XShoppingSt\Marketplace\Model\ResourceModel\Controllers\Collection $controllersData */
        $controllersData->load($controllersId);
        if (!$controllersData->getId()) {
            // seller controller does not exist
            //throw new NoSuchEntityException(__('Requested controller doesn\'t exist'));
            $this->_instancesById[$controllersId] = $controllersData;
        }
        $this->_instancesById[$controllersId] = $controllersData;

        return $this->_instancesById[$controllersId];
    }

    /**
     * {@inheritdoc}
     */
    public function getByModuleName($moduleName = null)
    {
        $controllersCollection = $this->_collectionFactory->create()
                ->addFieldToFilter('module_name', $moduleName);
        $controllersCollection->load();

        return $controllersCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function getByPath($controllerPath = null)
    {
        $controllersCollection = $this->_collectionFactory->create()
                ->addFieldToFilter('controller_path', $controllerPath);
        $controllersCollection->load();

        return $controllersCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function getList()
    {
        /** @var \XShoppingSt\Marketplace\Model\ResourceModel\Controllers\Collection $collection */
        $collection = $this->_collectionFactory->create();
        $collection->load();

        return $collection;
    }
}
