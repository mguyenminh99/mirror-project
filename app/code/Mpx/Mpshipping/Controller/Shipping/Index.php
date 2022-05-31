<?php
/**
 * Mpx Software
 *
 * @category  Mpx
 * @package   Mpx_Mpshipping
 * @author    Mpx
 */
namespace Mpx\Mpshipping\Controller\Shipping;

/**
 * Mpx Mpshipping import CSV
 */
class Index extends \Webkul\Mpshipping\Controller\Shipping\Index
{

    const COUNTRY_CODE = "JP";
    const REGION_ID = "*";
    const WEIGHT_FROM = "0";
    const WEIGHT_TO = "999";
    const NUMERIC_ZIPCODE = "yes";
    const ALPHANUMERIC_ZIPCODE = "";

    /**
     * Save Shipping rate.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if ($this->getRequest()->isPost()) {
            try {
                if (!$this->_formKeyValidator->validate($this->getRequest())) {
                    return $this->resultRedirectFactory->create()->setPath(
                        '*/*/view',
                        ['_secure' => $this->getRequest()->isSecure()]
                    );
                }
                $uploader = $this->_fileUploader->create(
                    ['fileId' => 'shippingfile']
                );
                $rows = [];
                $result = $uploader->validateFile();
                $wholedata = [];
                $file = $result['tmp_name'];
                $fileNameArray = explode('.', $result['name']);
                $ext = end($fileNameArray);
                $status = true;
                $totalSaved = 0;
                $totalUpdated = 0;
                $headerArray = ['zip','zip_to','price', 'shipping_method'];
                if ($file != '' && $ext == 'csv') {
                    $csvFileData = $this->_csvReader->getData($file);
                    $count = 0;
                    foreach ($csvFileData as $key => $row) {
                        if ($count==0) {
                            $this->getCsvFileData($row, $count, $headerArray);
                            $count++;
                            $data = $row;
                        } else {
                            $wholedata = $this->getForeachData($row, $data);
                            $wholedata['country_code'] = self::COUNTRY_CODE;
                            $wholedata['region_id'] = self::REGION_ID;
                            $wholedata['weight_from'] = self::WEIGHT_FROM;
                            $wholedata['weight_to'] = self::WEIGHT_TO;
                            $wholedata['numeric_zipcode'] = self::NUMERIC_ZIPCODE;
                            $wholedata['alphanumeric_zipcode'] = self::ALPHANUMERIC_ZIPCODE;
                            $partnerid = $this->_mpshippingHelperData->getPartnerId();
                            list($updatedWholedata, $errors) = $this->validateCsvDataToSave($wholedata);
                            $rowSaved = $this->getUpdateWholeData(
                                $errors,
                                $updatedWholedata,
                                $totalSaved,
                                $totalUpdated,
                                $partnerid
                            );
                            $totalSaved = $rowSaved[0];
                            $totalUpdated = $rowSaved[1];
                        }
                    }
                    $this->getCount($rows, $count, $totalSaved, $totalUpdated);

                } else {
                    $this->messageManager->addError(__('Please upload Csv file'));
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $this->resultRedirectFactory->create()->setPath('mpshipping/shipping/view');
            }
        }
        return $this->resultRedirectFactory->create()->setPath('mpshipping/shipping/view');
    }

    /**
     * Get Csv File Data
     *
     * @param $rowData          [$rowData description]
     * @param $count            [$count description]
     * @param $headerArray      [$headerArray description]
     */
    public function getCsvFileData($row, $count, $headerArray)
    {
        if (count($row) < 4) {
            $this->messageManager->addError(__('CSV file is not a valid file!'));
            return $this->resultRedirectFactory
                ->create()->setPath(
                    'mpshipping/shipping/view',
                    ['_secure'=>true]
                );
        } else {
            $status =($headerArray === $row);
            if (!$status) {
                $this->messageManager->addError(__('Please write the correct header formation of CSV file!'));
                return $this->resultRedirectFactory
                    ->create()->setPath(
                        'mpshipping/shipping/view',
                        ['_secure'=>true]
                    );
            }
        }
    }
}
