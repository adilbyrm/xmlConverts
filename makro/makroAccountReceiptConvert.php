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

// var_dump($rows); exit;

// A=>, 
// B=>kodu, 
// C=>, 
// D=>bakiye, 
// E=>,

$time = date('c');
$xml = '';
$i = 55;
foreach ($rows as $key => $row) {
    if ($key == 1) continue;
    $i += 1;
    $balance = true;

    if ($row['C'] && !empty($row['C'])) {
        $balance = str_replace([",", " "], [".", ""], $row['C']);
        $currencyNo = '1';
        $currencyCode = 'TL';
        $currencyPrice =  1;

    } elseif ($row['D'] && !empty($row['D'])) {
        $balance = str_replace([",", " "], [".", ""], $row['D']);
        $currencyNo = '2';
        $currencyCode = 'USD';
        $currencyPrice =  3.7523;

    } else {
        $balance = false;
    }

    if (!$balance) continue;

    $code = str_replace('_x000D_', "", $row['A']);
    
    $type = substr($balance, -3); // (A) or (B)
    $balance = substr($balance, 0, -3);

    

    if ($type == '(A)') {
        $receiptType = 2;
    } elseif ($type == '(B)') {
        $receiptType = 8;
    }

    $xml = "<CurrentAccountReceipts>";
        $xml .= "<RowID>{$i}</RowID>";
        $xml .= "<RowAddDateTime>{$time}</RowAddDateTime>";
        $xml .= "<RowAddUserNo>1</RowAddUserNo>";
        $xml .= "<RowEditDateTime>{$time}</RowEditDateTime>";
        $xml .= "<RowEditUserNo>0</RowEditUserNo>";
        $xml .= "<ID>{$i}</ID>";
        $xml .= "<ReceiptType>{$receiptType}</ReceiptType>";
        $xml .= "<Time>{$time}</Time>";
        $xml .= "<AccountCode>{$code}</AccountCode>";
        $xml .= "<BalanceCurrencyNo>{$currencyNo}</BalanceCurrencyNo>";
        $xml .= "<BalanceCurrencyCode>{$currencyCode}</BalanceCurrencyCode>";
        $xml .= "<BalanceCurrencyPrice>{$currencyPrice}</BalanceCurrencyPrice>";
        $xml .= "<Remainder>0</Remainder>";
        $xml .= "<CurrencyNo>{$currencyNo}</CurrencyNo>";
        $xml .= "<CurrencyCode>{$currencyCode}</CurrencyCode>";
        $xml .= "<CurrencyPrice>{$currencyPrice}</CurrencyPrice>";
        $xml .= "<TargetAccountID>0</TargetAccountID>";
        $xml .= "<TargetCurrencyNo>0</TargetCurrencyNo>";
        $xml .= "<TargetRemainder>0</TargetRemainder>";
        $xml .= "<Price>{$balance}</Price>";
        $xml .= "<CashTotalPrice>0</CashTotalPrice>";
        $xml .= "<Cash>false</Cash>";
        $xml .= "<BankTotalPrice>0</BankTotalPrice>";
        $xml .= "<Bank>false</Bank>";
        $xml .= "<TotalPrice>0</TotalPrice>";
        $xml .= "<RemainingPrice>{$balance}</RemainingPrice>";
        $xml .= "<BalanceNetTotalPrice>{$balance}</BalanceNetTotalPrice>";
        $xml .= "<Status>1</Status>";
        $xml .= "<Explanation></Explanation>";
    $xml .= "</CurrentAccountReceipts>";

    $output = "<CurrentAccountReceipts>\n";
    $output .= $xml;
    $output .= "</CurrentAccountReceipts>\n";

    if (!is_dir($outputFilePath)) {
        mkdir($outputFilePath, 0755, true);
    }

    $fileName = str_replace('/', '_', $outputFilePath);

    $file = fopen($outputFilePath . $fileName . $row['A'] . ".xml", "w");
    fwrite($file, $output);
    fclose($file);
}
