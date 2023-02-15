<?php
namespace Mpx\MpMassUpload\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Patch is mechanism, that allows to do atomic upgrade data changes
 */
class MoveMediaFiles implements
    DataPatchInterface
{
    /**
     * @var Reader
     */
    protected $_reader;

    /**
     * @var Reader
     */
    protected $_filesystem;

    /**
     * @var Reader
     */
    protected $_fileDriver;

    /**
     * @param Reader $reader
     * @param Filesystem $filesSystem
     * @param File $fileDriver
     */
    public function __construct(
        Reader $reader,
        Filesystem $filesSystem,
        File $fileDriver
    ) {
        $this->_reader = $reader;
        $this->_filesystem = $filesSystem;
        $this->_fileDriver = $fileDriver;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        $this->moveDirToMediaDir();
    }

    /**
     * Copy Sample CSV,XML and XSL files to Media
     */
    private function moveDirToMediaDir()
    {
        try {
            $type = \Magento\Framework\App\Filesystem\DirectoryList::MEDIA;
            $smpleFilePath = $this->_filesystem->getDirectoryRead($type)
                            ->getAbsolutePath().'marketplace/massupload/samples/';
            $files = [
                'product_import_jp.csv'
            ];
            if ($this->_fileDriver->isExists($smpleFilePath)) {
                $this->_fileDriver->deleteDirectory($smpleFilePath);
            }
            if (!$this->_fileDriver->isExists($smpleFilePath)) {
                $this->_fileDriver->createDirectory($smpleFilePath, 0777);
            }
            foreach ($files as $file) {
                $filePath = $smpleFilePath.$file;
                if (!$this->_fileDriver->isExists($filePath)) {
                    $path = '/pub/media/marketplace/massupload/samples/'.$file;
                    $mediaFile = $this->_reader->getModuleDir('', 'Mpx_MpMassUpload').$path;
                    if ($this->_fileDriver->isExists($mediaFile)) {
                        $this->_fileDriver->copy($mediaFile, $filePath);
                    }
                }
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [

        ];
    }
}
