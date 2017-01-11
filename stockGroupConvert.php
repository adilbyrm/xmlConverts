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

$groups = new SimpleXMLElement($xmlFilePath, null, true);

$i=0;
foreach($groups as $s) {
	if ($s->getName() == "StockGroups"){
		$i++;

		$stockGrous = "<StockGroups>\n";
		$stockGrous .= "<RowID>{$i}</RowID>\n";
		$stockGrous .= "<RowAddDateTime>{$s->RowDateTime}</RowAddDateTime>\n";
		$stockGrous .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
		$stockGrous .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
		$stockGrous .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
		$stockGrous .= "<ID>{$s->GroupNo}</ID>\n";
		$stockGrous .= "<Name>" . xmlEscape($s->GroupName) . "</Name>\n";
		$stockGrous .= "<ParentID>0</ParentID>\n";
		$stockGrous .= "<ParentName></ParentName>\n";
		$stockGrous .= "<Picture></Picture>\n";
		$stockGrous .= "<Status>0</Status>\n";
		$stockGrous .= "<_SynchronizationID_>" . getGUID() . "</_SynchronizationID_>\n";
		$stockGrous .= "</StockGroups>\n";
		

		$output = "<StockGroups>\n";
		$output .= $stockGrous;
		$output .= "</StockGroups>\n";

		if (!is_dir($outputFilePath)) {
            mkdir($outputFilePath, 0755, true);
        }

		$file = fopen($outputFilePath . $s->GroupNo . ".xml", "w");
		fwrite($file, $output);
		fclose($file);
	}
}