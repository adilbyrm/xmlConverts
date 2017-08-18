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

$colors = new SimpleXMLElement($xmlFilePath, null, true);

$inNames = [];

$i=0;
foreach($colors as $s) {
	if ($s->getName() == "Colors"){

		$colorName = (string)$s->ColorName;
		if (in_array($colorName, $inNames)) {
			$count = count(array_filter($inNames, function ($n) use ($colorName) { return $n == $colorName; }));
			$colorName = $s->ColorName . '(' . $count . ')';
		}
		$inNames[] = (string)$s->ColorName;

		$i++;

		$color = $s->Color == 0 ? '-1' : $s->Color; 

		$colors = "<Colors>\n";
		$colors .= "<RowID>{$i}</RowID>\n";
		$colors .= "<RowAddDateTime>{$s->RowDateTime}</RowAddDateTime>\n";
		$colors .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
		$colors .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
		$colors .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
		$colors .= "<ID>{$s->ColorNo}</ID>\n";
		$colors .= "<Name>" . xmlEscape($colorName) . "</Name>\n";
		$colors .= "<Color>{$color}</Color>\n";
		$colors .= "<Status>0</Status>\n";
		$colors .= "<_SynchronizationID_>" . getGUID() . "</_SynchronizationID_>\n";
		$colors .= "</Colors>\n";

		$output = "<Colors>\n";
		$output .= $colors;
		$output .= "</Colors>\n";

		if (!is_dir($outputFilePath)) {
            mkdir($outputFilePath, 0755, true);
        }

        $fileName = str_replace('/', '_', $outputFilePath);

		$file = fopen($outputFilePath . $fileName . $s->ColorNo . ".xml", "w");
		fwrite($file, $output);
		fclose($file);
	}
}