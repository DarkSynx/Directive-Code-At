<?php

$text = file_get_contents('page.cat');



include "directive.class.php";

$a = new directive($text);
//echo $a->get_data();


$string = compilator($a->get_data());

echo '/////////////////',PHP_EOL;
echo $string, PHP_EOL;
echo '/////////////////',PHP_EOL;

function compilator( $data ) {
	$fp = fopen('tmp.php', 'w');
	fwrite($fp, $data);
	fclose($fp);
	ob_start();
	passthru ('php -f tmp.php');
	$out = ob_get_contents();
	ob_end_clean();
	//unlink('tmp.php');
	return $out;
}




?>