<?php
/**
 * Created by PhpStorm.
 * User: m.mitrofanov
 * Date: 10.10.2016
 * Time: 10:12
 */

require_once(SERVER_ROOT."/core/view/parsers/JSONParser.class.php");

//echo "Index \n";
//echo "\n";
//print_r($answer);

$parser = new JSONParser();
echo $parser->parse($answer);