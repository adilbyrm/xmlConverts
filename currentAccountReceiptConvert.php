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

$stocks = new SimpleXMLElement($xmlFilePath, null, true);

$inNames = [];

$i=0;
foreach($stocks as $s) {
	if ($s->getName() == "CurrentReceipts"){
		if ($s->ReceiptType != '0' && $s->ReceiptType != '1') {
			continue;
		}

		$i++;

		if ($s->CurrencyNo == '0') {
			$currencyNo = '1';
			$currencyCode = 'TL';
			$currencyPrice = '1';
		} elseif ($s->CurrencyNo == '1') {
			$currencyNo = '2';
			$currencyCode = 'USD';
			$currencyPrice = 3.7433;
		} elseif ($s->CurrencyNo == '12') {
			$currencyNo = '5';
			$currencyCode = 'EUR';
			$currencyPrice = 4.0199;
		} else {
			$currencyNo = '1';
			$currencyCode = 'TL';
			$currencyPrice = '1';
		}

		$receiptType = 0;
		if ($s->ReceiptType == 2) {
			$receiptType = 0;
		} elseif ($s->ReceiptType == 3) {
			$receiptType = 1;
		} elseif ($s->ReceiptType == 4) {
			$receiptType = 4;
		} elseif ($s->ReceiptType == 5) {
			$receiptType = 5;
		}

		$xml = "<CurrentAccountReceipts>";
		    $xml .= "<RowID>{$s->RowID}</RowID>";
		    $xml .= "<RowAddDateTime>{$s->RowAddDateTime}</RowAddDateTime>";
		    $xml .= "<RowAddUserNo>1</RowAddUserNo>";
		    $xml .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>";
		    $xml .= "<RowEditUserNo>0</RowEditUserNo>";
		    $xml .= "<ID>{$i}</ID>";
		    $xml .= "<ReceiptNo>0000000001</ReceiptNo>";
		    $xml .= "<ReceiptType>{$receiptType}</ReceiptType>";
		    $xml .= "<Time>{$s->RowDateTime}</Time>";
		    $xml .= "<AccountCode>{$s->CurrentNo}</AccountCode>";
		    // $xml .= "<BalanceCurrencyNo>2</BalanceCurrencyNo>";
		    // $xml .= "<BalanceCurrencyCode>USD</BalanceCurrencyCode>";
		    // $xml .= "<BalanceCurrencyPrice>3.7678</BalanceCurrencyPrice>";
		    $xml .= "<Remainder>0</Remainder>";
		    $xml .= "<CurrencyNo>{$currencyNo}</CurrencyNo>";
		    $xml .= "<CurrencyCode>{$currencyCode}</CurrencyCode>";
		    $xml .= "<CurrencyPrice>{$currencyPrice}</CurrencyPrice>";
		    $xml .= "<TargetAccountID>0</TargetAccountID>";
		    $xml .= "<TargetCurrencyNo>0</TargetCurrencyNo>";
		    $xml .= "<TargetRemainder>0</TargetRemainder>";
		    $xml .= "<Price>{$s->Price}</Price>";
		    $xml .= "<CashTotalPrice>100</CashTotalPrice>";
		    $xml .= "<BankTotalPrice>0</BankTotalPrice>";
		    $xml .= "<TotalPrice>{$s->NetPrice}</TotalPrice>";
		    $xml .= "<RemainingPrice>0</RemainingPrice>";
		    $xml .= "<BalanceNetTotalPrice>100</BalanceNetTotalPrice>";
		    $xml .= "<Status>1</Status>";
		    $xml .= "<Explanation>".xmlEscape($s->Message)."</Explanation>";
		$xml .= "</CurrentAccountReceipts>";

		$xml .= "<CurrentAccountReceiptCashPayments>";
		    $xml .= "<RowID>{$s->RowID}</RowID>";
		    $xml .= "<RowAddDateTime>{$s->RowAddDateTime}</RowAddDateTime>";
		    $xml .= "<RowAddUserNo>1</RowAddUserNo>";
		    $xml .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>";
		    $xml .= "<RowEditUserNo>0</RowEditUserNo>";
		    $xml .= "<ReceiptID>{$i}</ReceiptID>";
		    $xml .= "<ReceiptType>{$receiptType}</ReceiptType>";
		    $xml .= "<ID>{$i}</ID>";
		    $xml .= "<SourceReceiptID>{$i}</SourceReceiptID>";
		    $xml .= "<CurrencyNo1>{$currencyNo}</CurrencyNo1>";
		    $xml .= "<CurrencyPrice1>{$currencyPrice}</CurrencyPrice1>";
		    $xml .= "<CurrencyNo2>{$currencyNo}</CurrencyNo2>";
		    $xml .= "<CurrencyPrice2>{$currencyPrice}</CurrencyPrice2>";
		    $xml .= "<Price>{$s->Price}</Price>";
		    $xml .= "<NetPrice>{$s->NetPrice}</NetPrice>";
		$xml .= "</CurrentAccountReceiptCashPayments>";

		$xml .= "<CaseReceipts>";
		    $xml .= "<RowID>{$s->RowID}</RowID>";
		    $xml .= "<RowAddDateTime>{$s->RowAddDateTime}</RowAddDateTime>";
		    $xml .= "<RowAddUserNo>1</RowAddUserNo>";
		    $xml .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>";
		    $xml .= "<RowEditUserNo>0</RowEditUserNo>";
		    $xml .= "<ID>{$i}</ID>";
		    $xml .= "<ReceiptNo>0000000001</ReceiptNo>";
		    $xml .= "<ReceiptType>{$receiptType}</ReceiptType>";
		    $xml .= "<CategoryID>0</CategoryID>";
		    $xml .= "<Time>{$s->RowDateTime}</Time>";
		    $xml .= "<CaseID>1</CaseID>";
		    // $xml .= "<CaseCode>0000000001</CaseCode>";
		    // $xml .= "<CaseName>İstoç</CaseName>";
		    $xml .= "<CurrencyNo>{$currencyNo}</CurrencyNo>";
		    $xml .= "<CurrencyCode>{$currencyCode}</CurrencyCode>";
		    $xml .= "<CurrencyPrice>{$currencyPrice}</CurrencyPrice>";
		    $xml .= "<Remainder>0</Remainder>";
		    $xml .= "<TargetCaseID>0</TargetCaseID>";
		    $xml .= "<TargetCurrencyNo>0</TargetCurrencyNo>";
		    $xml .= "<TargetRemainder>0</TargetRemainder>";
		    $xml .= "<Price>{$s->Price}</Price>";
		    $xml .= "<Status>1</Status>";
		    $xml .= "<Explanation>".xmlEscape($s->Message)."</Explanation>";
		    $xml .= "<SettingID>1</SettingID>";
		$xml .= "</CaseReceipts>";

		$output = "<CurrentAccountReceipts>\n";
		$output .= $xml;
		$output .= "</CurrentAccountReceipts>\n";

		if (!is_dir($outputFilePath)) {
            mkdir($outputFilePath, 0755, true);
        }

        $fileName = str_replace('/', '_', $outputFilePath);

		$file = fopen($outputFilePath . $fileName . $i . ".xml", "w");
		fwrite($file, $output);
		fclose($file);
	}

}