<?php

include "directive.class.php";

$a = new directive(file_get_contents('login.cat'));
//$b = new directive(file_get_contents('login.script.cat'));

	$val = $a->get_data();

	$fp = fopen('login.php', 'w');
	fwrite($fp, $val);
	fclose($fp);

$string = compilator($val);


echo '/////////////////',PHP_EOL;
echo $string, PHP_EOL;
echo '/////////////////',PHP_EOL;


function compilator( $data ) {
	
	ob_start();
	passthru ('php -f login.php');
	$out = ob_get_contents();
	ob_end_clean();
	//unlink('tmp.php');
	return $out;
}
/*
//echo $a->get_data();
	$val = $a->get_data();	

	
//echo $a->get_directive();
$string = compilator($val);


echo '/////////////////',PHP_EOL;
echo $string, PHP_EOL;
echo '/////////////////',PHP_EOL;

function compilator( $data ) {

	
	
	ob_start();
	passthru ('php -f tmp.php');
	$out = ob_get_contents();
	ob_end_clean();
	//unlink('tmp.php');
	return $out;
}
*/



?>