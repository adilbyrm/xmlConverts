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

// var_dump($rows);

// A=>Kodu, 
// B=>Açıklama/isim, 
// C=>, 
// D=>, 
// E=>alis fiyati $,
// F=>,
// G=>satis fiyati tl,
// H=>barkod

$time = date('c');

$i = 0;
foreach ($rows as $key => $row) {
	if ($key == 1) continue;

	$i++;

	$buyPrice1 = ltrim($row['E'], '$');
	$sellPrice1 = rtrim($row['G'], ' TL');
	$code = str_replace('_x000D_', "", $row['A']);

	$stockCard = "<StockCards>\n";
	$stockCard .= "<RowID>{$i}</RowID>\n";
	$stockCard .= "<RowAddDateTime>{$time}</RowAddDateTime>\n";
	$stockCard .= "<RowAddUserNo>1</RowAddUserNo>\n";
	$stockCard .= "<RowEditDateTime>{$time}</RowEditDateTime>\n";
	$stockCard .= "<RowEditUserNo>0</RowEditUserNo>\n";
	$stockCard .= "<ID>{$i}</ID>\n";
	$stockCard .= "<Code>".$code."</Code>\n";
	$stockCard .= "<Name>" . xmlEscape($row['B']) . "</Name>\n";
	$stockCard .= "<Name2>" . xmlEscape($row['B']) . "</Name2>\n";
	$stockCard .= "<SpecialCode>". xmlEscape($row['B']) ."</SpecialCode>\n";
	$stockCard .= "<CardType>0</CardType>\n";
	$stockCard .= "<TrackingType>0</TrackingType>\n";
	$stockCard .= "<GroupID>0</GroupID>\n";
	$stockCard .= "<TrademarkID>0</TrademarkID>\n";
	$stockCard .= "<ProductID>0</ProductID>\n";
	$stockCard .= "<ModelID>0</ModelID>\n";
	$stockCard .= "<UnitID>1</UnitID>\n";
	$stockCard .= "<UnitName>Adet</UnitName>\n";
	$stockCard .= "<SizeID>0</SizeID>\n";
	$stockCard .= "<SeasonID>0</SeasonID>\n";
	$stockCard .= "<SectionID>0</SectionID>\n";
	$stockCard .= "<ColorID>0</ColorID>\n";
	$stockCard .= "<Width>0</Width>\n";
	$stockCard .= "<Height>0</Height>\n";
	$stockCard .= "<Length>0</Length>\n";
	$stockCard .= "<Weight>0</Weight>\n";
	$stockCard .= "<VAT>0</VAT>\n";
	$stockCard .= "<BuyPriceID>1</BuyPriceID>\n";
	$stockCard .= "<BuyPrice>".$buyPrice1."</BuyPrice>\n";
	$stockCard .= "<BuyCurrencyCode>USD</BuyCurrencyCode>\n";
	$stockCard .= "<BuyVATStatus>0</BuyVATStatus>\n";
	$stockCard .= "<SellPriceID>1</SellPriceID>\n";
	$stockCard .= "<SellPrice>".$sellPrice1."</SellPrice>\n";
	$stockCard .= "<SellCurrencyCode>TL</SellCurrencyCode>\n";
	$stockCard .= "<SellVATStatus>0</SellVATStatus>\n";
	$stockCard .= "<DefaultProcessAmount>1</DefaultProcessAmount>\n";
	$stockCard .= "<Ponderable>false</Ponderable>\n";
	$stockCard .= "<IncludingScoring>false</IncludingScoring>\n";
	$stockCard .= "<Score>0</Score>\n";
	$stockCard .= "<PackageAmount>0</PackageAmount>\n";
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
	$stockCard .= "<Status>0</Status>\n";
	$stockCard .= "<Property1>{$time}</Property1>\n";
	$stockCard .= "<Property2>{$time}</Property2>\n";
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
	$stockCard .= "<Property20></Property20>\n";
	$stockCard .= "<Property21></Property21>\n";
	$stockCard .= "<Property22></Property22>\n";
	$stockCard .= "<Property23></Property23>\n";
	$stockCard .= "<Property24></Property24>\n";
	$stockCard .= "<Property25></Property25>\n";
	$stockCard .= "<Property26></Property26>\n";
	$stockCard .= "<Property27></Property27>\n";
	$stockCard .= "<Property28></Property28>\n";
	$stockCard .= "<Property29></Property29>\n";
	$stockCard .= "<SettingID>0</SettingID>\n";
	$stockCard .= "</StockCards>\n";
	
	if ((int) $row['H'] > 0) {
		$stockCard .= "<StockCardBarcodes>";
		$stockCard .= "<RowID>{$i}</RowID>\n";
		$stockCard .= "<RowAddDateTime>{$time}</RowAddDateTime>\n";
		$stockCard .= "<RowAddUserNo>1</RowAddUserNo>\n";
		$stockCard .= "<RowEditDateTime>{$time}</RowEditDateTime>\n";
		$stockCard .= "<RowEditUserNo>0</RowEditUserNo>\n";
		$stockCard .= "<StockID>{$i}</StockID>\n";
		$stockCard .= "<ID>{$i}</ID>\n";
		$stockCard .= "<SeqID>{$i}</SeqID>\n";
		$stockCard .= "<Name>Ürün</Name>\n";
		$stockCard .= "<Type>4</Type>\n";
		$stockCard .= "<Barcode>".$row['H']."</Barcode>\n";
		$stockCard .= "<Amount>1</Amount>\n";
		$stockCard .= "</StockCardBarcodes>";
	}

	$buyPrice = "<StockCardBuyPrices>\n";
	$buyPrice .= "<RowID>{$i}</RowID>\n";
	$buyPrice .= "<RowAddDateTime>{$time}</RowAddDateTime>\n";
	$buyPrice .= "<RowAddUserNo>1</RowAddUserNo>\n";
	$buyPrice .= "<RowEditDateTime>{$time}</RowEditDateTime>\n";
	$buyPrice .= "<RowEditUserNo>0</RowEditUserNo>\n";
	$buyPrice .= "<StockID>{$i}</StockID>\n";
	$buyPrice .= "<ID>{$i}</ID>\n";
	$buyPrice .= "<SeqID>{$i}</SeqID>\n";
	$buyPrice .= "<Price>" . $buyPrice1 . "</Price>\n";
	$buyPrice .= "<CurrencyNo>2</CurrencyNo>\n";
	$buyPrice .= "<CurrencyCode>USD</CurrencyCode>\n";
	$buyPrice .= "<CurrencyName>ABD DOLARI</CurrencyName>\n";
	$buyPrice .= "<VATStatus>0</VATStatus>\n";
	$buyPrice .= "<Status>0</Status>\n";
	$buyPrice .= "<SettingID>0</SettingID>\n";
	$buyPrice .= "</StockCardBuyPrices>\n";

	$sellPrice = "<StockCardSellPrices>\n";
	$sellPrice .= "<RowID>{$i}</RowID>\n";
	$sellPrice .= "<RowAddDateTime>{$time}</RowAddDateTime>\n";
	$sellPrice .= "<RowAddUserNo>1</RowAddUserNo>\n";
	$sellPrice .= "<RowEditDateTime>{$time}</RowEditDateTime>\n";
	$sellPrice .= "<RowEditUserNo>0</RowEditUserNo>\n";
	$sellPrice .= "<StockID>{$i}</StockID>\n";
	$sellPrice .= "<ID>{$i}</ID>\n";
	$sellPrice .= "<SeqID>{$i}</SeqID>\n";
	$sellPrice .= "<Price>" . $sellPrice1 . "</Price>\n";
	$sellPrice .= "<CurrencyNo>1</CurrencyNo>\n";
	$sellPrice .= "<CurrencyCode>TL</CurrencyCode>\n";
	$sellPrice .= "<CurrencyName>TÜRK LİRASI</CurrencyName>\n";
	$sellPrice .= "<VATStatus>0</VATStatus>\n";
	$sellPrice .= "<Status>0</Status>\n";
	$sellPrice .= "<SettingID>0</SettingID>\n";
	$sellPrice .= "</StockCardSellPrices>\n";

	$output = "<StockCards>\n";
	$output .= $stockCard . $buyPrice . $sellPrice;
	$output .= "</StockCards>\n";

	if (!is_dir($outputFilePath)) {
        mkdir($outputFilePath, 0755, true);
    }

    $fileName = str_replace('/', '_', $outputFilePath);

	$file = fopen($outputFilePath . $fileName . $i . ".xml", "w");
	fwrite($file, $output);
	fclose($file);
	
}
