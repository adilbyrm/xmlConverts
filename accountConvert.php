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

$accounts = new SimpleXMLElement($xmlFilePath, null, true);

$i=10;
$y=5;
$z=5;
foreach($accounts as $s) {
	if ($s->getName() == "Currents" && $s->Active == "true"){
		$i++;

		if (strpos($s->CurrentName, ' ') !== false) {
			$x = explode(" ", $s->CurrentName);
			$name = $x[0];
			$surname = end($x);
		} else {
			$name = $s->CurrentName;
			$surname = "";
		}

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

		$currents = "<CurrentAccounts>\n";
		$currents .= "<RowID>{$i}</RowID>\n";
		$currents .= "<RowAddDateTime>{$s->RowAddDateTime}</RowAddDateTime>\n";
		$currents .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
		$currents .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
		$currents .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
		$currents .= "<ID>{$i}</ID>\n";
		$currents .= "<Type>1</Type>\n";
		$currents .= "<Code>{$s->CurrentNo}</Code>\n";
		$currents .= "<Name>". xmlEscape($s->CurrentName) ."</Name>\n";
		$currents .= "<InterestLevel>0</InterestLevel>\n";
		$currents .= "<SellerID>0</SellerID>\n";
		$currents .= "<SellerName></SellerName>\n";
		$currents .= "<GroupID>0</GroupID>\n";
		$currents .= "<SpecialCode>". xmlEscape($s->SpecialCode) ."</SpecialCode>\n";
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
		$currents .= "<Property1>{$s->Property1}</Property1>\n";
		$currents .= "<Property2>{$s->Property1}</Property2>\n";
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
		$currents .= "<Property16></Property16>\n";
		$currents .= "<Property17></Property17>\n";
		$currents .= "</CurrentAccounts>\n";

		

		$balance = "<CurrentAccountBalances>\n";
		$balance .= "<RowID>{$i}</RowID>\n";
		$balance .= "<RowAddDateTime>{$s->RowAddDateTime}</RowAddDateTime>\n";
		$balance .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
		$balance .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
		$balance .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
		$balance .= "<AccountID>{$i}</AccountID>\n";
		$balance .= "<CurrencyNo>{$currencyNo}</CurrencyNo>\n";
		$balance .= "<CurrencyCode>{$currencyCode}</CurrencyCode>\n";
		$balance .= "<Debt>0</Debt>\n";
		$balance .= "<Credit>0</Credit>\n";
		$balance .= "<DebtRemainder>0</DebtRemainder>\n";
		$balance .= "<CreditRemainder>0</CreditRemainder>\n";
		$balance .= "<Remainder>0</Remainder>\n";
		$balance .= "<Explanation></Explanation>\n";
		$balance .= "<Status>0</Status>\n";
		$balance .= "</CurrentAccountBalances>\n";

		$company = "<Companies>\n";
		$company .= "<RowID>{$i}</RowID>\n";
		$company .= "<RowAddDateTime>{$s->RowAddDateTime}</RowAddDateTime>\n";
		$company .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
		$company .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
		$company .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
		$company .= "<ID>{$i}</ID>\n";
		$company .= "<Title>". xmlEscape($s->CurrentName) ."</Title>\n";
		$company .= "<ShortTitle></ShortTitle>\n";
		$company .= "<InstitutionType>0</InstitutionType>\n";
		$company .= "<EmployeeCount>0</EmployeeCount>\n";
		$company .= "<LifeSpan>0</LifeSpan>\n";
		$company .= "<ParticipantCount>0</ParticipantCount>\n";
		$company .= "<TradeRegisterNumber>0</TradeRegisterNumber>\n";
		$company .= "<Scene></Scene>\n";
		$company .= "<Sector></Sector>\n";
		$company .= "<TaxNumber>{$s->TaxNo}</TaxNumber>\n";
		$company .= "<TaxOffice>{$s->TaxDepartment}</TaxOffice>\n";
		$company .= "</Companies>\n";
		
		$info = "";

		if ($s->Address != "") {
			$info .= "<CompanyAddresses>\n";
			$info .= "<RowID>{$i}</RowID>\n";
			$info .= "<RowAddDateTime>{$s->RowAddDateTime}</RowAddDateTime>\n";
			$info .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
			$info .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
			$info .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
			$info .= "<CompanyID>{$i}</CompanyID>\n";
			$info .= "<ID>{$i}</ID>\n";
			$info .= "<AddressID>{$i}</AddressID>\n";
			$info .= "</CompanyAddresses>\n";

			$info .= "<CompanyAddressAddresses>\n";
			$info .= "<RowID>{$i}</RowID>\n";
			$info .= "<RowAddDateTime>{$s->RowAddDateTime}</RowAddDateTime>\n";
			$info .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
			$info .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
			$info .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
			$info .= "<ID>{$i}</ID>\n";
			$info .= "<Type>1</Type>\n";
			$info .= "<Name></Name>\n";
			$info .= "<CityID>1</CityID>\n";
			$info .= "<CityName>İstanbul</CityName>\n";
			$info .= "<CountyID>1</CountyID>\n";	
			$info .= "<CountyName>Küçükçekmece</CountyName>\n";
			$info .= "<Township></Township>\n";
			$info .= "<Village></Village>\n";
			$info .= "<District></District>\n";
			$info .= "<Street>" . xmlEscape($s->Address . " " . $s->District ." / " . $s->City) . "</Street>\n";
			$info .= "<SiteName></SiteName>\n";
			$info .= "<BuildingName></BuildingName>\n";
			$info .= "<BuildingNo></BuildingNo>\n";
			$info .= "<FlatNo></FlatNo>\n";
			$info .= "<Latitude>0</Latitude>\n";
			$info .= "<Longitude>0</Longitude>\n";
			$info .= "</CompanyAddressAddresses>\n";
		}

		for ($x=1; $x<=3; $x++) {
			$phone = ($x==1 ? $s->Phone : ($x == 2 ? $s->Phone2 : $s->Phone3));
			$phone = ltrim($phone, "0");
			$phone = preg_replace("/[^0-9]/", "", $phone);
			if ($phone != "" && strlen($phone) == 10) {
				$y++;
				$info .= "<CompanyPhones>\n";
				$info .= "<RowID>{$y}</RowID>\n";
				$info .= "<RowAddDateTime>{$s->RowAddDateTime}</RowAddDateTime>\n";
				$info .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
				$info .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
				$info .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
				$info .= "<CompanyID>{$i}</CompanyID>\n";
				$info .= "<ID>{$y}</ID>\n";
				$info .= "<PhoneID>{$y}</PhoneID>\n";
				$info .= "</CompanyPhones>\n";
			}
		}

		
		for ($k=1; $k<=3; $k++) {
			$phone = ($k == 1 ? $s->Phone : ($k == 2 ? $s->Phone2 : $s->Phone3));
			$phone = ltrim($phone, "0");
			$phone = preg_replace("/[^0-9]/", "", $phone);
			$type = $k == 1 || $k == 2 ? 1 : 2;
			if ($phone != "" && strlen($phone) == 10) {
				$z++;
				$info .= "<CompanyPhonePhones>\n";
				$info .= "<RowID>{$z}</RowID>\n";
				$info .= "<RowAddDateTime>{$s->RowAddDateTime}</RowAddDateTime>\n";
				$info .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
				$info .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
				$info .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
				$info .= "<ID>{$z}</ID>\n";
				$info .= "<Type>{$type}</Type>\n";
				$info .= "<Name></Name>\n";
				$info .= "<Number>{$phone}</Number>\n";
				$info .= "<Extension></Extension>\n";
				$info .= "</CompanyPhonePhones>\n";
			}
		}

		if ($s->EMail != "" && !filter_var($s->EMail, FILTER_VALIDATE_EMAIL) === false) {
			$info .= "<CompanyEMails>\n";
			$info .= "<RowID>{$i}</RowID>\n";
			$info .= "<RowAddDateTime>{$s->RowAddDateTime}</RowAddDateTime>\n";
			$info .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
			$info .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
			$info .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
			$info .= "<CompanyID>{$i}</CompanyID>\n";
			$info .= "<ID>{$i}</ID>\n";
			$info .= "<EMailID>{$i}</EMailID>\n";
			$info .= "</CompanyEMails>\n";

			$info .= "<CompanyEMailEMails>\n";
			$info .= "<RowID>{$i}</RowID>\n";
			$info .= "<RowAddDateTime>{$s->RowAddDateTime}</RowAddDateTime>\n";
			$info .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
			$info .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
			$info .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
			$info .= "<ID>{$i}</ID>\n";
			$info .= "<Type>1</Type>\n";
			$info .= "<Name></Name>\n";
			$info .= "<Email>". str_replace("'", '', $s->EMail) ."</Email>\n";
			$info .= "</CompanyEMailEMails>\n";
		}



		$output = "<CurrentAccounts>\n";
		$output .= $currents . $balance . $company . $info;
		$output .= "</CurrentAccounts>\n";

		if (!is_dir($outputFilePath)) {
            mkdir($outputFilePath, 0755, true);
        }

        $fileName = str_replace('/', '_', $outputFilePath);

		$file = fopen($outputFilePath . $fileName . $s->CurrentNo . ".xml", "w");
		fwrite($file, $output);
		fclose($file);
	}
}