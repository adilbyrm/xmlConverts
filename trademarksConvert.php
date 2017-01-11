<?php
if (!isset($argv[1]) || !isset($argv[2])) {
    exit('parametre eksik');
}

$xmlFilePath = $argv[1]; // like -> velesbit/trademarks.xml
$outputFilePath = $argv[2]; // like -> velesbit/trademarks/

function xmlEscape($string)
{
    return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
}

function getGUID()
{
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid, 12, 4).$hyphen
            .substr($charid, 16, 4).$hyphen
            .substr($charid, 20, 12)
            .chr(125);// "}"
        return $uuid;
    }
}

$trademarks = new SimpleXMLElement($xmlFilePath, null, true);

$i=0;
foreach ($trademarks as $s) {
    if ($s->getName() == "Trademarks") {
        $i++;

        $trademarks = "<Trademarks>\n";
        $trademarks .= "<RowID>{$i}</RowID>\n";
        $trademarks .= "<RowAddDateTime>{$s->RowDateTime}</RowAddDateTime>\n";
        $trademarks .= "<RowAddUserNo>{$s->RowAddUserNo}</RowAddUserNo>\n";
        $trademarks .= "<RowEditDateTime>{$s->RowEditDateTime}</RowEditDateTime>\n";
        $trademarks .= "<RowEditUserNo>{$s->RowEditUserNo}</RowEditUserNo>\n";
        $trademarks .= "<ID>{$s->TrademarkNo}</ID>\n";
        $trademarks .= "<Name>" . xmlEscape($s->TrademarkName) . "</Name>\n";
        $trademarks .= "<Status>0</Status>\n";
        $trademarks .= "<_SynchronizationID_>" . getGUID() . "</_SynchronizationID_>\n";
        $trademarks .= "</Trademarks>\n";

        $output = "<Trademarks>\n";
        $output .= $trademarks;
        $output .= "</Trademarks>\n";

        if (!is_dir($outputFilePath)) {
            mkdir($outputFilePath, 0755, true);
        }
        
        $file = fopen($outputFilePath . $s->TrademarkNo . ".xml", "w");
        fwrite($file, $output);
        fclose($file);
    }
}
