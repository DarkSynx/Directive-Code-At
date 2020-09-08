<?php 
/*
 Juste for test new optimisation
 NOT USE THIS VERSION
 
 !!!   WARNING   !!!
 !!! USE PHP 7.4 !!!
 
 !!! INITIALISING CAT AND CACHE DEFINE !!!
 CAT is the starting folder to find your .cat .seg .tpl files.
 CACHE is the folder that allows to store the generated files

*/
if(!defined('CAT')){ DEFINE('CAT', ''); } /// <--------- DEFINE CAT
if(!defined('CACHE')){ DEFINE('CACHE', ''); } /// <----- DEFINE CACHE
/*
 DARKSYNX @ 2020
*/



class directive {
	
	private $_final_page;
	private $_data;
	private $_filename = '';
	private $_nocache = false;
	private $_style_plus = false;
	private $_jsr_plus = false;
	private $_js_plus = false;
	
	private $_optimised = true;
	private $_commentHTML = false;
	
	PRIVATE $_CAT = CAT; /// <--------- DEFINE CAT
	PRIVATE $_CACHE = CACHE; /// <----- DEFINE CACHE
	
	
	// on récupére que par get_data mais on peu initialisé par le constructeur ou par set_data
	public function __construct( $filename=false, $optimised=TRUE, $commentHTML=FALSE ) {   
		
		$this->_optimised = $optimised;
		$this->_commentHTML = $commentHTML;
		
		if($filename){
			$this->set_page($filename);
			$this->set_data( $this->get_page() );
			}
	}
	public function set_page($filename) { $this->_filename = $filename; }
	public function get_page() { return file_get_contents( $this->_CAT . $this->_filename); }
	public function set_data($data) { $this->_data = $data; $this->gen(); }	
	public function get_data() { return $this->_final_page; }
	
	
	public function get_directive() { 
		$sortir = '';
		$fp = fopen( 'gen_directive.txt', 'w');
		$directive =  $this->gen(TRUE);
		//var_dump($directive);
		foreach($directive as $val) {
			
			$rplt1 = ''; $rplt2 = '';
			switch( $val[0] ){
				case 'bsp':
					$f = ['','','<?php ',' ?>','','','','','',''];
					foreach($val[1] as $k => $l) { $f[$k] = $l; }
				break;
				case 'bop':
					$f = ['',0,'', '','<?php ',' ?>','(',')','{','}'];
					
					foreach($val[1] as $k => $l) { $f[$k] = $l; }
					if($f[6] != '' && $f[7] != '') { $rplt1 = $f[6] . ' value 1 ' . $f[7]; }
					if($f[8] != '' && $f[9] != '') { $rplt2 = $f[8] . ' value 2 ' . $f[9]; }
					
				break;
				case 'bof':
					$f = ['',0,'', '','(',')','<?php ',' ?>','',''];
					foreach($val[1] as $k => $l) { $f[$k] = $l; }
					if($f[4] != '' && $f[5] != '') { $rplt1 = $f[4] . ' value 1 ' . $f[5]; }
				break;
			}
			
			

			fwrite($fp, $f[0]  . $rplt1 . $rplt2  . PHP_EOL  );
		}
		fclose($fp);
		return $sortir;
	}
	
	public function cache_it() {
		if($this->_nocache) {
			$fp = fopen( $this->_CACHE . $this->_filename . '.php', 'w');
			fwrite($fp, $this->get_data());
			fclose($fp);
		}
		return $this->_CACHE . $this->_filename . '.php';
	}
	
	private function gen($rerturn_tag=FALSE,$optimised=TRUE,$timetest=true) {
		
		global 	$data, $style_p, $jsr_p, $js_p;
		
		$style_p = $jsr_p = $js_p = '';
		
		$iner_var = array();
		$data = $this->_data;
		
		
		$tags = [
		
		
			// toujours au début get
			['bop',['@include'		,0,'include_once( %_M1_%',');','<?php ',' ?>','(',')','', '', '%_M1_%','']],

			['bop',['@structure'	,0]],
			['bop',['@getfile'		,0,'', '','<?php ',' ?>','(',')','','','%_M1_%','',FALSE]],
			['bop',['@getsegment'	,0]],
			['bop',['@phpsegment'	,0]],
			
			['bof',['@setsegment'	,0,'<!--SEGMENT:', '','(',')','','']],
			['bsp',['@endsegment' 	,'-->','','']],
		
			['bsp',['@dowhile'		,"do{ echo <<<END\r\n",'<?php ','']],
			['bof',['@whiledo'		,0,"\r\nEND;\r\n}while(", ');','(',')','']],
			['bop',['@dow'			,0,'do{', '%_M2_%}while(%_M1_%);']],	

			['bof',['@PHP'			,0,'','','{','}']], // affiche son resulta
			['bof',['@JSR'			,0,'','','{','}','<script>$( document ).ready(function(){','});</script>']], // affiche son resulta
			['bof',['@JS'			,0,'','','{','}','<script>','</script>']], // affiche son resulta
	
			
			['bof',['@JSR+'			,0,'','','{','}','','']], // Grouping all @JSR+ to the first @JSR+
			['bof',['@JS+'			,0,'','','{','}','','']], // Grouping all @JS+ to the first @JS+

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
			
			['bop',['@html'			,0,'<html %_M1_%>','%_M2_%</html>', '','']],			
			['bof',['@head'			,0,'<head>','</head>', '{', '}', '','']],			
			['bof',['@title'		,0,'<title>','</title>', '(', ')', '','']],
			['bof',['@meta'			,0,'<meta ', '>', '(', ')', '','']],
			['bof',['@link'			,0,'<link ', '>', '(', ')', '','']],
			['bof',['@filecss'		,0,'<link rel="stylesheet" type="text/css" href=', '>', '(', ')', '','']],
			['bof',['@script+'		,0,'<script ', '></script>', '(', ')', '','']],
			['bof',['@script'		,0,'<script src=', '></script>', '(', ')', '','']],
			['bof',['@style+'		,0,'', '', '{', '}', '','']], // Grouping all @style+ to the first @style+
			['bof',['@style'		,0,'<style>', '</style>', '{', '}', '','']],
			['bop',['@body'			,0,'<body %_M1_%>','%_M2_%</body>', '','']],
	

			
			['bop',['@blockquote'	,0,'<blockquote %_M1_%>','%_M2_%</blockquote>', '','']],
			['bop',['@figcaption'	,0,'<figcaption %_M1_%>','%_M2_%</figcaption>', '','']],
			
			['bop',['@colgroup'		,0,'<colgroup %_M1_%>','%_M2_%</colgroup>', '','']],
			['bop',['@datalist'		,0,'<datalist %_M1_%>','%_M2_%</datalist>', '','']],
			['bop',['@fieldset'		,0,'<fieldset %_M1_%>','%_M2_%</fieldset>', '','']],
			['bop',['@noscript'		,0,'<noscript %_M1_%>','%_M2_%</noscript>', '','']],
			['bop',['@optgroup'		,0,'<optgroup %_M1_%>','%_M2_%</optgroup>', '','']],
			['bop',['@progress'		,0,'<progress %_M1_%>','%_M2_%</progress>', '','']],
			['bop',['@textarea'		,0,'<textarea %_M1_%>','%_M2_%</textarea>', '','']],
			
			['bop',['@!DOCTYPE'		,0,'<!DOCTYPE  %_M1_%','>', '','','(',')','', '', '%_M1_%','',]],
			
			['bop',['@address'		,0,'<address %_M1_%>','%_M2_%</address>', '','']],
			['bop',['@article'		,0,'<article %_M1_%>','%_M2_%</article>', '','']],
			['bop',['@caption'		,0,'<caption %_M1_%>','%_M2_%</caption>', '','']],
			['bop',['@command'		,0,'<command %_M1_%>','%_M2_%</command>', '','']],
			['bop',['@details'		,0,'<details %_M1_%>','%_M2_%</details>', '','']],
			['bop',['@section'		,0,'<section %_M1_%>','%_M2_%</section>', '','']],
			['bop',['@summary'		,0,'<summary %_M1_%>','%_M2_%</summary>', '','']],
			
			['bop',['@button'		,0,'<button %_M1_%>','%_M2_%</button>', '','']],
			['bop',['@canvas'		,0,'<canvas %_M1_%>','%_M2_%</canvas>', '','']],
			['bop',['@figure'		,0,'<figure %_M1_%>','%_M2_%</figure>', '','']],
			['bop',['@footer'		,0,'<footer %_M1_%>','%_M2_%</footer>', '','']],
			['bop',['@header'		,0,'<header %_M1_%>','%_M2_%</header>', '','']],
			['bop',['@hgroup'		,0,'<hgroup %_M1_%>','%_M2_%</hgroup>', '','']],
			['bop',['@iframe'		,0,'<iframe %_M1_%>','%_M2_%</iframe>', '','']],
			['bop',['@keygen'		,0,'<keygen %_M1_%>','%_M2_%</keygen>', '','']],
			['bop',['@legend'		,0,'<legend %_M1_%>','%_M2_%</legend>', '','']],
			['bop',['@object'		,0,'<object %_M1_%>','%_M2_%</object>', '','']],
			['bop',['@option'		,0,'<option %_M1_%>','%_M2_%</option>', '','']],
			['bop',['@output'		,0,'<output %_M1_%>','%_M2_%</output>', '','']],
			['bop',['@select'		,0,'<select %_M1_%>','%_M2_%</select>', '','']],
			['bop',['@source'		,0,'<source %_M1_%>','%_M2_%</source>', '','']],
			['bop',['@strong'		,0,'<strong %_M1_%>','%_M2_%</strong>', '','']],
			['bop',['@center'		,0,'<center %_M1_%>','%_M2_%</center>', '','']],
			
			['bop',['@aside'		,0,'<aside %_M1_%>','%_M2_%</aside>', '','']],
			['bop',['@audio'		,0,'<audio %_M1_%>','%_M2_%</audio>', '','']],
			['bop',['@embed'		,0,'<embed %_M1_%>','%_M2_%</embed>', '','']],
			['bop',['@input'		,0,'<input %_M1_%' ,'>', '','','(',')','', '', '%_M1_%','',]],
			['bop',['@label'		,0,'<label %_M1_%>','%_M2_%</label>', '','']],
			['bop',['@meter'		,0,'<meter %_M1_%>','%_M2_%</meter>', '','']],
			['bop',['@param'		,0,'<param %_M1_%>','%_M2_%</param>', '','']],
			['bop',['@small'		,0,'<small %_M1_%>','%_M2_%</small>', '','']],
			['bop',['@table'		,0,'<table %_M1_%>','%_M2_%</table>', '','']],
			['bop',['@tbody'		,0,'<tbody %_M1_%>','%_M2_%</tbody>', '','']],
			['bop',['@tfoot'		,0,'<tfoot %_M1_%>','%_M2_%</tfoot>', '','']],
			['bop',['@thead'		,0,'<thead %_M1_%>','%_M2_%</thead>', '','']],
			['bop',['@title'		,0,'<title %_M1_%>','%_M2_%</title>', '','']],
			['bop',['@track'		,0,'<track %_M1_%>','%_M2_%</track>', '','']],
			['bop',['@video'		,0,'<video %_M1_%>','%_M2_%</video>', '','']],
			
			
			
			['bop',['@abbr'			,0,'<abbr %_M1_%>','%_M2_%</abbr>', '','']],
			['bop',['@area'			,0,'<area %_M1_%>','%_M2_%</area>', '','']],
			['bop',['@base'			,0,'<base %_M1_%>','%_M2_%</base>', '','']],
			['bop',['@cite'			,0,'<cite %_M1_%>','%_M2_%</cite>', '','']],
			['bop',['@code'			,0,'<code %_M1_%>','%_M2_%</code>', '','']],
			['bop',['@form'			,0,'<form %_M1_%>','%_M2_%</form>', '','']],
			['bop',['@mark'			,0,'<mark %_M1_%>','%_M2_%</mark>', '','']],
			['bop',['@math'			,0,'<math %_M1_%>','%_M2_%</math>', '','']],
			['bop',['@menu'			,0,'<menu %_M1_%>','%_M2_%</menu>', '','']],
			['bop',['@ruby'			,0,'<ruby %_M1_%>','%_M2_%</ruby>', '','']],
			['bop',['@samp'			,0,'<samp %_M1_%>','%_M2_%</samp>', '','']],
			['bop',['@span'			,0,'<span %_M1_%>','%_M2_%</span>', '','']],
			['bop',['@time'			,0,'<time %_M1_%>','%_M2_%</time>', '','']],
			
			['bop',['@bdo'			,0,'<bdo %_M1_%>','%_M2_%</bdo>', '','']],
			['bop',['@col'			,0,'<col %_M1_%>','%_M2_%</col>', '','']],
			['bop',['@del'			,0,'<del %_M1_%>','%_M2_%</del>', '','']],
			['bop',['@dfn'			,0,'<dfn %_M1_%>','%_M2_%</dfn>', '','']],
			['bop',['@div'			,0,'<div %_M1_%>','%_M2_%</div>', '','']],
			['bop',['@img'			,0,'<img %_M1_%>','%_M2_%</img>', '','']],
			['bop',['@ins'			,0,'<ins %_M1_%>','%_M2_%</ins>', '','']],
			['bop',['@kbd'			,0,'<kbd %_M1_%>','%_M2_%</kbd>', '','']],
			['bop',['@map'			,0,'<map %_M1_%>','%_M2_%</map>', '','']],
			['bop',['@nav'			,0,'<nav %_M1_%>','%_M2_%</nav>', '','']],
			['bop',['@pre'			,0,'<pre %_M1_%>','%_M2_%</pre>', '','']],
			['bop',['@sub'			,0,'<sub %_M1_%>','%_M2_%</sub>', '','']],
			['bop',['@sup'			,0,'<sup %_M1_%>','%_M2_%</sup>', '','']],
			['bop',['@svg'			,0,'<svg %_M1_%>','%_M2_%</svg>', '','']],
			['bop',['@var'			,0,'<var %_M1_%>','%_M2_%</var>', '','']],
			['bop',['@wbr'			,0,'<wbr %_M1_%>','%_M2_%</wbr>', '','']],
			
			['bsp',['@br'			,'<br></br>','','']],
			
			['bop',['@dd'			,0,'<dd %_M1_%>','%_M2_%</dd>', '','']],
			['bop',['@dl'			,0,'<dl %_M1_%>','%_M2_%</dl>', '','']],
			['bop',['@dt'			,0,'<dt %_M1_%>','%_M2_%</dt>', '','']],
			['bop',['@em'			,0,'<em %_M1_%>','%_M2_%</em>', '','']],
			['bop',['@h1'			,0,'<h1 %_M1_%>','%_M2_%</h1>', '','']],
			['bop',['@h2'			,0,'<h2 %_M1_%>','%_M2_%</h2>', '','']],
			['bop',['@h3'			,0,'<h3 %_M1_%>','%_M2_%</h3>', '','']],
			['bop',['@h4'			,0,'<h4 %_M1_%>','%_M2_%</h4>', '','']],
			['bop',['@h5'			,0,'<h5 %_M1_%>','%_M2_%</h5>', '','']],
			['bop',['@h6'			,0,'<h6 %_M1_%>','%_M2_%</h6>', '','']],
			
			['bop',['@hr'			,0,'<hr %_M1_%>','</hr>', '','', '(',')','', '', '%_M1_%','',]],
			
			['bop',['@li'			,0,'<li %_M1_%>','%_M2_%</li>', '','']],
			['bop',['@ol'			,0,'<ol %_M1_%>','%_M2_%</ol>', '','']],
			['bop',['@rp'			,0,'<rp %_M1_%>','%_M2_%</rp>', '','']],
			['bop',['@rt'			,0,'<rt %_M1_%>','%_M2_%</rt>', '','']],
			['bop',['@td'			,0,'<td %_M1_%>','%_M2_%</td>', '','']],
			['bop',['@th'			,0,'<th %_M1_%>','%_M2_%</th>', '','']],
			['bop',['@tr'			,0,'<tr %_M1_%>','%_M2_%</tr>', '','']],
			['bop',['@ul'			,0,'<ul %_M1_%>','%_M2_%</ul>', '','']],
			
			['bop',['@a'			,0,'<a %_M1_%>','%_M2_%</a>', '','']],
			['bop',['@b'			,0,'<b %_M1_%>','%_M2_%</b>', '','']],
			['bop',['@i'			,0,'<i %_M1_%>','%_M2_%</i>', '','']],
			['bop',['@p'			,0,'<p %_M1_%>','%_M2_%</p>', '','']],
			['bop',['@q'			,0,'<q %_M1_%>','%_M2_%</q>', '','']],
			
			['bof',['@:'			,0,'',';','{','}']], // affiche son resulta
			['bop',['@.'			,0,'','', '','','','','','']], // comment cat invisible
			['bop',['@?'			,0,'','', '','','<','>','', '', '%_M1_%','']], // comment cat invisible
			['bof',['@!'			,0,'','','<','>','<!-- ',' -->']], // comment html
			['bof',['@*'			,0,'\/* $','*\/','<','>']], // comment php
			['bof',['@>'			,0,'echo ',';','{','}']], // affiche son resulta
			//['bof',['@'				,0,'echo $',';','{','}']], // affiche son resulta
			
			
		];
		
		
		
		// préparation création d'un tableau de revers
		$tabloc = array();	
		foreach($tags as $k => $fnc) { $tabloc[$fnc[1][0]] = $k; }
		
		if($rerturn_tag) { return $tags; }
		
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
		$this->_nocache = true;
		//$this->_nocache = $this->hashvalidate($data);
		if(!$this->_nocache) {return $tabloc; }
		
		
		
	
		// on supprime les functions non présente dans data
		// et on prépare les variables du talbleau
		foreach($tags as $k => &$fnc) {				
				$ctme = substr_count($data, $fnc[1][0]);
				if( $ctme == 0 ) {
					unset( $tags[$k] );
				}
				else if($fnc[0] != 'bsp' ) {
						$fnc[1][1] = $ctme;	
				}
		}
		
		//var_dump($tags);
		
		if($timetest) { $time_start = microtime(true); }
			
			// on demarre la fabrication
			foreach($tags as &$fnc) {
				//$fnc[1][2] = &$data;
				call_user_func_array([$this,$fnc[0]],$fnc[1]);
			}
		
			/* RESTORE STYLE */
			if($this->_style_plus) {
				$data = str_replace('[#STYLE-LOAD-MASQUE-GROUPING]', '<style>' . $style_p . '</style>', $data);
			}
			if($this->_jsr_plus) { // '<script>$( document ).ready(function(){','});</script>'
				$data = str_replace('[#JSR-LOAD-MASQUE-GROUPING]', '<script>$( document ).ready(function(){' . $jsr_p . '});</script>', $data);
			}
			if($this->_js_plus) {
				$data = str_replace('[#JS-LOAD-MASQUE-GROUPING]', '<script>' . $js_p . '</script>', $data);
			}
				
				
				
		if($timetest) {
			$time_end = microtime(true);
			$time = ($time_end - $time_start);
		}
		

		if($this->_optimised) { 
			$this->optimised($data); 
		}


		if($timetest) {echo "execusion $time secondes\n";}
		$this->_final_page = $data;
		
	}
	
	private function hashvalidate(&$data){
		
		$hashdata = hash('fnv1a64',$data, true);
		$filehash = @file_get_contents( $this->_CACHE . $this->_filename . '.hash');
		if($hashdata != $filehash or (strlen(file_get_contents(CACHE . $this->_filename . '.php')) == 0) ) {
		
			$fp = fopen( $this->_CACHE . $this->_filename . '.hash', 'w');
			fwrite($fp, $hashdata);
			fclose($fp);
			return true;
			
		} 
		return false;
		
	}
	
	private function optimised(&$data) {
		
		$data = str_replace(
		["\t\r\nt\r\n",		"\t\r\n", 	"\r\n\r\n\r\n",		"\r\n\r\n",		"\r\n ?>",		' >', "\r\n\t\r\n", "\r\n\t\t\r\n"], 
		["\r\n",			"\r\n",		"\r\n",				"\r\n", 		"\r\n?>",		'>',  "\r\n", "\r\n"], $data);

				
				
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
				
		return $data;		
		
	}

	
	
	private function bsp($find,$replace,$debx='<?php ',$endx=' ?>'): bool {
		//var_dump([$find,$replace,$debx,$endx]);
		global $data;
		$data = str_replace($find,"$debx$replace$endx", $data);
		return true;
	}
	

	private function bof($find,$fdb,$deb='', $fin='',$bdeb='(',$bfin=')',$debx='<?php ',$endx=' ?>',$b=0,$fbmax=0,$rpl=''): bool {
			
			global $data, $style_p, $jsr_p, $js_p;
			
			$d = strlen($data);
			$s = strlen($find);
			//$i=1;	
			$fbmax = $fdb - 1;
			do { 
				$b = strpos($data,$find,$b);
				$bs = ($b + $s);
				if($b !== false ) {

							list($m,$c) = $this->shearch( $bs, $d , $bdeb, $bfin); 

							switch($find) {
								case '@style+':
									//[#STYLE-LOAD-MASQUE]
									if($fbmax == $fdb) { $this->_style_plus = true; $deb='[#STYLE-LOAD-MASQUE-GROUPING]'; }
									$style_p .= $m;
									$rpl = "$debx$deb$fin$endx";
								break;
								case '@JSR+':
									//[#STYLE-LOAD-MASQUE]
									if($fbmax == $fdb) { $this->_jsr_plus = true; $deb='[#JSR-LOAD-MASQUE-GROUPING]'; }
									$jsr_p .= $m;
									$rpl = "$debx$deb$fin$endx";
								break;
								case '@JS+':
									//[#STYLE-LOAD-MASQUE]
									if($fbmax == $fdb) { $this->_js_plus = true; $deb='[#JS-LOAD-MASQUE-GROUPING]'; }
									$js_p .= $m;
									$rpl = "$debx$deb$fin$endx";
								break;
								default:
								
								$rpl = "$debx$deb$m$fin$endx";
								
							}

							$data = substr_replace($data, $rpl, $b, (($c - $b)) );

							$b = $bs + 1;

				
				} else { return false; }

			} while(--$fdb > 0);
		return TRUE;
	}
	
	
	private	function shearch(  $bs, $dd, $bdeb1, $bfin1, $mex='', $sens=0): array {
				global $data;						
				$bx = $bs;
				
				do {
					if( $data[ $bs + $sens ] == $bdeb1 ){ 
						$bs += $sens; 
						$dp = $dd - $bs;
						$c = strpos($data,$bfin1,$bs);
							do {												
								$g = substr($data,$bs,($c - $bs));												
								if( substr_count( $g , $bdeb1) == substr_count( $g , $bfin1) ) { break; }
								$c = strpos($data,$bfin1, $c) + 1;	
							} while( --$dp > 0 )	;
							$mex = trim( substr($data,($bs + 1),( $c - $bs) - 2) );
							return [$mex,$c];
					}
				} while( ++$sens < 1 );

			return [$mex,$bx];					
		}
	
	
	private function bop($find='',$fdb=0,$deb='', $fin='',$debx='<?php ',$endx=' ?>',$bdeb1='(',$bfin1=')',$bdeb2='{',$bfin2='}',$masque1='%_M1_%',$masque2='%_M2_%',$exp=TRUE,$b=0, $m2='', $m3='', $c=0): bool 
	{		
			global $data, $segment;
			

			$s 		= 	strlen($find);

			do {
				
				$rpl 	= 	'';
				$m3		=	'';
				$m2		=	'';
				$m1		= 	'';
				$c		=	0;	
				$dd 	= 	strlen($data);

				$b = strpos($data,$find,$b);
				$bs = ( ($b ? $b : 0) + $s);
				if($b !== false ) {
					
					//[]
					list($m1,$bs) = $this->shearch( $bs, $dd , '[', ']'); 
					$node1 = ($m1 == '' ? false : true);
					
					//()
					list($m2,$bs) = $this->shearch( $bs, $dd , $bdeb1, $bfin1); 
					
					//{}
					list($m3,$bs) = $this->shearch(  $bs,  $dd , $bdeb2, $bfin2);
					
					//if( ($masque2) && ($exp === false) && ($m3 == '')) { $m3 = "echo <<<END2 \r\n$m3\r\nEND2;\r\n"; }

	
							list($rpl,$debx,$debo,$fino,$endx,$m2,$m3,$dx) = ($masque1 ? $this->masque_one($find,$deb,$fin,$debx,$endx,$m2,$m3,$masque1,$masque2,$segment) : ["$debx$deb$m2$fin$endx",$debx,'','',$endx,$m2,$m3,1]) ;
							$rpl = ($node1 ? $this->node_one($rpl,$dx,$m1,$m2,$m3,$masque2,$masque1,$debx,$debo,$fino,$endx,$deb,$fin) : $rpl);
								

							$data = substr_replace($data, $rpl, $b, ($bs - $b) );
							
							$b = $bs + 1;

				} else { return false; }

			} while( --$fdb > 0);
		return true;
	}

	private function masque_one($find,$debo,$fino,$debx,$endx,$m2,$m3,$masque1,$masque2,$segment){


									// si $deb à une exception  précise
									switch($find) { // si masque 1 est true
										case '@getfile':
											$debx = $this->_commentHTML ? "\r\n<!-- start invoc file : $m2 -->\r\n" : ''  ;
											$endx = $this->_commentHTML ? "\r\n<!-- END invoc file : $m2 -->\r\n" : '';
											$debo = file_get_contents( $this->_CAT . trim($m2,'\'') );
											$fino = '';
											$m2  = '';
										break;
										case '@structure':
												$debx = $this->_commentHTML ? "\r\n<!-- start import file : $m2 -->\r\n" : '';
												$endx = $this->_commentHTML ? "\r\n<!-- END import file : $m2 -->\r\n" : '';
												$debf = file_get_contents( $this->_CAT . trim($m2,'\''));
												$mxxx = array_filter( explode('|',$m3), function($v){ return trim($v); } );

												//var_dump($mxxx);
												foreach($mxxx as $l) {
													$l = trim($l);
													if(strpos($l, '~') === false) { 
														$ddt = (file_exists( $this->_CAT . trim($l,'\'')) ? file_get_contents( $this->_CAT . trim($l,'\'')) : trim($l,'\'') ); 
													}
													else { 
														$gll = explode('~',$l);
														$ddt = trim($gll[1],'\'');
														$l = $gll[0];
													}
													$debf = str_replace('{{'.trim($l,'\'').'}}', $ddt, $debf);
												}
												$debo = $debf;
												$fino = ''; 
												$m2  = '';
										break;
										case '@getsegment':
												$debx = $this->_commentHTML ? "\r\n<!-- start import:segment $m3 to file : $m2 -->\r\n" : '';
												$endx = $this->_commentHTML ? "\r\n<!-- END import:segment $m3 to file : $m2 -->\r\n" : '';
												$debf = file_get_contents( $this->_CAT . trim($m2,'\''));
												$tds  = strlen("@setsegment($m3)");
												$ddms = strpos($debf,"@setsegment($m3)",0);
												$fdms = strpos($debf,'@endsegment',$ddms+1);
												$debo = substr($debf,($ddms + $tds),($fdms - ($ddms + $tds)));
												$fino = ''; 
												$m2  = '';												
										break;
										case '@phpsegment':	
												$debx = '<?php ';
												$endx = ' ?>';
												$debf = file_get_contents( $this->_CAT . trim($m2,'\''));
												$tds  = strlen("@setsegment($m3)");
												$ddms = strpos($debf,"@setsegment($m3)",0);
												$fdms = strpos($debf,'@endsegment',$ddms+1);
												$debo = substr($debf,($ddms + $tds),($fdms - ($ddms + $tds)));
												$fino = ''; 
												$m2  = '';
										break;
										case '@loadsegment':	
												$debx = '';
												$endx = '';
												$debf = file_get_contents( $this->_CAT . trim($m2,'\''));
												$tds  = strlen("@setsegment($m3)");
												$ddms = strpos($debf,"@setsegment($m3)",0);
												$fdms = strpos($debf,'@endsegment',$ddms+1);
												$segment[$m3] = substr($debf,($ddms + $tds),($fdms - ($ddms + $tds)));
												$debo = '';
												$fino = ''; 
												$m2  = '';	
										break;
									}
									
									$rpl = "$debx$debo$fino$endx";
									$rpl = ($masque2 ? str_replace($masque2,$m3, str_replace($masque1,$m2,$rpl) ) : str_replace($masque1,$m2,$rpl));
									
									return [$rpl,$debx,$debo,$fino,$endx,$m2,$m3,0];
	
	}
	
	private function node_one($rpl,$dx,$m1,$m2,$m3,$masque2,$masque1,$debx,$debo,$fino,$endx,$deb,$fin) {
									
									switch($m1[0]){
									case '&':
										$m1 = substr($m1,1);
										$rpl = "<a href='#$m1'>" . $rpl . '</a>';
									break;
									case '#': // Add ID
										
									break;
									case '.': // add class
									
									break;
									case '+': // functions spé
										$m1 = substr($m1,1);
										switch(substr($m1,0,4)) {
											case 'Flex':
												
												if($bdeb1) {
													
													var_dump($m2);
													preg_match('/style=("|\')([^("|\')]*)("|\')/', $m2,$xstyle);
													preg_match('/class=("|\')([^("|\')]*)("|\')/', $m2,$xclass);
													var_dump($xstyle); // 0 = all string and 2 = in string

													$rez = false;
													
													switch(true) {
														case ($g = strpos($m1,',')):
															if(count($xstyle) == 0){ $m2 .= ' style=\'[%tagreplace]\' ';
																	if($dx == 1) {$rpl = "$debx$deb$m2$fin$endx";}
																	else {
																		$rpl = "$debx$debo$fino$endx";
																		$rpl = ($masque2 ? str_replace($masque2,$m3,str_replace($masque1,$m2,$rpl)) : str_replace($masque1,$m2,$rpl));
																	}
																	$xstyle = ['style=\'[%tagreplace]\'','\'',''];
															}
															$lstx = explode(',',substr($m1,$g+1));
															$flex = ['flex-direction:' , 'flex-wrap:' , 'justify-content:', 'align-items:' , 'align-content:' ];
															$new = '';
															foreach($lstx as $k => $l){
																$new .= $flex[$k] . $l . ';';
															}
															//var_dump($rpl);
															//echo PHP_EOL, PHP_EOL;
															$xtp = 'style=';
															$d=$xstyle[1];
															$rec=$xstyle[2];
															$rch=$xstyle[0];
															var_dump($rpl);
															//sleep(1000);
														break;
														case ($g = strpos($m1,'=')):
															if(count($xstyle) == 0){ $m2 .= ' style=\'[%tagreplace]\' ';
																	if($dx == 1) {$rpl = "$debx$deb$m2$fin$endx";}
																	else {
																		$rpl = "$debx$debo$fino$endx";
																		$rpl = ($masque2 ? str_replace($masque2,$m3,str_replace($masque1,$m2,$rpl)) : str_replace($masque1,$m2,$rpl));
																	}
																	$xstyle = ['style=\'[%tagreplace]\'','\'',''];
															}
															$new = substr($m1,$g+1);
															$xtp = 'style=';
															$d=$xstyle[1];
															$rec=$xstyle[2];
															$rch=$xstyle[0];
															var_dump($rpl);
														break;
														case ($g = strpos($m1,'.')):
															if(count($xclass) == 0) { $m2 .= ' class=\'[%tagreplace]\' '; 
																	if($dx == 1) {$rpl = "$debx$deb$m2$fin$endx";}
																	else {
																		  $rpl = "$debx$debo$fino$endx";
																		  $rpl = ($masque2 ? str_replace($masque2,$m3,str_replace($masque1,$m2,$rpl)) : str_replace($masque1,$m2,$rpl));
																	}
																	$xclass = ['class=\'[%tagreplace]\'','\'',''];
															}
															$lstx = substr($m1,$g+1);
															
															$xtp = 'class=';
															$d=$xclass[1];
															$rec=$xclass[2];
															$rch=$xclass[0];
															//var_dump($rpl);sleep(1000);
														break;
														default:
															if(count($xstyle) == 0){ $m2 .= ' style=\'[%tagreplace]\' ';
																	if($dx == 1) {$rpl = "$debx$deb$m2$fin$endx";}
																	else {
																		$rpl = "$debx$debo$fino$endx";
																		$rpl = ($masque2 ? str_replace($masque2,$m3,str_replace($masque1,$m2,$rpl)) : str_replace($masque1,$m2,$rpl));
																	}
																	$xstyle = ['style=\'[%tagreplace]\'','\'',''];
															}
															$new = 'display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: flex-start; align-items: stretch; align-content: stretch;';
															$xtp = 'style=';
															$d=$xstyle[1];
															$rec=$xstyle[2];
															$rch=$xstyle[0];
															//$rpl = str_replace($xstyle[0],"style={$xstyle[1]}{$xstyle[2]}{$lstx}{$xstyle[1]}", $rpl);
															var_dump($rpl);
															//sleep(1000);
													}
													$rpl = str_replace($rch,"{$xtp}{$d}{$rec}{$new}{$d}", $rpl);
											

												}
											break;
											case 'List':
											 // gen list
											break;
										}
									break;
									
									default:
										if( ($g = strpos($m1,',')) !== false ) {
											list($seg,$tag) = explode(',',$m1);
											$rpl = str_replace($tag,$rpl,$segment[$seg]);
										} else {
											$rpl = "<?php if($m1): ?>" . $rpl . '<?php endif; ?>';
										}
									}		
		
		return $rpl;
	}


	
}

?>
