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
	if ($s->getName() == "StockCards" && $s->Active == "true"){
		
		$stockName = (string)$s->StockName;
		if (in_array($stockName, $inNames)) {
			$count = count(array_filter($inNames, function ($n) use ($stockName) { return $n == $stockName; }));
			$stockName = $s->StockName . '(' . $count . ')';
		}
		$inNames[] = (string)$s->StockName;

		$i++;
		$status = $s->Active == "true" ? "0" : "1";

		if ($s->CurrencyNo == '0') {
			$currencyNo = '1';
			$currencyCode = 'TL';
		} elseif ($s->CurrencyNo == '1') {
			$currencyNo = '2';
			$currencyCode = 'USD';
		} elseif ($s->CurrencyNo == '12') {
			$currencyNo = '5';
			$currencyCode = 'EUR';
		} else {
			$currencyNo = '1';
			$currencyCode = 'TL';
		}

		if ($s->BuyPrice > 0) {
			$stockBuyPrice = $s->BuyPrice;
			$buyPriceCurrencyCode = $currencyCode;
			$buyPriceID = "1";
		} else {
			$stockBuyPrice = "999";
			$buyPriceCurrencyCode = "TL";
			$buyPriceID = "1";
		}

		if ($s->SellPrice > 0) {
			$stockSellPrice = $s->SellPrice;
			$sellPriceCurrencyCode = $currencyCode;
			$sellPriceID = "1";
		} else {
			$stockSellPrice = "999";
			$sellPriceCurrencyCode = "TL";
			$sellPriceID = "1";
		}

		$stockCard = "<StockCards>\n";
		$stockCard .= "<RowID>{$i}</RowID>\n";
		$stockCard .= "<RowAddDateTime>{$s->RowEditDateTime}</RowAddDateTime>\n";
		$stockCard .= "<RowAddUserNo>1</RowAddUserNo>\n";
		$stockCard .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
		$stockCard .= "<RowEditUserNo>1</RowEditUserNo>\n";
		$stockCard .= "<ID>{$i}</ID>\n";
		$stockCard .= "<Code>".substr($s->Barcode, 1)."</Code>\n";
		$stockCard .= "<Name>" . xmlEscape($stockName) . "</Name>\n";
		$stockCard .= "<Name2>" . xmlEscape($s->StockName2) . "</Name2>\n";
		$stockCard .= "<SpecialCode>{$s->SpecialCode}</SpecialCode>\n";
		$stockCard .= "<CardType>0</CardType>\n";
		$stockCard .= "<TrackingType>0</TrackingType>\n";
		$stockCard .= "<GroupID>{$s->GroupNo}</GroupID>\n";
		$stockCard .= "<TrademarkID>{$s->TrademarkNo}</TrademarkID>\n";
		$stockCard .= "<TrademarkName></TrademarkName>\n";
		$stockCard .= "<ProductID>0</ProductID>\n";
		$stockCard .= "<ModelID>0</ModelID>\n";
		$stockCard .= "<UnitID>1</UnitID>\n";
		$stockCard .= "<SizeID>{$s->SizeNo}</SizeID>\n";
		$stockCard .= "<UnitName>Adet</UnitName>\n";
		$stockCard .= "<SeasonID>0</SeasonID>\n";
		$stockCard .= "<SectionID>0</SectionID>\n";
		$stockCard .= "<ColorID>{$s->ColorNo}</ColorID>\n";
		$stockCard .= "<Width>0</Width>\n";
		$stockCard .= "<Height>0</Height>\n";
		$stockCard .= "<Length>0</Length>\n";
		$stockCard .= "<Weight>0</Weight>\n";
		$stockCard .= "<VAT>{$s->SellVAT}</VAT>\n";
		$stockCard .= "<BuyPriceID>{$buyPriceID}</BuyPriceID>\n";
		$stockCard .= "<BuyPrice>{$stockBuyPrice}</BuyPrice>\n";
		$stockCard .= "<BuyCurrencyCode>{$buyPriceCurrencyCode}</BuyCurrencyCode>\n";
		$stockCard .= "<BuyVATStatus>0</BuyVATStatus>\n";
		$stockCard .= "<SellPriceID>{$sellPriceID}</SellPriceID>\n";
		$stockCard .= "<SellPrice>{$stockSellPrice}</SellPrice>\n";
		$stockCard .= "<SellCurrencyCode>{$sellPriceCurrencyCode}</SellCurrencyCode>\n";
		$stockCard .= "<SellVATStatus>0</SellVATStatus>\n";
		$stockCard .= "<DefaultProcessAmount>1</DefaultProcessAmount>\n";
		$stockCard .= "<Ponderable>{$s->Scale}</Ponderable>\n";
		$stockCard .= "<IncludingScoring>false</IncludingScoring>\n";
		$stockCard .= "<Score>0</Score>\n";
		$stockCard .= "<PackageAmount>{$s->PackageAmount}</PackageAmount>\n";
		$stockCard .= "<InputAmount>0</InputAmount>\n";
		$stockCard .= "<OutputAmount>0</OutputAmount>\n";
		$stockCard .= "<Amount>0</Amount>\n";
		$stockCard .= "<Explanation></Explanation>\n";
		$stockCard .= "<Picture></Picture>\n";
		$stockCard .= "<VideoWebAddressID>0</VideoWebAddressID>\n";
		$stockCard .= "<PresentationWebAddressID>0</PresentationWebAddressID>\n";
		$stockCard .= "<B2C>false</B2C>\n";
		$stockCard .= "<B2B>false</B2B>\n";
		$stockCard .= "<Web>false</Web>\n";
		$stockCard .= "<HotSelling>false</HotSelling>\n";
		$stockCard .= "<MobileSelling>false</MobileSelling>\n";
		$stockCard .= "<Status>{$status}</Status>\n";
		$stockCard .= "<Property1>{$s->Property1}</Property1>\n";
		$stockCard .= "<Property2>{$s->Property1}</Property2>\n";
		$stockCard .= "<Property3>false</Property3>\n";
		$stockCard .= "<Property4>false</Property4>\n";
		$stockCard .= "<Property5>false</Property5>\n";
		$stockCard .= "<Property6>false</Property6>\n";
		$stockCard .= "<Property7>false</Property7>\n";
		$stockCard .= "<Property8>false</Property8>\n";
		$stockCard .= "<Property9>false</Property9>\n";
		$stockCard .= "<Property10>false</Property10>\n";
		$stockCard .= "<Property11>0</Property11>\n";
		$stockCard .= "<Property12>0</Property12>\n";
		$stockCard .= "<Property13>0</Property13>\n";
		$stockCard .= "<Property14>0</Property14>\n";
		$stockCard .= "<Property15>0</Property15>\n";
		$stockCard .= "<Property16>0</Property16>\n";
		$stockCard .= "<Property17>0</Property17>\n";
		$stockCard .= "<Property18>0</Property18>\n";
		$stockCard .= "<Property19>0</Property19>\n";
		$stockCard .= "<Property20>" . xmlEscape($s->Property6) . "</Property20>\n";
		$stockCard .= "<Property21>" . xmlEscape($s->Property7) . "</Property21>\n";
		$stockCard .= "<Property22>" . xmlEscape($s->Property8) . "</Property22>\n";
		$stockCard .= "<Property23>" . xmlEscape($s->Property9) . "</Property23>\n";
		$stockCard .= "<Property24>" . xmlEscape($s->Property10) . "</Property24>\n";
		$stockCard .= "<Property25>" . xmlEscape($s->Properties) . "</Property25>\n";
		$stockCard .= "<Property26></Property26>\n";
		$stockCard .= "<Property27></Property27>\n";
		$stockCard .= "<Property28></Property28>\n";
		$stockCard .= "<Property29></Property29>\n";
		$stockCard .= "<SettingID>0</SettingID>\n";
		$stockCard .= "<_SynchronizationID_>" . getGUID() . "</_SynchronizationID_>\n";
		$stockCard .= "</StockCards>\n";
		
		$barcodeType = $s->BarcodeType == '0' ? '12' : '4';
		$stockCard .= "<StockCardBarcodes>";
		$stockCard .= "<RowID>{$i}</RowID>\n";
		$stockCard .= "<RowAddDateTime>{$s->RowEditDateTime}</RowAddDateTime>\n";
		$stockCard .= "<RowAddUserNo>1</RowAddUserNo>\n";
		$stockCard .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
		$stockCard .= "<RowEditUserNo>1</RowEditUserNo>\n";
		$stockCard .= "<StockID>{$i}</StockID>\n";
		$stockCard .= "<ID>{$i}</ID>\n";
		$stockCard .= "<SeqID>{$i}</SeqID>\n";
		$stockCard .= "<Name>Ürün</Name>\n";
		$stockCard .= "<Type>{$barcodeType}</Type>\n";
		$stockCard .= "<Barcode>".$s->Barcode."</Barcode>\n";
		$stockCard .= "<Amount>1</Amount>\n";
		$stockCard .= "</StockCardBarcodes>";

		$buyPrice = '';
		for ($k=1; $k<=3; $k++) {
			$price1 = ($k==1 ? $s->BuyPrice : ($k==2 ? $s->BuyPrice2 : $s->BuyPrice3));
			$currency1 = ($k==1 ? $s->CurrencyNo : ($k==2 ? $s->CurrencyNo2 : $s->CurrencyNo3));

			if ($currency1 == '0') {
				$currencyNo1 = '1';
			} elseif ($currency1 == '1') {
				$currencyNo1 = '2';
			} elseif ($currency1 == '12') {
				$currencyNo1 = '5';
			} else {
				$currencyNo1 = '1';
			}

			if ($currencyNo1 == "2") {
				$currencyCode1 = "USD";
				$currencyName1 = "ABD DOLARI";
			} elseif ($currencyNo1 == "5") {
				$currencyCode1 = "EUR";
				$currencyName1 = "EURO";
			} elseif ($currencyNo1 == "1") {
				$currencyCode1 = "TL";
				$currencyName1 = "TÜRK LİRASI";
			} else {
				$currencyCode1 = "TL";
				$currencyName1 = "TÜRK LİRASI";
			}

			if ($price1 > 0) {
				$buyPrice .= "<StockCardBuyPrices>\n";
				$buyPrice .= "<RowID>{$i}</RowID>\n";
				$buyPrice .= "<RowAddDateTime>{$s->RowEditDateTime}</RowAddDateTime>\n";
				$buyPrice .= "<RowAddUserNo>1</RowAddUserNo>\n";
				$buyPrice .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
				$buyPrice .= "<RowEditUserNo>1</RowEditUserNo>\n";
				$buyPrice .= "<StockID>{$i}</StockID>\n";
				$buyPrice .= "<ID>{$k}</ID>\n";
				$buyPrice .= "<SeqID>{$k}</SeqID>\n";
				$buyPrice .= "<Price>" . $price1 . "</Price>\n";
				$buyPrice .= "<CurrencyNo>" . $currencyNo1 . "</CurrencyNo>\n";
				$buyPrice .= "<CurrencyCode>{$currencyCode1}</CurrencyCode>\n";
				$buyPrice .= "<CurrencyName>{$currencyName1}</CurrencyName>\n";
				$buyPrice .= "<VATStatus>0</VATStatus>\n";
				$buyPrice .= "<Status>0</Status>\n";
				$buyPrice .= "<SettingID>0</SettingID>\n";
				$buyPrice .= "<_SynchronizationID_>" . getGUID() . "</_SynchronizationID_>\n";
				$buyPrice .= "</StockCardBuyPrices>\n";
			}
		}

		if ($buyPrice == '') {
			$buyPrice .= "<StockCardBuyPrices>\n";
			$buyPrice .= "<RowID>{$i}</RowID>\n";
			$buyPrice .= "<RowAddDateTime>{$s->RowEditDateTime}</RowAddDateTime>\n";
			$buyPrice .= "<RowAddUserNo>1</RowAddUserNo>\n";
			$buyPrice .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
			$buyPrice .= "<RowEditUserNo>1</RowEditUserNo>\n";
			$buyPrice .= "<StockID>{$i}</StockID>\n";
			$buyPrice .= "<ID>{$buyPriceID}</ID>\n";
			$buyPrice .= "<SeqID>{$buyPriceID}</SeqID>\n";
			$buyPrice .= "<Price>{$stockBuyPrice}</Price>\n";
			$buyPrice .= "<CurrencyNo>1</CurrencyNo>\n";
			$buyPrice .= "<CurrencyCode>{$buyPriceCurrencyCode}</CurrencyCode>\n";
			$buyPrice .= "<CurrencyName>TÜRK LİRASI</CurrencyName>\n";
			$buyPrice .= "<VATStatus>0</VATStatus>\n";
			$buyPrice .= "<Status>0</Status>\n";
			$buyPrice .= "<SettingID>0</SettingID>\n";
			$buyPrice .= "<_SynchronizationID_>" . getGUID() . "</_SynchronizationID_>\n";
			$buyPrice .= "</StockCardBuyPrices>\n";
		}

		$sellPrice = '';
		for ($x=1; $x<=3; $x++) {
			$price2 = ($x==1 ? $s->SellPrice : ($x==2 ? $s->SellPrice2 : $s->SellPrice3));
			$currency2 = ($x==1 ? $s->CurrencyNo : ($x==2 ? $s->CurrencyNo2 : $s->CurrencyNo3));

			if ($currency2 == '0') {
				$currencyNo2 = '1';
			} elseif ($currency2 == '1') {
				$currencyNo2 = '2';
			} elseif ($currency2 == '12') {
				$currencyNo2 = '5';
			} else {
				$currencyNo2 = '1';
			}

			if ($currencyNo2 == "2") {
				$currencyCode2 = "USD";
				$currencyName2 = "ABD DOLARI";
			} elseif ($currencyNo2 == "5") {
				$currencyCode2 = "EUR";
				$currencyName2 = "EURO";
			} elseif ($currencyNo2 == "1") {
				$currencyCode2 = "TL";
				$currencyName2 = "TÜRK LİRASI";
			} else {
				$currencyCode2 = "TL";
				$currencyName2 = "TÜRK LİRASI";
			}

			if ($price2 > 0) {
				$sellPrice .= "<StockCardSellPrices>\n";
				$sellPrice .= "<RowID>{$i}</RowID>\n";
				$sellPrice .= "<RowAddDateTime>{$s->RowEditDateTime}</RowAddDateTime>\n";
				$sellPrice .= "<RowAddUserNo>1</RowAddUserNo>\n";
				$sellPrice .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
				$sellPrice .= "<RowEditUserNo>1</RowEditUserNo>\n";
				$sellPrice .= "<StockID>{$i}</StockID>\n";
				$sellPrice .= "<ID>{$x}</ID>\n";
				$sellPrice .= "<SeqID>{$x}</SeqID>\n";
				$sellPrice .= "<Price>" . $price2 . "</Price>\n";
				$sellPrice .= "<CurrencyNo>" . $currencyNo2 . "</CurrencyNo>\n";
				$sellPrice .= "<CurrencyCode>{$currencyCode2}</CurrencyCode>\n";
				$sellPrice .= "<CurrencyName>{$currencyName2}</CurrencyName>\n";
				$sellPrice .= "<VATStatus>0</VATStatus>\n";
				$sellPrice .= "<Status>0</Status>\n";
				$sellPrice .= "<SettingID>0</SettingID>\n";
				$sellPrice .= "<_SynchronizationID_>" . getGUID() . "</_SynchronizationID_>\n";
				$sellPrice .= "</StockCardSellPrices>\n";
			}
		}

		if ($sellPrice == '') {
			$sellPrice .= "<StockCardSellPrices>\n";
			$sellPrice .= "<RowID>{$i}</RowID>\n";
			$sellPrice .= "<RowAddDateTime>{$s->RowEditDateTime}</RowAddDateTime>\n";
			$sellPrice .= "<RowAddUserNo>1</RowAddUserNo>\n";
			$sellPrice .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
			$sellPrice .= "<RowEditUserNo>1</RowEditUserNo>\n";
			$sellPrice .= "<StockID>{$i}</StockID>\n";
			$sellPrice .= "<ID>{$sellPriceID}</ID>\n";
			$sellPrice .= "<SeqID>{$sellPriceID}</SeqID>\n";
			$sellPrice .= "<Price>{$stockSellPrice}</Price>\n";
			$sellPrice .= "<CurrencyNo>1</CurrencyNo>\n";
			$sellPrice .= "<CurrencyCode>{$sellPriceCurrencyCode}</CurrencyCode>\n";
			$sellPrice .= "<CurrencyName>TÜRK LİRASI</CurrencyName>\n";
			$sellPrice .= "<VATStatus>0</VATStatus>\n";
			$sellPrice .= "<Status>0</Status>\n";
			$sellPrice .= "<SettingID>0</SettingID>\n";
			$sellPrice .= "<_SynchronizationID_>" . getGUID() . "</_SynchronizationID_>\n";
			$sellPrice .= "</StockCardSellPrices>\n";
		}

		$output = "<StockCards>\n";
		$output .= $stockCard . $buyPrice . $sellPrice;
		$output .= "</StockCards>\n";

		if (!is_dir($outputFilePath)) {
            mkdir($outputFilePath, 0755, true);
        }

        $fileName = str_replace('/', '_', $outputFilePath);

		$file = fopen($outputFilePath . $fileName . $s->Barcode . ".xml", "w");
		fwrite($file, $output);
		fclose($file);
	}

}