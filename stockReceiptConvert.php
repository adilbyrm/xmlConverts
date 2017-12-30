<?php
if (!isset($argv[1]) || !isset($argv[2])) {
    exit('parametre eksik');
}

$xmlFilePath = $argv[1]; // like -> velesbit/trademarks.xml
$outputFilePath = $argv[2]; // like -> velesbit/trademarks/

function xmlEscape($string) {
    return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
}

function getGUID(){
    if ( function_exists('com_create_guid') ) {
        return com_create_guid();
    } else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}

$xmls = new SimpleXMLElement($xmlFilePath, null, true);
$xml = '';
$totalAmount = 0;
$receiptNo = '2';

foreach($xmls as $s) {
	if ($s->getName() == "StockReceiptsDetail" && $s->ReceiptNo == $receiptNo) {
		if ($s->Amount < 0) continue;
		$xml .= "<StockReceiptStocks>\n";
		$xml .= "<RowID>1</RowID>\n";
		$xml .= "<RowAddDateTime>{$s->RowAddDateTime}</RowAddDateTime>\n";
		$xml .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
		$xml .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
		$xml .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
		$xml .= "<ID>{$receiptNo}</ID>\n";
		$xml .= "<ReceiptID>{$receiptNo}</ReceiptID>\n";
		$xml .= "<ReceiptType>2</ReceiptType>\n";
		$xml .= "<Time>{$s->RowDateTime}</Time>\n";
		$xml .= "<DepotID>1</DepotID>\n";
		$xml .= "<TargetDepotID>0</TargetDepotID>\n";
		$xml .= "<StockCode>{$s->Barcode}</StockCode>\n";
		$xml .= "<Number></Number>\n";
		$xml .= "<UnitName>Adet</UnitName>\n";
		$xml .= "<Amount>{$s->Amount}</Amount>\n";
		$xml .= "<DepotAmount>0</DepotAmount>\n";
		$xml .= "<TargetDepotAmount>0</TargetDepotAmount>\n";
		$xml .= "</StockReceiptStocks>\n";

		$totalAmount += $s->Amount;
		$RowAddDateTime = $s->RowAddDateTime;
		$RowAddUserNo = $s->RowAddUserNo;
		$RowEditDateTime = $s->RowEditDateTime;
		$RowEditUserNo = $s->RowEditUserNo;
		$RowDateTime = $s->RowDateTime;
	}
}

// $zeroCount = 10 - strlen($receiptNo);
// $receiptNo2 = str_repeat('0', $zeroCount) . $receiptNo;

$stockReceipts = "<StockReceipts>\n";
$stockReceipts .= "<RowID>1</RowID>\n";
$stockReceipts .= "<RowAddDateTime>{$RowAddDateTime}</RowAddDateTime>\n";
$stockReceipts .= "<RowAddUserNo>{$RowAddUserNo}</RowAddUserNo>\n";
$stockReceipts .= "<RowEditDateTime>{$RowEditDateTime}</RowEditDateTime>\n";
$stockReceipts .= "<RowEditUserNo>{$RowEditUserNo}</RowEditUserNo>\n";
$stockReceipts .= "<ID>{$receiptNo}</ID>\n";
$stockReceipts .= "<ReceiptNo>{$receiptNo}</ReceiptNo>\n";
$stockReceipts .= "<ReceiptType>2</ReceiptType>\n";
$stockReceipts .= "<Time>{$RowDateTime}</Time>\n";
$stockReceipts .= "<DepotID>1</DepotID>\n";
$stockReceipts .= "<DepotName>Merkez</DepotName>\n";
$stockReceipts .= "<TargetDepotID>0</TargetDepotID>\n";
$stockReceipts .= "<TotalAmount>{$totalAmount}</TotalAmount>\n";
$stockReceipts .= "<SettingID>1</SettingID>\n";
$stockReceipts .= "</StockReceipts>\n";

$output = "<StockReceipts>\n";
$output .= $stockReceipts . $xml;
$output .= "</StockReceipts>\n";

if (!is_dir($outputFilePath)) {
    mkdir($outputFilePath, 0755, true);
}

$fileName = str_replace('/', '_', $outputFilePath);

$file = fopen($outputFilePath . $fileName . $receiptNo . ".xml", "w");
fwrite($file, $output);
fclose($file);