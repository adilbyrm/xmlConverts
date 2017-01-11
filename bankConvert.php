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

$banks = [];
foreach ($xmls as $s) {
	if ($s->getName() == "Banks"){

		$banks[(int)$s->BankNo] = [
								'RowID' => $s->RowID,
								'RowAddDateTime' => $s->RowAddDateTime,
								'RowAddUserNo' => $s->RowAddUserNo,
								'RowEditDateTime' => $s->RowEditDateTime,
								'RowEditUserNo' => $s->RowEditUserNo,
								'ID' => $s->BankNo,
								'Code' => $s->BankCode,
								'Name' => $s->BankName,
								'Status' => 0,
								'_SynchronizationID_' => getGUID()
							];
	}
}

foreach ($xmls as $s) {
    if ($s->getName() == "BankBranches"){

        $banks[(int)$s->BankNo]['branches'][] = [
                                        'RowID' => $s->RowID,
                                        'RowAddDateTime' => $s->RowAddDateTime,
                                        'RowAddUserNo' => $s->RowAddUserNo,
                                        'RowEditDateTime' => $s->RowEditDateTime,
                                        'RowEditUserNo' => $s->RowEditUserNo,
                                        'BankID' => $s->BankNo,
                                        'ID' => $s->RowID,
                                        'Code' => $s->BranchCode,
                                        'Name' => $s->BranchName,
                                        'AddressExists' => 'false',
                                        'AddressID' => 0,
                                        'Status' => 0,
                                        '_SynchronizationID_' => getGUID()
                                    ];
    }
}



foreach ($banks as $s) {
    $xml = "<Banks>\n";
    $xml .= "<RowID>{$s['RowID']}</RowID>\n";
    $xml .= "<RowAddDateTime>{$s['RowAddDateTime']}</RowAddDateTime>\n";
    $xml .= "<RowAddUserNo>{$s['RowAddUserNo']}</RowAddUserNo>\n";
    $xml .= "<RowEditDateTime>{$s['RowEditDateTime']}</RowEditDateTime>\n";
    $xml .= "<RowEditUserNo>{$s['RowEditUserNo']}</RowEditUserNo>\n";
    $xml .= "<ID>{$s['ID']}</ID>\n";
    $xml .= "<Code>{$s['Code']}</Code>\n";
    $xml .= "<Name>" . xmlEscape($s['Name']) . "</Name>\n";
    $xml .= "<Status>0</Status>\n";
    $xml .= "<_SynchronizationID_>" . getGUID() . "</_SynchronizationID_>\n";
    $xml .= "</Banks>\n";

    $inNames = [];
    foreach ($s['branches'] as $v) {

        $branchName = (string)$v['Name'];
        if (in_array($branchName, $inNames)) {
            $count = count(array_filter($inNames, function ($n) use ($branchName) { return $n == $branchName; }));
            $branchName = $v['Name'] . '(' . $count . ')';
        }
        $inNames[] = (string)$v['Name'];

        $xml .= "<BankBranches>\n";
        $xml .= "<RowID>{$v['RowID']}</RowID>\n";
        $xml .= "<RowAddDateTime>{$v['RowAddDateTime']}</RowAddDateTime>\n";
        $xml .= "<RowAddUserNo>{$v['RowAddUserNo']}</RowAddUserNo>\n";
        $xml .= "<RowEditDateTime>{$v['RowEditDateTime']}</RowEditDateTime>\n";
        $xml .= "<RowEditUserNo>{$v['RowEditUserNo']}</RowEditUserNo>\n";
        $xml .= "<BankID>{$v['BankID']}</BankID>\n";
        $xml .= "<ID>{$v['ID']}</ID>\n";
        $xml .= "<Code>{$v['Code']}</Code>\n";
        $xml .= "<Name>" . xmlEscape($branchName) . "</Name>\n";
        $xml .= "<AddressExists>false</AddressExists>\n";
        $xml .= "<AddressID>0</AddressID>\n";
        $xml .= "<Status>0</Status>\n";
        $xml .= "<_SynchronizationID_>" . getGUID() . "</_SynchronizationID_>\n";
        $xml .= "</BankBranches>\n";
    }

    $output = "<Banks>\n";
    $output .= $xml;
    $output .= "</Banks>\n";

    if (!is_dir($outputFilePath)) {
        mkdir($outputFilePath, 0755, true);
    }

    $fileName = str_replace('/', '_', $outputFilePath);

    $file = fopen($outputFilePath . $fileName . $s['ID'] . ".xml", "w");
    fwrite($file, $output);
    fclose($file);
}