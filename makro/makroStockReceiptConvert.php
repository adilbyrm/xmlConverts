<?php
if (!isset($argv[1]) || !isset($argv[2])) {
    exit('parametre eksik');
}

$xmlFilePath = $argv[1]; // like -> velesbit/trademarks.xml
$outputFilePath = $argv[2]; // like -> velesbit/trademarks/

ini_set('display_errors', 'on');
set_time_limit(2400);
require_once 'PHPExcel/IOFactory.php';
$objPHPExcel = PHPExcel_IOFactory::load($xmlFilePath);
$rows = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

function xmlEscape($string) {
    return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
}

// A=>, 
// B=>kodu, 
// C=>adı, 
// D=>adet, 
// E=>birim,

$time = date('c');
$xml = '';
$x = 0;
foreach ($rows as $key => $row) {
    if ($key == 1 || $key == 2) continue;
    $x += 1;

    if ($row['D'] == 'MERKEZ EMINONU') {
        $depotID = 1;
    } else if ($row['D'] == 'DEPO') {
        $depotID = 2;
    } else if ($row['D'] == '34UH7102') {
        $depotID = 3;
    }

    if ($row['C'] == 'ADET') {
        $unitID = 1;
        $unitName = 'Adet';
    } else if ($row['C'] == 'PK') {
        $unitID = 3;
        $unitName = 'Paket';
    }

    $code = str_replace('_x000D_', "", $row['A']);
    $amount = $row['E'];
    $xml = "<StockReceiptStocks>\n";
    $xml .= "<RowID>1</RowID>\n";
    $xml .= "<RowAddDateTime>{$time}</RowAddDateTime>\n";
    $xml .= "<RowAddUserNo>1</RowAddUserNo>\n";
    $xml .= "<RowEditDateTime>{$time}</RowEditDateTime>\n";
    $xml .= "<RowEditUserNo>0</RowEditUserNo>\n";
    $xml .= "<ID_>{$x}</ID_>\n";
    $xml .= "<ReceiptID>{$x}</ReceiptID>\n";
    $xml .= "<ReceiptType>0</ReceiptType>\n";
    $xml .= "<Time>{$time}</Time>\n";
    $xml .= "<DepotID>{$depotID}</DepotID>\n";
    $xml .= "<TargetDepotID>0</TargetDepotID>\n";
    $xml .= "<StockCode>".$code."</StockCode>\n";
    $xml .= "<Number></Number>\n";
    $xml .= "<UnitID>{$unitID}</UnitID>\n";
    $xml .= "<UnitName>{$unitName}</UnitName>\n";
    $xml .= "<Amount>".$amount."</Amount>\n";
    $xml .= "<DepotAmount>0</DepotAmount>\n";
    $xml .= "<TargetDepotAmount>0</TargetDepotAmount>\n";
    $xml .= "<Status>1</Status>\n";
    $xml .= "</StockReceiptStocks>\n";

    $stockReceipts = "<StockReceipts>\n";
    $stockReceipts .= "<RowID>1</RowID>\n";
    $stockReceipts .= "<RowAddDateTime>{$time}</RowAddDateTime>\n";
    $stockReceipts .= "<RowAddUserNo>1</RowAddUserNo>\n";
    $stockReceipts .= "<RowEditDateTime>{$time}</RowEditDateTime>\n";
    $stockReceipts .= "<RowEditUserNo>0</RowEditUserNo>\n";
    $stockReceipts .= "<ID>{$x}</ID>\n";
    $stockReceipts .= "<ReceiptType>0</ReceiptType>\n";
    $stockReceipts .= "<Time>{$time}</Time>\n";
    $stockReceipts .= "<DepotID>{$depotID}</DepotID>\n";
    $stockReceipts .= "<TargetDepotID>0</TargetDepotID>\n";
    $stockReceipts .= "<TotalAmount>".$amount."</TotalAmount>\n";
    $stockReceipts .= "<SettingID>1</SettingID>\n";
    $stockReceipts .= "<Status>1</Status>\n";
    $stockReceipts .= "</StockReceipts>\n";

    $output = "<StockReceipts>\n";
    $output .= $stockReceipts . $xml;
    $output .= "</StockReceipts>\n";

    if (!is_dir($outputFilePath)) {
        mkdir($outputFilePath, 0755, true);
    }

    $fileName = str_replace('/', '_', $outputFilePath);

    $file = fopen($outputFilePath . $fileName . '_' .  $x . ".xml", "w");
    fwrite($file, $output);
    fclose($file);
}
