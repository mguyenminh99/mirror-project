<?php

namespace Mpx\CustomizeCMS\Model;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\PageFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Config\Composer\Package;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Framework\Setup\SampleData\FixtureManager;
use Magento\Setup\Module\Dependency\Parser\Composer\Json as Json;
use Magento\Framework\Filesystem\Driver\File as File;

class Page
{
    /**
     * @var PageRepositoryInterface
     */
    protected $pageRepositoryInterface;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var FixtureManager
     */
    private $fixtureManager;

    /**
     * @var Json
     */
    protected $jsonReader;
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var File
     */
    protected $fileGetContents;

    /**
     * @param PageRepositoryInterface $pageRepositoryInterface
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param File $fileGetContents
     * @param Json $jsonReader
     * @param SampleDataContext $sampleDataContext
     * @param PageFactory $pageFactory
     */
    public function __construct(
        PageRepositoryInterface $pageRepositoryInterface,
        SearchCriteriaBuilder   $searchCriteriaBuilder,
        File                    $fileGetContents,
        Json                    $jsonReader,
        SampleDataContext       $sampleDataContext,
        PageFactory             $pageFactory
    ) {
        $this->pageRepositoryInterface = $pageRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->fileGetContents = $fileGetContents;
        $this->jsonReader = $jsonReader;
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->pageFactory = $pageFactory;
    }

    /**
     * Get Module Composer Package
     *
     * @param File $file
     * @return Package
     * @throws FileSystemException
     */
    public function getModuleComposerPackage($file)
    {
        return new Package(json_decode($this->fileGetContents->fileGetContents($file)));
    }

    /**
     * Check Exist Cms Page
     *
     * @param string $urlKey
     * @return Boolean
     * @throws LocalizedException
     */
    public function isExistingCmsPage($urlKey)
    {
        if (!empty($urlKey)) {
            $searchCriteria = $this->searchCriteriaBuilder->addFilter('identifier', $urlKey, 'eq')->create();
            $pages = $this->pageRepositoryInterface->getList($searchCriteria)->getItems();
            return (count($pages) > 0);
        }
        return false;
    }

    /**
     * Install Data Cms Page
     *
     * @param array $fixtures
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function install(array $fixtures)
    {
        foreach ($fixtures as $fileName) {
            $fileName = $this->fixtureManager->getFixture($fileName);

            $convertFileName = $this->getModuleComposerPackage($fileName);
            $data = json_decode($convertFileName->getJson(), true);

            if ($this->isExistingCmsPage($data['identifier'])) {
                continue;
            }
            $contentPath = $data['content'];
            $contentPage = $this->fileGetContents->fileGetContents($contentPath);
            $replaceContent = ['content' => $contentPage];
            $data = array_replace($data, $replaceContent);

            $this->pageFactory->create()
                ->load($data['identifier'], 'identifier')
                ->addData($data)
                ->setStores([\Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->save();
        }
    }
}
