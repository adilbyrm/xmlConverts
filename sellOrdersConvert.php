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

$sellReceiptsMasters = [];
foreach ($xmls as $s) {
	if ($s->getName() == "SellReceiptsMaster"){

		$sellReceiptsMasters[(int)$s->ReceiptNo] = [
								'RowID' => $s->RowID,
                                'RowDateTime' => $s->RowDateTime,
                                'ReceiptNo' => $s->ReceiptNo,
                                'ReceiptDateTime' => $s->ReceiptDateTime,
                                'ReceiptType' => $s->ReceiptType,
                                'CurrentNo' => $s->CurrentNo,
                                'TotalPrice' => $s->TotalPrice,
                                'Discount' => $s->Discount,
                                'Message' => $s->Message,
                                'RowAddDateTime' => $s->RowAddDateTime,
                                'RowAddUserNo' => $s->RowAddUserNo,
                                'RowEditDateTime' => $s->RowEditDateTime,
                                'RowEditUserNo' => $s->RowEditUserNo,
                                'RowDiscount' => $s->RowDiscount,
                                'TotalDiscount' => $s->TotalDiscount,
                                'TotalPoints' => $s->TotalPoints,
                                'TotalVAT' => $s->TotalVAT,
                                'NetTotalPrice' => $s->NetTotalPrice,
                                'DocumentNo' => $s->DocumentNo,
                                'LastRemainder' => $s->LastRemainder,
                                'DocumentType' => $s->DocumentType,
                                'DocumentNo2' => $s->DocumentNo2,
                                'DocumentDateTime' => $s->DocumentDateTime,
                                'DocumentDateTime2' => $s->DocumentDateTime2,
                                'SendDateTime' => $s->SendDateTime,
                                'PersonnelNo' => $s->PersonnelNo,
                                'CurrencyNo' => $s->CurrencyNo,
                                'CurrencyPrice' => $s->CurrencyPrice,
                                'CurrentCurrencyPrice' => $s->CurrentCurrencyPrice,
                                'CurrencyTotalPrice' => $s->CurrencyTotalPrice,
                                'DepotNo' => $s->DepotNo,
                                'Message2' => $s->Message2,
                                'MaturityDate' => $s->MaturityDate,
                                'StatusType' => $s->StatusType
							];
	}
}

foreach ($xmls as $s) {
    if ($s->getName() == "SellReceiptsDetail"){

        $sellReceiptsMasters[(int)$s->ReceiptNo]['SellReceiptsDetails'][] = [
                                        'RowID' => $s->RowID,
                                        'RowDateTime' => $s->RowDateTime,
                                        'ReceiptNo' => $s->ReceiptNo,
                                        'OrderNo' => $s->OrderNo,
                                        'Barcode' => $s->Barcode,
                                        'BuyPrice' => $s->BuyPrice,
                                        'Price' => $s->Price,
                                        'Amount' => $s->Amount,
                                        'Points' => $s->Points,
                                        'RowAddDateTime' => $s->RowAddDateTime,
                                        'RowAddUserNo' => $s->RowAddUserNo,
                                        'RowEditDateTime' => $s->RowEditDateTime,
                                        'RowEditUserNo' => $s->RowEditUserNo,
                                        'VAT' => $s->VAT,
                                        'DiscountPerc' => $s->DiscountPerc,
                                        'DiscountPerc2' => $s->DiscountPerc2,
                                        'DiscountPerc3' => $s->DiscountPerc3,
                                        'DiscountPerc4' => $s->DiscountPerc4,
                                        'DiscountPerc5' => $s->DiscountPerc5,
                                        'Discount' => $s->Discount,
                                        'VATPrice' => $s->VATPrice,
                                        'TotalPrice' => $s->TotalPrice,
                                        'NetTotalPrice' => $s->NetTotalPrice,
                                        'SerialNo' => $s->SerialNo,
                                        'ExtraInfo' => $s->ExtraInfo,
                                        'PersonnelNo' => $s->PersonnelNo,
                                        'NetTotalDiscount' => $s->NetTotalDiscount
                                    ];
    }
}

$i = 100;
$k = 100;
foreach ($sellReceiptsMasters as $sell) {
    $i++;
    if ($sell['CurrencyNo'] == '0') {
        $currencyNo = 1;
        $currencyCode = 'TL';
        $currencyPrice = 1;
    } elseif ($sell['CurrencyNo'] == '1') {
        $currencyNo = 2;
        $currencyCode = 'USD';
        $currencyPrice = 3.8708;
    } elseif ($sell['CurrencyNo'] == '12') {
        $currencyNo = 5;
        $currencyCode = 'EUR';
        $currencyPrice = 4.1412;
    } else {
        $currencyNo = 1;
        $currencyCode = 'TL';
        $currencyPrice = 1;
    }
    
    $detailReceipt = '';
    $totalAmount = 0;
    
    foreach ($sell['SellReceiptsDetails'] as $v) {
        $k++;
        $totalAmount += $v['Amount'];

        $price = $v['Price'] == 0 ? 1 : $v['Price'];
        $totalP = $v['TotalPrice'] == 0 ? 1 : $v['TotalPrice'];
        $netTotalP = $v['NetTotalPrice'] == 0 ? 1 : $v['NetTotalPrice'];

        $detailReceipt .= "<ReceiptStocks>\n\r";
        $detailReceipt .= "<RowID>{$v['RowID']}</RowID>\n\r";
        $detailReceipt .= "<RowAddDateTime>{$v['RowAddDateTime']}</RowAddDateTime>\n\r";
        $detailReceipt .= "<RowAddUserNo>1</RowAddUserNo>\n\r";
        $detailReceipt .= "<RowEditDateTime>{$v['RowEditDateTime']}</RowEditDateTime>\n\r";
        $detailReceipt .= "<RowEditUserNo>1</RowEditUserNo>\n\r";
        $detailReceipt .= "<ReceiptDirection>1</ReceiptDirection>\n\r";
        $detailReceipt .= "<ReceiptType>4</ReceiptType>\n\r";
        $detailReceipt .= "<ReceiptID>{$i}</ReceiptID>\n\r";
        $detailReceipt .= "<Time>{$v['RowDateTime']}</Time>\n\r";
        $detailReceipt .= "<ReceiptCurrencyNo>{$currencyNo}</ReceiptCurrencyNo>\n\r";
        $detailReceipt .= "<ReceiptCurrencyPrice>{$currencyPrice}</ReceiptCurrencyPrice>\n\r";
        $detailReceipt .= "<ID>{$k}</ID>\n\r";
        $detailReceipt .= "<StockCode>{$v['Barcode']}</StockCode>\n\r";
        $detailReceipt .= "<Number></Number>\n\r";
        $detailReceipt .= "<UnitName>Adet</UnitName>\n\r";
        $detailReceipt .= "<Amount>{$v['Amount']}</Amount>\n\r";
        $detailReceipt .= "<Price>{$price}</Price>\n\r";
        $detailReceipt .= "<TotalPrice>{$totalP}</TotalPrice>\n\r";
        $detailReceipt .= "<CurrencyNo>{$currencyNo}</CurrencyNo>\n\r";
        $detailReceipt .= "<CurrencyCode>{$currencyCode}</CurrencyCode>\n\r";
        $detailReceipt .= "<CurrencyPrice>{$currencyPrice}</CurrencyPrice>\n\r";
        $detailReceipt .= "<Discount>{$v['Discount']}</Discount>\n\r";
        $detailReceipt .= "<DiscountedPrice>" . ($price - $v['Discount']) . "</DiscountedPrice>\n\r";
        $detailReceipt .= "<DiscountRatio>{$v['DiscountPerc']}</DiscountRatio>\n\r";
        $detailReceipt .= "<DiscountRatio2>{$v['DiscountPerc2']}</DiscountRatio2>\n\r";
        $detailReceipt .= "<DiscountRatio3>{$v['DiscountPerc3']}</DiscountRatio3>\n\r";
        $detailReceipt .= "<TotalDiscount>{$v['NetTotalDiscount']}</TotalDiscount>\n\r";
        $detailReceipt .= "<VAT>{$v['VAT']}</VAT>\n\r";
        $detailReceipt .= "<VATStatus>0</VATStatus>\n\r";
        $detailReceipt .= "<VATPrice>{$v['VATPrice']}</VATPrice>\n\r";
        $detailReceipt .= "<TotalVATPrice>" . ($v['VATPrice'] * $v['Amount']) . "</TotalVATPrice>\n\r";
        $detailReceipt .= "<WithoutVATPrice>" . ($price - $v['VATPrice']) . "</WithoutVATPrice>\n\r";
        $detailReceipt .= "<TotalWithoutVATPrice>" . ($totalP - ($v['VATPrice'] * $v['Amount'])) . "</TotalWithoutVATPrice>\n\r";
        $detailReceipt .= "<NetPrice>{$price}</NetPrice>\n\r";
        $detailReceipt .= "<NetTotalPrice>{$netTotalP}</NetTotalPrice>\n\r";
        $detailReceipt .= "<CurrencyTotalWithoutVATPrice>" . ($totalP * $currencyPrice) . "</CurrencyTotalWithoutVATPrice>\n\r";
        $detailReceipt .= "<CurrencyTotalDiscount>0</CurrencyTotalDiscount>\n\r";
        $detailReceipt .= "<CurrencyTotalVATPrice>0</CurrencyTotalVATPrice>\n\r";
        $detailReceipt .= "<DepotID>1</DepotID>\n\r";
        $detailReceipt .= "<DepotName>merkez</DepotName>\n\r";
        $detailReceipt .= "<DepotAmount>0</DepotAmount>\n\r";
        $detailReceipt .= "<EmployeeID>1</EmployeeID>\n\r";
        $detailReceipt .= "<Explanation></Explanation>\n\r";
        $detailReceipt .= "<Status>0</Status>\n\r";
        $detailReceipt .= "</ReceiptStocks>\n\r";
    }

    $masterReceipt = "<Receipts>\n\r";
    $masterReceipt .= "<RowID>{$sell['RowID']}</RowID>\n\r";
    $masterReceipt .= "<RowAddDateTime>{$sell['RowAddDateTime']}</RowAddDateTime>\n\r";
    $masterReceipt .= "<RowAddUserNo>1</RowAddUserNo>\n\r";
    $masterReceipt .= "<RowEditDateTime>{$sell['RowEditDateTime']}</RowEditDateTime>\n\r";
    $masterReceipt .= "<RowEditUserNo>1</RowEditUserNo>\n\r";
    $masterReceipt .= "<ReceiptDirection>1</ReceiptDirection>\n\r";
    $masterReceipt .= "<ReceiptType>4</ReceiptType>\n\r";
    $masterReceipt .= "<ID>{$i}</ID>\n\r";
    $masterReceipt .= "<ReceiptNo>{$sell['ReceiptNo']}</ReceiptNo>\n\r";
    $masterReceipt .= "<Time>{$sell['ReceiptDateTime']}</Time>\n\r";
    $masterReceipt .= "<DepotID>1</DepotID>\n\r"; //
    $masterReceipt .= "<DepotName>merkez</DepotName>\n\r";
    $masterReceipt .= "<EmployeeID>1</EmployeeID>\n\r";
    $masterReceipt .= "<AccountCode>{$sell['CurrentNo']}</AccountCode>\n\r";
    $masterReceipt .= "<Remainder>0</Remainder>\n\r";
    $masterReceipt .= "<CurrencyNo>{$currencyNo}</CurrencyNo>\n\r";
    $masterReceipt .= "<CurrencyCode>{$currencyCode}</CurrencyCode>\n\r";
    $masterReceipt .= "<CurrencyPrice>{$currencyPrice}</CurrencyPrice>\n\r";
    $masterReceipt .= "<TotalAmount>{$totalAmount}</TotalAmount>\n\r";
    $masterReceipt .= "<TotalPrice>{$sell['TotalPrice']}</TotalPrice>\n\r";
    $masterReceipt .= "<RowDiscount>{$sell['RowDiscount']}</RowDiscount>\n\r";
    $masterReceipt .= "<DiscountRatio>0</DiscountRatio>\n\r";
    $masterReceipt .= "<Discount>{$sell['Discount']}</Discount>\n\r";
    $masterReceipt .= "<DiscountedPrice>" . ($sell['TotalPrice'] - $sell['Discount']) . "</DiscountedPrice>\n\r";
    $masterReceipt .= "<TotalDiscount>{$sell['TotalDiscount']}</TotalDiscount>\n\r";
    $masterReceipt .= "<TotalDiscountedPrice>" . ($sell['TotalPrice'] - $sell['TotalDiscount']) . "</TotalDiscountedPrice>\n\r";
    $masterReceipt .= "<RowTotalVATPrice>0</RowTotalVATPrice>\n\r";
    $masterReceipt .= "<TotalVATPrice>{$sell['TotalVAT']}</TotalVATPrice>\n\r";
    $masterReceipt .= "<NetTotalPrice>{$sell['NetTotalPrice']}</NetTotalPrice>\n\r";
    $masterReceipt .= "<AccountNetTotalPrice>0</AccountNetTotalPrice>\n\r";
    $masterReceipt .= "<AccountingAccountNetTotalPrice>0</AccountingAccountNetTotalPrice>\n\r";
    $masterReceipt .= "<Status>0</Status>\n\r";
    $masterReceipt .= "<Explanation>{$sell['Message']}</Explanation>\n\r";
    $masterReceipt .= "<AccountingAccountID>0</AccountingAccountID>\n\r";
    $masterReceipt .= "<VatAccountingAccountID>0</VatAccountingAccountID>\n\r";
    $masterReceipt .= "<AccountAccountingAccountID>0</AccountAccountingAccountID>\n\r";
    $masterReceipt .= "<AccountBuyersAccountID>0</AccountBuyersAccountID>\n\r";
    $masterReceipt .= "<AccountSellersAccountID>0</AccountSellersAccountID>\n\r";
    $masterReceipt .= "<BuyersAccountID>0</BuyersAccountID>\n\r";
    $masterReceipt .= "<SellersAccountID>0</SellersAccountID>\n\r";
    $masterReceipt .= "<BuysAccountID>0</BuysAccountID>\n\r";
    $masterReceipt .= "<SellsAccountID>0</SellsAccountID>\n\r";
    $masterReceipt .= "<BuysVatAccountID>0</BuysVatAccountID>\n\r";
    $masterReceipt .= "<SellsVatAccountID>0</SellsVatAccountID>\n\r";
    $masterReceipt .= "<SettingID>0</SettingID>\n\r";
    $masterReceipt .= "</Receipts>\n\r";

    $output = "<SellOrders>\n\r";
    $output .= $masterReceipt . $detailReceipt;
    $output .= "</SellOrders>\n\r";

    if (!is_dir($outputFilePath)) {
        mkdir($outputFilePath, 0755, true);
    }

    $fileName = str_replace('/', '_', $outputFilePath);

    $file = fopen($outputFilePath . $fileName . $sell['ReceiptNo'] . ".xml", "w");
    fwrite($file, $output);
    fclose($file);

}

