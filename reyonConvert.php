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

$i=0;
foreach($xmls as $s) {
	if ($s->getName() == "Sections"){
		$i++;

		$xml = "<Sections>\n";
		$xml .= "<RowID>{$i}</RowID>\n";
		$xml .= "<RowAddDateTime>{$s->RowDateTime}</RowAddDateTime>\n";
		$xml .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
		$xml .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
		$xml .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
		$xml .= "<ID>{$s->SectionNo}</ID>\n";
		$xml .= "<Name>" . xmlEscape($s->SectionName) . "</Name>\n";
		$xml .= "<Status>0</Status>\n";
		$xml .= "<_SynchronizationID_>" . getGUID() . "</_SynchronizationID_>\n";
		$xml .= "</Sections>\n";

		$output = "<Sections>\n";
		$output .= $xml;
		$output .= "</Sections>\n";

		if (!is_dir($outputFilePath)) {
            mkdir($outputFilePath, 0755, true);
        }

        $fileName = str_replace('/', '_', $outputFilePath);

		$file = fopen($outputFilePath . $fileName . $s->SectionNo . ".xml", "w");
		fwrite($file, $output);
		fclose($file);
	}
}