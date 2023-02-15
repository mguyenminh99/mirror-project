<?php
namespace Mpx\MpMassUpload\Plugin;

use Mpx\MpMassUpload\Model\Import;
use Webkul\MpMassUpload\Helper\Data;

class BeforeValidateFields
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $import;

    /**
     * @param Import $import
     */
    public function __construct(
        Import $import
    ) {
        $this->import = $import;
    }

    /**
     * @inheritdoc
     */
    public function beforeValidateFields(Data $subject, $data, $profileType, $row): array
    {
        $data = $this->import->replaceJapArrKeyToSavingFormat($data);
        return [$data,$profileType,$row];
    }
}
