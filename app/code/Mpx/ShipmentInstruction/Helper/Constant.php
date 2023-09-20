<?php

namespace Mpx\ShipmentInstruction\Helper;

class Constant
{
    const ROW_EDIT_URL = 'shipmentinstruction/shipmentinstruction/edit';
    const SELLER_STATUS_OPENNING = 1; // 1 : ストアオープン中
    const YAMATO_TRANSPORT_CARRIER_CODE = 'ヤマト運輸';
    const SHIPMENT_PAGE_KEY = 'shipment_page';
    const CSV_EXPORT_ID_KEY = 'csv_export_id';
    const SHIPMENT_INSTRUCTION_GRID_CODE = 1; //1: param set in session to check shipment instruction grid
    const EXPORTED_SHIPMENT_INSTRUCTION_GRID_CODE = 2; //2: param set in session to check exported shipment instruction gird
    const UN_EXPORTED_SHIPMENT_INSTRUCTION_GRID_CODE = 3; //3: param set in session to check un exported shipment instruction gird
}
