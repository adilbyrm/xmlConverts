<?php
if (!isset($argv[1]) || !isset($argv[2])) {
    exit('parametre eksik');
}

$xmlFilePath = $argv[1]; // like -> velesbit/trademarks.xml
$outputFilePath = $argv[2]; // like -> velesbit/trademarks/

ini_set('display_errors', 'on');
require_once 'PHPExcel/IOFactory.php';
$objPHPExcel = PHPExcel_IOFactory::load($xmlFilePath);
$rows = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

function xmlEscape($string) {
    return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
}

// A=>Kodu, 
// B=>Açıklama/isim, 
// C=>şehir, 
// D=>ülke, 
// E=>Kod_2,
// F=>iş akış kodu,
// G=>TELEFON_NO,
// H=>ADRES1,
// I=>ADRES2



$i=10;
$y=5;
$z=5;
foreach($rows as $key => $row) {
	if ($key == 1) continue;
	$i++;

	$time = date('c');
	$status = "0";

	$currencyNo = '1';
	$currencyCode = 'TL';

	$currents = "<CurrentAccounts>\n";
	$currents .= "<RowID>{$i}</RowID>\n";
	$currents .= "<RowAddDateTime>{$time}</RowAddDateTime>\n";
	$currents .= "<RowAddUserNo>1</RowAddUserNo>\n";
	$currents .= "<RowEditDateTime>{$time}</RowEditDateTime>\n";
	$currents .= "<RowEditUserNo>0</RowEditUserNo>\n";
	$currents .= "<ID>{$i}</ID>\n";
	$currents .= "<Type>1</Type>\n";
	$currents .= "<Code>". $row['A'] ."</Code>\n";
	$currents .= "<Name>". xmlEscape($row['B']) ."</Name>\n";
	$currents .= "<InterestLevel>0</InterestLevel>\n";
	$currents .= "<SellerID>0</SellerID>\n";
	$currents .= "<SellerName></SellerName>\n";
	$currents .= "<GroupID>0</GroupID>\n";
	$currents .= "<SpecialCode>". xmlEscape($row['B']) ."</SpecialCode>\n";
	$currents .= "<CardCode></CardCode>\n";
	$currents .= "<DiscountRatio>0</DiscountRatio>\n";
	$currents .= "<BuyPriceIndex>1</BuyPriceIndex>\n";
	$currents .= "<SellPriceIndex>1</SellPriceIndex>\n";
	$currents .= "<DefaultBalanceCurrencyNo>{$currencyNo}</DefaultBalanceCurrencyNo>\n";
	$currents .= "<DefaultBalanceCurrencyCode>{$currencyCode}</DefaultBalanceCurrencyCode>\n";
	$currents .= "<Status>{$status}</Status>\n";
	$currents .= "<ContactID>0</ContactID>\n";
	$currents .= "<CompanyID>{$i}</CompanyID>\n";
	$currents .= "<UseCount>0</UseCount>\n";
	$currents .= "<UserName></UserName>\n";
	$currents .= "<Password></Password>\n";
	$currents .= "<BuyersAccountID>0</BuyersAccountID>\n";
	$currents .= "<SellersAccountID>0</SellersAccountID>\n";
	$currents .= "<RememberToken></RememberToken>\n";
	$currents .= "<Property1>{$time}</Property1>\n";
	$currents .= "<Property2>{$time}</Property2>\n";
	$currents .= "<Property3>false</Property3>\n";
	$currents .= "<Property4>false</Property4>\n";
	$currents .= "<Property5>false</Property5>\n";
	$currents .= "<Property6>false</Property6>\n";
	$currents .= "<Property7>0</Property7>\n";
	$currents .= "<Property8>0</Property8>\n";
	$currents .= "<Property9>0</Property9>\n";
	$currents .= "<Property10>0</Property10>\n";
	$currents .= "<Property11>0</Property11>\n";
	$currents .= "<Property12>0</Property12>\n";
	$currents .= "<Property13></Property13>\n";
	$currents .= "<Property14></Property14>\n";
	$currents .= "<Property15></Property15>\n";
	$currents .= "<Property16>".$row['E']."</Property16>\n";
	$currents .= "<Property17></Property17>\n";
	$currents .= "</CurrentAccounts>\n";

	// $balance = "<CurrentAccountBalances>\n";
	// $balance .= "<RowID>{$i}</RowID>\n";
	// $balance .= "<RowAddDateTime>{$time}</RowAddDateTime>\n";
	// $balance .= "<RowAddUserNo>1</RowAddUserNo>\n";
	// $balance .= "<RowEditDateTime>{$time}</RowEditDateTime>\n";
	// $balance .= "<RowEditUserNo>0</RowEditUserNo>\n";
	// $balance .= "<AccountID>{$i}</AccountID>\n";
	// $balance .= "<CurrencyNo>{$currencyNo}</CurrencyNo>\n";
	// $balance .= "<CurrencyCode>{$currencyCode}</CurrencyCode>\n";
	// $balance .= "<Debt>0</Debt>\n";
	// $balance .= "<Credit>0</Credit>\n";
	// $balance .= "<DebtRemainder>0</DebtRemainder>\n";
	// $balance .= "<CreditRemainder>0</CreditRemainder>\n";
	// $balance .= "<Remainder>0</Remainder>\n";
	// $balance .= "<Explanation></Explanation>\n";
	// $balance .= "<Status>0</Status>\n";
	// $balance .= "</CurrentAccountBalances>\n";

	$company = "<Companies>\n";
	$company .= "<RowID>{$i}</RowID>\n";
	$company .= "<RowAddDateTime>{$time}</RowAddDateTime>\n";
	$company .= "<RowAddUserNo>1</RowAddUserNo>\n";
	$company .= "<RowEditDateTime>{$time}</RowEditDateTime>\n";
	$company .= "<RowEditUserNo>0</RowEditUserNo>\n";
	$company .= "<ID>{$i}</ID>\n";
	$company .= "<Title>". xmlEscape($row['B']) ."</Title>\n";
	$company .= "<ShortTitle></ShortTitle>\n";
	$company .= "<InstitutionType>0</InstitutionType>\n";
	$company .= "<EmployeeCount>0</EmployeeCount>\n";
	$company .= "<LifeSpan>0</LifeSpan>\n";
	$company .= "<ParticipantCount>0</ParticipantCount>\n";
	$company .= "<TradeRegisterNumber>0</TradeRegisterNumber>\n";
	$company .= "<Scene></Scene>\n";
	$company .= "<Sector></Sector>\n";
	$company .= "<TaxNumber></TaxNumber>\n";
	$company .= "<TaxOffice></TaxOffice>\n";
	$company .= "</Companies>\n";
	
	$info = "";

	if ($row['G'] || $row['H']) {
		$info .= "<CompanyAddresses>\n";
		$info .= "<RowID>{$i}</RowID>\n";
		$info .= "<RowAddDateTime>{$time}</RowAddDateTime>\n";
		$info .= "<RowAddUserNo>1</RowAddUserNo>\n";
		$info .= "<RowEditDateTime>{$time}</RowEditDateTime>\n";
		$info .= "<RowEditUserNo>0</RowEditUserNo>\n";
		$info .= "<CompanyID>{$i}</CompanyID>\n";
		$info .= "<ID>{$i}</ID>\n";
		$info .= "<AddressID>{$i}</AddressID>\n";
		$info .= "</CompanyAddresses>\n";

		$info .= "<CompanyAddressAddresses>\n";
		$info .= "<RowID>{$i}</RowID>\n";
		$info .= "<RowAddDateTime>{$time}</RowAddDateTime>\n";
		$info .= "<RowAddUserNo>1</RowAddUserNo>\n";
		$info .= "<RowEditDateTime>{$time}</RowEditDateTime>\n";
		$info .= "<RowEditUserNo>0</RowEditUserNo>\n";
		$info .= "<ID>{$i}</ID>\n";
		$info .= "<Type>1</Type>\n";
		$info .= "<Name></Name>\n";
		$info .= "<CityID>1</CityID>\n";
		$info .= "<CityName>Adana</CityName>\n";
		$info .= "<CountyID>1</CountyID>\n";	
		$info .= "<CountyName>Seyhan</CountyName>\n";
		$info .= "<Township></Township>\n";
		$info .= "<Village></Village>\n";
		$info .= "<District></District>\n";
		$info .= "<Street>" . xmlEscape($row['G'] . ' / ' . $row['H']) . "</Street>\n";
		$info .= "<SiteName></SiteName>\n";
		$info .= "<BuildingName></BuildingName>\n";
		$info .= "<BuildingNo></BuildingNo>\n";
		$info .= "<FlatNo></FlatNo>\n";
		$info .= "<Latitude>0</Latitude>\n";
		$info .= "<Longitude>0</Longitude>\n";
		$info .= "</CompanyAddressAddresses>\n";
	}

	$phone = $row['F'];
	$phone = ltrim($phone, "0");
	$phone = preg_replace("/[^0-9]/", "", $phone);
	$phone = substr($phone, 0, 10);
	if ($phone != "" && strlen($phone) == 10) {
		$y++;
		$info .= "<CompanyPhones>\n";
		$info .= "<RowID>{$y}</RowID>\n";
		$info .= "<RowAddDateTime>{$time}</RowAddDateTime>\n";
		$info .= "<RowAddUserNo>1</RowAddUserNo>\n";
		$info .= "<RowEditDateTime>{$time}</RowEditDateTime>\n";
		$info .= "<RowEditUserNo>0</RowEditUserNo>\n";
		$info .= "<CompanyID>{$i}</CompanyID>\n";
		$info .= "<ID>{$y}</ID>\n";
		$info .= "<PhoneID>{$y}</PhoneID>\n";
		$info .= "</CompanyPhones>\n";

		$info .= "<CompanyPhonePhones>\n";
		$info .= "<RowID>{$y}</RowID>\n";
		$info .= "<RowAddDateTime>{$time}</RowAddDateTime>\n";
		$info .= "<RowAddUserNo>1</RowAddUserNo>\n";
		$info .= "<RowEditDateTime>{$time}</RowEditDateTime>\n";
		$info .= "<RowEditUserNo>0</RowEditUserNo>\n";
		$info .= "<ID>{$y}</ID>\n";
		$info .= "<Type>1</Type>\n";
		$info .= "<Name></Name>\n";
		$info .= "<Number>{$phone}</Number>\n";
		$info .= "<Extension></Extension>\n";
		$info .= "</CompanyPhonePhones>\n";
	}

	$output = "<CurrentAccounts>\n";
	$output .= $currents .  $company . $info;
	$output .= "</CurrentAccounts>\n";

	if (!is_dir($outputFilePath)) {
        mkdir($outputFilePath, 0755, true);
    }

    $fileName = str_replace('/', '_', $outputFilePath);

	$file = fopen($outputFilePath . $fileName . $row['A'] . ".xml", "w");
	fwrite($file, $output);
	fclose($file);
}