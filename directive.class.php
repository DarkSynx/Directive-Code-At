<?php 
/*
 USE 7.4
 
 !!! INITIALISING CAT AND CACHE DEFINE !!!
 
*/



class directive {
	
	private $_final_page;
	private $_data;
	private $_filename = '';
	private $_nocache = false;
	
	
	// on récupére que par get_data mais on peu initialisé par le constructeur ou par set_data
	public function __construct( $filename=false ) {   
		if($filename){
			$this->set_page($filename);
			$this->set_data( $this->get_page() );
			}
	}
	public function set_page($filename) { $this->_filename = $filename; }
	public function get_page() { return file_get_contents( CAT . $this->_filename); }
	public function set_data($data) { $this->_data = $data; $this->gen(); }	
	public function get_data() { return $this->_final_page; }
	
	
	public function get_directive() { 
		$sortir = '';
		foreach($this->gen(TRUE) as $key => $val) {
			$sortir .= 	$key . PHP_EOL;
		}
		return $sortir;
	}
	
	public function cache_it() {
		if($this->_nocache) {
			$fp = fopen( CACHE . $this->_filename . '.php', 'w');
			fwrite($fp, $this->get_data());
			fclose($fp);
		}
	}
	
	private function gen($rerturn_tag=FALSE,$optimised=TRUE,$timetest=true) {
		
		global 	$data;
		$iner_var = array();
		$data = $this->_data;
		
		
		$tags = [
		
		
			// toujours au début get
			['bop',['@include'		,0,'include_once( CAT . □',');','<?php ',' ?>','(',')','', '', '□','']],

			['bop',['@structure'	,0,]],
			['bop',['@getfile'		,0,'', '','<?php ',' ?>','(',')','','','□','',FALSE]],
			['bop',['@getsegment'	,0,]],
			['bop',['@phpsegment'	,0,]],
			
			['bof',['@setsegment'	,0,'<!--SEGMENT:', '','(',')','','']],
			['bsp',['@endsegment' 	,'-->','','']],


			['bof',['@setvar'		,0]], // initialisé des variables
			['bof',['@unsetvar'		,0,'unset(',');']], // initialisé des variables
			['bop',['@use'			,0,'',');','<?php ',' ?>',':',')']], // utilisé une function 
			['bop',['@instancy'		,0,'□ = new ', '■;','<?php ',' ?>','(',')','{','}','□','■',FALSE]], // instancier l'objet
			['bof',['@object'		,0,'', ';']], // utilisé un objet
			['bop',['@setclass'		,0,'class □ ', '{■}','<?php ',' ?>','(',')','{','}','□','■',FALSE]],	// créé des class		
		
			['bsp',['@dowhile'		,"do{ echo <<<END\r\n",'<?php ','']],
			['bof',['@whiledo'		,0,"\r\nEND;\r\n}while(", ');','(',')','']],
			['bop',['@dow'			,0,'do{', '■}while(□);']],	

			['bof',['@PHP'			,0,'','','{','}']], // affiche son resulta
			['bof',['@JSR'			,0,'','','{','}','<script>$( document ).ready(function(){','});</script>']], // affiche son resulta
			['bof',['@JS'			,0,'','','{','}','<script>','</script>']], // affiche son resulta
			

			['bof',['@if'			,0,'if(', '):']],
			['bof',['@elseif'		,0,'elseif(', '):']],
			['bsp',['@else'			,'else:']],
			['bsp',['@endif'		,'endif;']],
			['bof',['@foreach'		,0,'foreach(','):']],
			['bsp',['@endforeach'	,'endforeach;']],
			['bof',['@for'			,0,'for(','):']],
			['bsp',['@endfor'		,'endfor;']],
			['bof',['@while'		,0,'while(','):']],
			['bsp',['@endwhile'		,'endwhile;']],
			['bof',['@switch'		,0,'switch (','):']],
			['bof',['@case'			,0,'case (','):']],
			['bsp',['@break'		,'break;']],
			['bsp',['@continue'		,'continue;']],
			['bsp',['@default'		,'default:']],
			['bsp',['@endswitch'	,'endswitch;']],
			['bof',['@goto'			,0,'goto ', ';']],
			['bof',['@label'		,0,'', ':']],

			// code encapsuler 
			['bof',['@isTRUE'		,0,'echo ((',")? <<<END\r\n",'(',')','<?php ', '']],
			['bsp',['@endisTRUE'	,"\r\nEND\r\n:'');",'']],
			['bof',['@isfalse'		,0,'echo ((',")? '':<<<END\r\n",'(',')','<?php ', '']],
			['bsp',['@endisfalse'	,"\r\nEND\r\n);",'']],
			

			['bsp',['@sessionstart'	,'session_start();']],

			////////////////////////////////////

			['bsp',['@timetest'		,'$microtime_start_test = microtime(TRUE);']],
			['bsp',['@endtimetest'	,'$microtime_end_test = microtime(TRUE); echo round(($microtime_end_test - $microtime_start_test),4);']],


			['bsp',['@headpage'		,'<html><head>','','']],
			['bsp',['@bodypage'		,'</head><body>','','']],
			['bsp',['@endpage'		,'</body></html>','','']],
			
			['bop',['@html'			,0,'<html □>','■</html>', '','']],			
			['bof',['@head'			,0,'<head>','</head>', '{', '}', '','']],			
			['bof',['@title'		,0,'<title>','</title>', '(', ')', '','']],
			['bof',['@meta'			,0,'<meta ', '>', '(', ')', '','']],
			['bof',['@link'			,0,'<link ', '>', '(', ')', '','']],
			['bof',['@filecss'		,0,'<link rel="stylesheet" type="text/css" href=', '>', '(', ')', '','']],
			['bof',['@script'		,0,'<script src=', '></script>', '(', ')', '','']],
			['bof',['@script+'		,0,'<script ', '></script>', '(', ')', '','']],
			['bof',['@style'		,0,'<style>', '</style>', '{', '}', '','']],
			['bop',['@body'			,0,'<body □>','■</body>', '','']],
	

			
			['bop',['@blockquote'	,0,'<blockquote □>','■</blockquote>', '','']],
			['bop',['@figcaption'	,0,'<figcaption □>','■</figcaption>', '','']],
			
			['bop',['@colgroup'		,0,'<colgroup □>','■</colgroup>', '','']],
			['bop',['@datalist'		,0,'<datalist □>','■</datalist>', '','']],
			['bop',['@fieldset'		,0,'<fieldset □>','■</fieldset>', '','']],
			['bop',['@noscript'		,0,'<noscript □>','■</noscript>', '','']],
			['bop',['@optgroup'		,0,'<optgroup □>','■</optgroup>', '','']],
			['bop',['@progress'		,0,'<progress □>','■</progress>', '','']],
			['bop',['@textarea'		,0,'<textarea □>','■</textarea>', '','']],
			['bop',['@!DOCTYPE'		,0,'<!DOCTYPE  □','>', '','','(',')','', '', '□','',]],
			
			['bop',['@address'		,0,'<address □>','■</address>', '','']],
			['bop',['@article'		,0,'<article □>','■</article>', '','']],
			['bop',['@caption'		,0,'<caption □>','■</caption>', '','']],
			['bop',['@command'		,0,'<command □>','■</command>', '','']],
			['bop',['@details'		,0,'<details □>','■</details>', '','']],
			['bop',['@section'		,0,'<section □>','■</section>', '','']],
			['bop',['@summary'		,0,'<summary □>','■</summary>', '','']],
			
			['bop',['@button'		,0,'<button □>','■</button>', '','']],
			['bop',['@canvas'		,0,'<canvas □>','■</canvas>', '','']],
			['bop',['@figure'		,0,'<figure □>','■</figure>', '','']],
			['bop',['@footer'		,0,'<footer □>','■</footer>', '','']],
			['bop',['@header'		,0,'<header □>','■</header>', '','']],
			['bop',['@hgroup'		,0,'<hgroup □>','■</hgroup>', '','']],
			['bop',['@iframe'		,0,'<iframe □>','■</iframe>', '','']],
			['bop',['@keygen'		,0,'<keygen □>','■</keygen>', '','']],
			['bop',['@legend'		,0,'<legend □>','■</legend>', '','']],
			['bop',['@object'		,0,'<object □>','■</object>', '','']],
			['bop',['@option'		,0,'<option □>','■</option>', '','']],
			['bop',['@output'		,0,'<output □>','■</output>', '','']],
			['bop',['@select'		,0,'<select □>','■</select>', '','']],
			['bop',['@source'		,0,'<source □>','■</source>', '','']],
			['bop',['@strong'		,0,'<strong □>','■</strong>', '','']],
			['bop',['@center'		,0,'<center □>','■</center>', '','']],
			
			['bop',['@aside'		,0,'<aside □>','■</aside>', '','']],
			['bop',['@audio'		,0,'<audio □>','■</audio>', '','']],
			['bop',['@embed'		,0,'<embed □>','■</embed>', '','']],
			['bop',['@input'		,0,'<input □' ,'>', '','','(',')','', '', '□','',]],
			['bop',['@label'		,0,'<label □>','■</label>', '','']],
			['bop',['@meter'		,0,'<meter □>','■</meter>', '','']],
			['bop',['@param'		,0,'<param □>','■</param>', '','']],
			['bop',['@small'		,0,'<small □>','■</small>', '','']],
			['bop',['@table'		,0,'<table □>','■</table>', '','']],
			['bop',['@tbody'		,0,'<tbody □>','■</tbody>', '','']],
			['bop',['@tfoot'		,0,'<tfoot □>','■</tfoot>', '','']],
			['bop',['@thead'		,0,'<thead □>','■</thead>', '','']],
			['bop',['@title'		,0,'<title □>','■</title>', '','']],
			['bop',['@track'		,0,'<track □>','■</track>', '','']],
			['bop',['@video'		,0,'<video □>','■</video>', '','']],
			
			
			
			['bop',['@abbr'			,0,'<abbr □>','■</abbr>', '','']],
			['bop',['@area'			,0,'<area □>','■</area>', '','']],
			['bop',['@base'			,0,'<base □>','■</base>', '','']],
			['bop',['@cite'			,0,'<cite □>','■</cite>', '','']],
			['bop',['@code'			,0,'<code □>','■</code>', '','']],
			['bop',['@form'			,0,'<form □>','■</form>', '','']],
			['bop',['@mark'			,0,'<mark □>','■</mark>', '','']],
			['bop',['@math'			,0,'<math □>','■</math>', '','']],
			['bop',['@menu'			,0,'<menu □>','■</menu>', '','']],
			['bop',['@ruby'			,0,'<ruby □>','■</ruby>', '','']],
			['bop',['@samp'			,0,'<samp □>','■</samp>', '','']],
			['bop',['@span'			,0,'<span □>','■</span>', '','']],
			['bop',['@time'			,0,'<time □>','■</time>', '','']],
			
			['bop',['@bdo'			,0,'<bdo □>','■</bdo>', '','']],
			['bop',['@col'			,0,'<col □>','■</col>', '','']],
			['bop',['@del'			,0,'<del □>','■</del>', '','']],
			['bop',['@dfn'			,0,'<dfn □>','■</dfn>', '','']],
			['bop',['@div'			,0,'<div □>','■</div>', '','']],
			['bop',['@img'			,0,'<img □>','■</img>', '','']],
			['bop',['@ins'			,0,'<ins □>','■</ins>', '','']],
			['bop',['@kbd'			,0,'<kbd □>','■</kbd>', '','']],
			['bop',['@map'			,0,'<map □>','■</map>', '','']],
			['bop',['@nav'			,0,'<nav □>','■</nav>', '','']],
			['bop',['@pre'			,0,'<pre □>','■</pre>', '','']],
			['bop',['@sub'			,0,'<sub □>','■</sub>', '','']],
			['bop',['@sup'			,0,'<sup □>','■</sup>', '','']],
			['bop',['@svg'			,0,'<svg □>','■</svg>', '','']],
			['bop',['@var'			,0,'<var □>','■</var>', '','']],
			['bop',['@wbr'			,0,'<wbr □>','■</wbr>', '','']],
			
			['bsp',['@br'			,'<br></br>','','']],
			
			['bop',['@dd'			,0,'<dd □>','■</dd>', '','']],
			['bop',['@dl'			,0,'<dl □>','■</dl>', '','']],
			['bop',['@dt'			,0,'<dt □>','■</dt>', '','']],
			['bop',['@em'			,0,'<em □>','■</em>', '','']],
			['bop',['@h1'			,0,'<h1 □>','■</h1>', '','']],
			['bop',['@h2'			,0,'<h2 □>','■</h2>', '','']],
			['bop',['@h3'			,0,'<h3 □>','■</h3>', '','']],
			['bop',['@h4'			,0,'<h4 □>','■</h4>', '','']],
			['bop',['@h5'			,0,'<h5 □>','■</h5>', '','']],
			['bop',['@h6'			,0,'<h6 □>','■</h6>', '','']],
			
			['bop',['@hr'			,0,'<hr □>','</hr>', '','', '(',')','', '', '□','',]],
			
			['bop',['@li'			,0,'<li □>','■</li>', '','']],
			['bop',['@ol'			,0,'<ol □>','■</ol>', '','']],
			['bop',['@rp'			,0,'<rp □>','■</rp>', '','']],
			['bop',['@rt'			,0,'<rt □>','■</rt>', '','']],
			['bop',['@td'			,0,'<td □>','■</td>', '','']],
			['bop',['@th'			,0,'<th □>','■</th>', '','']],
			['bop',['@tr'			,0,'<tr □>','■</tr>', '','']],
			['bop',['@ul'			,0,'<ul □>','■</ul>', '','']],
			
			['bop',['@a'			,0,'<a □>','■</a>', '','']],
			['bop',['@b'			,0,'<b □>','■</b>', '','']],
			['bop',['@i'			,0,'<i □>','■</i>', '','']],
			['bop',['@p'			,0,'<p □>','■</p>', '','']],
			['bop',['@q'			,0,'<q □>','■</q>', '','']],
			
			['bof',['@:'			,0,'',';','{','}']], // affiche son resulta
			['bop',['@.'			,0,'','', '','',]], // comment cat invisible
			['bop',['@?'			,0,'','', '','','<','>','', '', '□','']], // comment cat invisible
			['bof',['@!'			,0,'','','<','>','<!-- ',' -->']], // comment html
			['bof',['@*'			,0,'/* $','*/','<','>']], // comment php
			['bof',['@>'			,0,'echo ',';','{','}']], // affiche son resulta
			['bof',['@'				,0,'echo $',';','{','}']], // affiche son resulta
			
			
		];
		
		
		
		// préparation création d'un tableau de revers
		$tabloc = array();	
		foreach($tags as $k => $fnc) { $tabloc[$fnc[1][0]] = $k; }
		if($rerturn_tag) { return $tabloc; }
		
		// on charge les fichier des fonctions qui load du code
		$rx = ['@structure' => 0,'@getfile' => 0,'@getsegment' => 0, '@phpsegment' => 0];
		foreach($rx as $fnd => &$nbf) { $nbf = substr_count($data, $fnd); }
		while( array_sum($rx) > 0 ){ 	
			foreach($rx as $fnd => $nbf) {
				if($nbf > 0) {
					$fndx = $tabloc[$fnd];
					$tags[$fndx][1][1] = $nbf;
					//$tags[$fndx][1][2] = &$data;
					call_user_func_array([$this,$tags[$fndx][0]],$tags[$fndx][1]);
					
				}
			}
			foreach($rx as $fnd => &$nbf) { $nbf = substr_count($data, $fnd); }	
		}
		//var_dump($tags);
		
		unset($tags[1],$tags[2],$tags[3],$tags[4]);
	
		// on hash les données brute et si elle son similaire
		// alors on recharge le fichier fabriqué sinon 
		// on le refabrique
		$hashdata = hash('adler32',$data);
		$filehash = @file_get_contents(CACHE . $this->_filename . '.hash');
		if($hashdata /*= $filehash or (strlen(file_get_contents(CACHE . $this->_filename . '.php')) == 0)*/ ) {
		
			$fp = fopen( CACHE . $this->_filename . '.hash', 'w');
			fwrite($fp, $hashdata);
			fclose($fp);
			$this->_nocache = true;
			
		} else { $this->_nocache = false; return $tabloc; }
	
		// on supprime les functions non présente dans data
		// et on prépare les variables du talbleau
		foreach($tags as $k => &$fnc) {				
				
				$ctme = substr_count($data, $fnc[1][0]);
				
				if($fnc[0] != 'bsp' ) {
					//echo $fnc[0] . '</br/>';
					$fnc[1][1] = $ctme;	
				}
				
				if( $ctme == 0 ) {
					unset( $tags[$k] );
				}
			
		}
		
		//var_dump($tags);
		
		if($timetest) { $time_start = microtime(true); }
			
			// on demarre la fabrication
			foreach($tags as &$fnc) {
				//$fnc[1][2] = &$data;
				call_user_func_array([$this,$fnc[0]],$fnc[1]);
			}
		
		if($timetest) {
			$time_end = microtime(true);
			$time = $time_end - $time_start;
		}
		
		$optimised = false;
		if($optimised) {
		
		$data = str_replace(
		["\t\r\nt\r\n",		"\t\r\n", 	"\r\n\r\n\r\n",		"\r\n\r\n",		"\r\n ?>",		' >', "\r\n\t\r\n", "\r\n\t\t\r\n", "\r\n", "\r\r", "\t\t\t", "\t\t","\r ","\r  ","  "], 
		["\r\n",			"\r\n",		"\r\n",				"\r\n", 		"\r\n?>",		'>',  "\r\n", "\r\n", "\r","\r","\t","\t","\r","\r"," "], $data);
		
		
		$x = strlen($data);
		$tabp = [chr(32),"\t","\0","\r","\n","<"];
		for($l=0; $l < $x; $l++) {
			if(substr($data,$l, 2) == '?>') {
				//echo '[',substr($data,$l,2),']', PHP_EOL;
				
				for($z=$l+2; $z < $x; $z++) {
					
					if(in_array($data[$z],$tabp)) {
						
							if(substr($data,$z, 5) == '<?php') {

							$data = substr_replace($data, chr(32), $l, (($z - $l)+5) );
						
							
							$l = $z+5;
							break;
							
							}
						
					 
					} else{ break;}
				}
			}
		}
		
		
		$data = $this->sanitize_output($data);
		}




		if($timetest) {echo "execusion $time secondes\n";}
		$this->_final_page = $data;
			
		
	}
	
	private function sanitize_output($buffer) {

   $search = array(
    '/(\n|^)(\x20+|\t)/',
    '/(\n|^)\/\/(.*?)(\n|$)/',
    '/\n/',
    '/\<\!--.*?-->/',
    '/(\x20+|\t)/', # Delete multispace (Without \n)
    '/\>\s+\</', # strip whitespaces between tags
    '/(\"|\')\s+\>/', # strip whitespaces between quotation ("') and end tags
    '/=\s+(\"|\')/'
	); # strip whitespaces between = "'

   $replace = array(
    "",
    "",
    " ",
    "",
    " ",
    "><",
    "$1>",
    "=$1"
	);

		$buffer = preg_replace($search, $replace, $buffer);

		return $buffer;
	}
	
	
	private function bsp($find,$replace,$debx='<?php ',$endx=' ?>'){
		//var_dump([$find,$replace,$debx,$endx]);
		global $data;
		$data = str_replace($find,"$debx$replace$endx", $data);
	}
	
	private function bop($find,$fdb,$deb='', $fin='',$debx='<?php ',$endx=' ?>',$bdeb1='(',$bfin1=')',$bdeb2='{',$bfin2='}',$masque1='□',$masque2='■',$exp=TRUE,$b=0, $mex=''){
			
			global $data;
			
			$dd = strlen($data);
			$s = strlen($find);
			while(--$fdb >= 0) {
				$b = strpos($data,$find,$b);
				$bs = ($b + $s);
				if($b !== false ) {
					
					
								$nodeb1 = false;
								// si les parenthéses existes 
								if( $data[$bs] == $bdeb1 && $data[$bs + 1] == $bfin1 && ($bdeb2 == '' || $bdeb2 == false) ) {
									$c = $bs + 2;
									$nodeb1 = true; 
									 //echo '<br/>',$find,':1';
								}
								elseif ($data[$bs] == chr(32) && $data[$bs + 1] == $bdeb1 && $data[$bs + 2] == $bfin1 && ($bdeb2 == '' || $bdeb2 == false) ) {
									$c = $bs + 3;
									$nodeb1 = true; 
									//echo '<br/>',$find,':2';
								}
								elseif( 
								 ($data[$bs] == $bdeb1 || $data[$bs + 1] == $bdeb1 )  && 
								 (($c = strpos($data,$bfin1,$bs)) !== false )
								
								) {
									$c = ($data[$c] == $data[$bs+1]) ? ($bs+2) : $c-=1 ;

									
									
										for($j=0; $j < $dd ;$j++) {
											$g = substr($data,$bs,($c - $bs));
											$k = substr_count( $g , $bdeb1);
											$l = substr_count( $g , $bfin1);
											//if($find == '@getsegment') { echo $k , '==' ,$l, ' :: ',$c, PHP_EOL; }
											if($k == $l) { break; }
											$c = strpos($data,$bfin1, $c+1) + 1; //execusion 0.0077269077301025 secondes
											//$c++; // execusion 0.0082950592041016 secondes
										}
										
										
										
										$mex = trim( substr($data,($bs + 1),( $c - $bs) - 2) );


								} else { 
								$c = $bs;								
								$nodeb1 = true; 
								
								}
								
								
								
								if(
								
								($bdeb2 && (($e = strpos($data,$bfin2,$c)) !== false) ) //bdeb2 != '' || $bdeb2 !== false
								&& 
								( ($data[$c] == $bdeb2) || ($data[$c+1] == $bdeb2) )
								 
								 ){ 
								  
								  $c = ($data[$c+1] == $bdeb2)  ? $c+1 : $c ;


										for($j=0; $j < $dd ;$j++) {
											$g = substr($data,$c,($e - $c));
											$o = substr_count( $g , $bdeb2);
											$p = substr_count( $g , $bfin2);
											if($o == $p) { break; }
											$e = strpos($data,$bfin2, $e) + 1; 
										}
									

										$m2 = substr($data,$c+1,($e - $c)-2); 

									if( ($masque2) && ($exp === false) && ($nodeb1 === false) ) { $m2 = "echo <<<END2 $nodeb1 \r\n$m2\r\nEND2;\r\n"; }

								} else { $e = $c; }



								// si masque 1 existe
								if($masque1) {
									
									$debo = $deb;
									$fino = $fin;
									
									// si $deb à une exception  précise
									switch($find) { // si masque 1 est true

										case '@getfile':
										
											$debx = "\r\n<!-- start invoc file : $mex -->\r\n" ;
											$debo = file_get_contents( CAT . trim($mex,'\'') );
											$endx = "\r\n<!-- END invoc file : $mex -->\r\n";
											$fino = '';
											$mex  = '';
											
										break;
										
										case '@structure':
												$debx = "\r\n<!-- start import file : $mex -->\r\n";
												$endx = "\r\n<!-- END import file : $mex -->\r\n";
												$debf = file_get_contents( CAT . trim($mex,'\''));
												$mxxx = array_filter( explode('|',$m2), function($v){ return trim($v); } );

												//var_dump($mxxx);
												foreach($mxxx as $k => $l) {
													$l = trim($l);
													
													if(strpos($l, '~') === false) { 
														$ddt = (file_exists(CAT . trim($l,'\'')) ? file_get_contents( CAT . trim($l,'\'')) : trim($l,'\'') ); 
													}
													else { 
														$gll = explode('~',$l);
														//var_dump($gll);
														//$gll[1] = trim($gll[1]);
														$ddt = trim($gll[1],'\'');
														//var_dump($ddt);
														$l = $gll[0];
													}
													//$l = str_replace(["\r","\n","\t",'\''],'',$l);
													$debf = str_replace('{{'.trim($l,'\'').'}}', $ddt, $debf);
												}
												$debo = $debf;
												$fino = ''; unset($debf);
												$mex  = '';
										break;
										case '@getsegment':
												$debx = "\r\n<!-- start import:segment $m2 to file : $mex -->\r\n";
												$endx = "\r\n<!-- END import:segment $m2 to file : $mex -->\r\n";
										case '@phpsegment':	
										
											$debf = file_get_contents( CAT . trim($mex,'\''));
											$tds  = strlen("@setsegment($m2)");
											$ddms = strpos($debf,"@setsegment($m2)",0);
											$fdms = strpos($debf,'@endsegment',$ddms+1);
											$debo = substr($debf,($ddms + $tds),($fdms - ($ddms + $tds)));
											$fino = ''; unset($debf);
											$mex  = '';
											
										break;
									}
									
									
									
									$rpl = "$debx$debo$fino$endx";
									$rpl = ($masque2 ? str_replace($masque2,$m2,str_replace($masque1,$mex,$rpl)) : str_replace($masque1,$mex,$rpl));

								}	
								else {
									$rpl = "$debx$deb$mex$fin$endx";
								}
						
							$data = substr_replace($data, $rpl, $b, (($e - $b)) );
							$mex=''; $m2='';

							$b = $bs + 1;
	
				
				} else { return false; }

			}
	
	}

	
	private function bof($find,$fdb,$deb='', $fin='',$bdeb='(',$bfin=')',$debx='<?php ',$endx=' ?>',$b=0){
			global $data;
			
			$d = strlen($data);
			$s = strlen($find);
			$i=1;	
			while(--$fdb >= 0) { 
				$b = strpos($data,$find,$b);
				$bs = ($b + $s);
				if(
					( $b !== false ) && 
					( $data[$bs] == $bdeb )  && 
					( ($c = strpos($data,$bfin,$bs)) !== false ) 
				) {  
							

							$c--; 
								for($j=0; $j < $d ;$j++) {
									$g = substr($data,$bs,($c - $bs));
									$k = substr_count( $g , $bdeb);
									$l = substr_count( $g , $bfin);
									//if($find == '@PHP') { echo $find, ' > ' , $k , '==' ,$l, ' :: ',$c, PHP_EOL; }
									if($k == $l) { break; }
									$c = strpos($data,$bfin, $c+1) + 1; //execusion 0.0077269077301025 secondes
									//$c++; // execusion 0.0082950592041016 secondes
								}
								

								
								
								$m = trim( substr($data,($bs + 1),( ($c - $bs) -2)) );
								$rpl = "$debx$deb$m$fin$endx";
							
								/*if($find == '@PHP') {
									
										//echo '<br/> >>>', $m, '<<< <br/>', PHP_EOL;
									
								}*/
							
							$data = substr_replace($data, $rpl, $b, (($c - $b)) );
							
							// expérimental
							//$b += strlen($rpl);
							$b = $bs + 1;

				
				} else { return false; }

			}

	}
	
}

?>
