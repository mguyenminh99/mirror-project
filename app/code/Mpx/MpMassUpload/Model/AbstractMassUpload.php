<?php
namespace Mpx\MpMassUpload\Model;

abstract class AbstractMassUpload
{
    /**
     * @inheritdoc
     */
    abstract protected function replaceJapArrKeyToSavingFormat($data);
}
