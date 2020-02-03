<?php


$text = <<<END


phrase qui s'affiche
@set( \$a='test')
@var(a::'test')

@dow(\$a!='test'){
	<html>mon code html yyyyy</html>
}
@dowhile
	<html>code html zzz</html>
@whiledo(\$a!='test')

@say('content')
@off('content')
@int(['content']=false)
@init('content'::true)

@see('content')
	mon code html 1 
@endsee
@is(['content222'] == false )
	mon code html 2
@endis
@goto(a)
@exe( echo mafonction() )
@fct( mafonction() { 
	return 'test';
})

@use::mafonction()


@label(a)
@if(\$a == 'test')
ok c'est cool 111111
@elseif( \$a == 'toto')
@else
non c'est pas cool 111111
@endif
END;

/*

@if(\$a == 'test')
ok c'est cool 222222
@elseif( \$a == 'toto')
@else
non c'est pas cool 22222222
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif
@if(\$a == 'test')
ok c'est cool
@elseif( \$a == 'toto')
@else
non c'est pas cool
@endif*/

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

/*
$text = <<<END
-set(\$a = 'test')
-if(\$a == 'test'):
ok c'est cool
-else:
non c'est pas cool
-endif
END;

$text = str_ireplace(['-set(','-if','-else','-endif'],['<?php ','<?php if','<?php else', '<?php endif'],$text);

echo $text;


$fp = fopen('test.php', 'w');
	fwrite($fp, PHP_EOL);
fclose($fp); 


$a=10;
if($a==0):
echo "ok";
elseif($a==10):
echo "ok1";
else:
echo "no";
endif;



sleep(100);

$a = [ 1, 2 , -1 , 4 , 5 , -1 , 3 , -2, 1, -2,1,2,3,4,-1,-1,2,3,-2,-2];
print_r(recurcive($a));

echo "FIn", PHP_EOL;


function recurcive($a,$k=-1,$r=0,$tab=array()) {    
    while(++$k < count($a)) {
        switch($a[$k]) {
            case -1:
                echo str_repeat('#',$r) . $a[$k] . PHP_EOL; 
                $tab[$k] = ['name'=>$a[$k], 'level'=>$r];
                return recurcive($a,$k,++$r,$tab);
            break;
            
            case -2:
                $r--;
            break;
            
            default:
            echo str_repeat('#',$r) . $a[$k] . PHP_EOL;
            $tab[$k] = ['name'=>$a[$k], 'level'=>$r];
        }
    }    
    return $tab;
}


sleep(1000);
$test = 5;

$TEXT = <<<END
    -var(AA = $test)  
    -var(BB = 15) 
    -if(AA == 5)
        -then
            -if(AA > 5)
            -then 
                ok
            if-
        -else(AA < 40)
            no1
        -else
            no2
    if-
END;


$PUR_TEXT = str_replace(["\t","\r","\n"],null, $TEXT);
$index = indexeur($PUR_TEXT);


print_r($index);


function indexeur( $data ) {
 $index = array();
 $c = strlen($data);
 $tag = array('-var','-if','if-','-then','-else');
 foreach($tag as $fdtag) { 
    $d=strlen($fdtag);
    for($a=0; $a<$c; $a++) {
        $b = strpos($data, $fdtag, $a);
        if($b){ $a = $b;
            if(array_key_exists($a, $index)){ break; }
            $index[$a] = ['tag' =>$fdtag, 'pos'=> $a];
            //rechercher les ( )
            if($data[$a+$d] == '(') {
                $e = strpos($data, ')', $a+$d);
                if($e) {
                    $index[$a]['eval'] = trim(substr($data, $a+$d+1, ($e - ($a+$d+1)) ));
                }
            }
        }

    }
 }
 ksort($index);
 $index = array_merge($index);
 
 
 
 
 return $index;
}
*/


?>