<?php 
/*
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
	
	PRIVATE $_CAT = CAT; /// <--------- DEFINE CAT
	PRIVATE $_CACHE = CACHE; /// <----- DEFINE CACHE
	
	
	// on récupére que par get_data mais on peu initialisé par le constructeur ou par set_data
	public function __construct( $filename=false ) {   
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
			['bop',['@include'		,0,'include_once( [#1]',');','<?php ',' ?>','(',')','', '', '[#1]','']],

			['bop',['@structure'	,0]],
			['bop',['@getfile'		,0,'', '','<?php ',' ?>','(',')','','','[#1]','',FALSE]],
			['bop',['@getsegment'	,0]],
			['bop',['@phpsegment'	,0]],
			
			['bof',['@setsegment'	,0,'<!--SEGMENT:', '','(',')','','']],
			['bsp',['@endsegment' 	,'-->','','']],

			/*  
			['bof',['@setvar'		,0]], // initialisé des variables
			['bof',['@unsetvar'		,0,'unset(',');']], // initialisé des variables
			['bop',['@use'			,0,'',');','<?php ',' ?>',':',')']], // utilisé une function 
			['bop',['@instancy'		,0,'[#1] = new ', '[#2];','<?php ',' ?>','(',')','{','}','[#1]','[#2]',FALSE]], // instancier l'objet
			['bof',['@object'		,0,'', ';']], // utilisé un objet
			['bop',['@setclass'		,0,'class [#1] ', '{[#2]}','<?php ',' ?>','(',')','{','}','[#1]','[#2]',FALSE]],	// créé des class	
			*/	
		
			['bsp',['@dowhile'		,"do{ echo <<<END\r\n",'<?php ','']],
			['bof',['@whiledo'		,0,"\r\nEND;\r\n}while(", ');','(',')','']],
			['bop',['@dow'			,0,'do{', '[#2]}while([#1]);']],	

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
			
			['bop',['@html'			,0,'<html [#1]>','[#2]</html>', '','']],			
			['bof',['@head'			,0,'<head>','</head>', '{', '}', '','']],			
			['bof',['@title'		,0,'<title>','</title>', '(', ')', '','']],
			['bof',['@meta'			,0,'<meta ', '>', '(', ')', '','']],
			['bof',['@link'			,0,'<link ', '>', '(', ')', '','']],
			['bof',['@filecss'		,0,'<link rel="stylesheet" type="text/css" href=', '>', '(', ')', '','']],
			['bof',['@script+'		,0,'<script ', '></script>', '(', ')', '','']],
			['bof',['@script'		,0,'<script src=', '></script>', '(', ')', '','']],
			['bof',['@style+'		,0,'', '', '{', '}', '','']], // Grouping all @style+ to the first @style+
			['bof',['@style'		,0,'<style>', '</style>', '{', '}', '','']],
			['bop',['@body'			,0,'<body [#1]>','[#2]</body>', '','']],
	

			
			['bop',['@blockquote'	,0,'<blockquote [#1]>','[#2]</blockquote>', '','']],
			['bop',['@figcaption'	,0,'<figcaption [#1]>','[#2]</figcaption>', '','']],
			
			['bop',['@colgroup'		,0,'<colgroup [#1]>','[#2]</colgroup>', '','']],
			['bop',['@datalist'		,0,'<datalist [#1]>','[#2]</datalist>', '','']],
			['bop',['@fieldset'		,0,'<fieldset [#1]>','[#2]</fieldset>', '','']],
			['bop',['@noscript'		,0,'<noscript [#1]>','[#2]</noscript>', '','']],
			['bop',['@optgroup'		,0,'<optgroup [#1]>','[#2]</optgroup>', '','']],
			['bop',['@progress'		,0,'<progress [#1]>','[#2]</progress>', '','']],
			['bop',['@textarea'		,0,'<textarea [#1]>','[#2]</textarea>', '','']],
			
			['bop',['@!DOCTYPE'		,0,'<!DOCTYPE  [#1]','>', '','','(',')','', '', '[#1]','',]],
			
			['bop',['@address'		,0,'<address [#1]>','[#2]</address>', '','']],
			['bop',['@article'		,0,'<article [#1]>','[#2]</article>', '','']],
			['bop',['@caption'		,0,'<caption [#1]>','[#2]</caption>', '','']],
			['bop',['@command'		,0,'<command [#1]>','[#2]</command>', '','']],
			['bop',['@details'		,0,'<details [#1]>','[#2]</details>', '','']],
			['bop',['@section'		,0,'<section [#1]>','[#2]</section>', '','']],
			['bop',['@summary'		,0,'<summary [#1]>','[#2]</summary>', '','']],
			
			['bop',['@button'		,0,'<button [#1]>','[#2]</button>', '','']],
			['bop',['@canvas'		,0,'<canvas [#1]>','[#2]</canvas>', '','']],
			['bop',['@figure'		,0,'<figure [#1]>','[#2]</figure>', '','']],
			['bop',['@footer'		,0,'<footer [#1]>','[#2]</footer>', '','']],
			['bop',['@header'		,0,'<header [#1]>','[#2]</header>', '','']],
			['bop',['@hgroup'		,0,'<hgroup [#1]>','[#2]</hgroup>', '','']],
			['bop',['@iframe'		,0,'<iframe [#1]>','[#2]</iframe>', '','']],
			['bop',['@keygen'		,0,'<keygen [#1]>','[#2]</keygen>', '','']],
			['bop',['@legend'		,0,'<legend [#1]>','[#2]</legend>', '','']],
			['bop',['@object'		,0,'<object [#1]>','[#2]</object>', '','']],
			['bop',['@option'		,0,'<option [#1]>','[#2]</option>', '','']],
			['bop',['@output'		,0,'<output [#1]>','[#2]</output>', '','']],
			['bop',['@select'		,0,'<select [#1]>','[#2]</select>', '','']],
			['bop',['@source'		,0,'<source [#1]>','[#2]</source>', '','']],
			['bop',['@strong'		,0,'<strong [#1]>','[#2]</strong>', '','']],
			['bop',['@center'		,0,'<center [#1]>','[#2]</center>', '','']],
			
			['bop',['@aside'		,0,'<aside [#1]>','[#2]</aside>', '','']],
			['bop',['@audio'		,0,'<audio [#1]>','[#2]</audio>', '','']],
			['bop',['@embed'		,0,'<embed [#1]>','[#2]</embed>', '','']],
			['bop',['@input'		,0,'<input [#1]' ,'>', '','','(',')','', '', '[#1]','',]],
			['bop',['@label'		,0,'<label [#1]>','[#2]</label>', '','']],
			['bop',['@meter'		,0,'<meter [#1]>','[#2]</meter>', '','']],
			['bop',['@param'		,0,'<param [#1]>','[#2]</param>', '','']],
			['bop',['@small'		,0,'<small [#1]>','[#2]</small>', '','']],
			['bop',['@table'		,0,'<table [#1]>','[#2]</table>', '','']],
			['bop',['@tbody'		,0,'<tbody [#1]>','[#2]</tbody>', '','']],
			['bop',['@tfoot'		,0,'<tfoot [#1]>','[#2]</tfoot>', '','']],
			['bop',['@thead'		,0,'<thead [#1]>','[#2]</thead>', '','']],
			['bop',['@title'		,0,'<title [#1]>','[#2]</title>', '','']],
			['bop',['@track'		,0,'<track [#1]>','[#2]</track>', '','']],
			['bop',['@video'		,0,'<video [#1]>','[#2]</video>', '','']],
			
			
			
			['bop',['@abbr'			,0,'<abbr [#1]>','[#2]</abbr>', '','']],
			['bop',['@area'			,0,'<area [#1]>','[#2]</area>', '','']],
			['bop',['@base'			,0,'<base [#1]>','[#2]</base>', '','']],
			['bop',['@cite'			,0,'<cite [#1]>','[#2]</cite>', '','']],
			['bop',['@code'			,0,'<code [#1]>','[#2]</code>', '','']],
			['bop',['@form'			,0,'<form [#1]>','[#2]</form>', '','']],
			['bop',['@mark'			,0,'<mark [#1]>','[#2]</mark>', '','']],
			['bop',['@math'			,0,'<math [#1]>','[#2]</math>', '','']],
			['bop',['@menu'			,0,'<menu [#1]>','[#2]</menu>', '','']],
			['bop',['@ruby'			,0,'<ruby [#1]>','[#2]</ruby>', '','']],
			['bop',['@samp'			,0,'<samp [#1]>','[#2]</samp>', '','']],
			['bop',['@span'			,0,'<span [#1]>','[#2]</span>', '','']],
			['bop',['@time'			,0,'<time [#1]>','[#2]</time>', '','']],
			
			['bop',['@bdo'			,0,'<bdo [#1]>','[#2]</bdo>', '','']],
			['bop',['@col'			,0,'<col [#1]>','[#2]</col>', '','']],
			['bop',['@del'			,0,'<del [#1]>','[#2]</del>', '','']],
			['bop',['@dfn'			,0,'<dfn [#1]>','[#2]</dfn>', '','']],
			['bop',['@div'			,0,'<div [#1]>','[#2]</div>', '','']],
			['bop',['@img'			,0,'<img [#1]>','[#2]</img>', '','']],
			['bop',['@ins'			,0,'<ins [#1]>','[#2]</ins>', '','']],
			['bop',['@kbd'			,0,'<kbd [#1]>','[#2]</kbd>', '','']],
			['bop',['@map'			,0,'<map [#1]>','[#2]</map>', '','']],
			['bop',['@nav'			,0,'<nav [#1]>','[#2]</nav>', '','']],
			['bop',['@pre'			,0,'<pre [#1]>','[#2]</pre>', '','']],
			['bop',['@sub'			,0,'<sub [#1]>','[#2]</sub>', '','']],
			['bop',['@sup'			,0,'<sup [#1]>','[#2]</sup>', '','']],
			['bop',['@svg'			,0,'<svg [#1]>','[#2]</svg>', '','']],
			['bop',['@var'			,0,'<var [#1]>','[#2]</var>', '','']],
			['bop',['@wbr'			,0,'<wbr [#1]>','[#2]</wbr>', '','']],
			
			['bsp',['@br'			,'<br></br>','','']],
			
			['bop',['@dd'			,0,'<dd [#1]>','[#2]</dd>', '','']],
			['bop',['@dl'			,0,'<dl [#1]>','[#2]</dl>', '','']],
			['bop',['@dt'			,0,'<dt [#1]>','[#2]</dt>', '','']],
			['bop',['@em'			,0,'<em [#1]>','[#2]</em>', '','']],
			['bop',['@h1'			,0,'<h1 [#1]>','[#2]</h1>', '','']],
			['bop',['@h2'			,0,'<h2 [#1]>','[#2]</h2>', '','']],
			['bop',['@h3'			,0,'<h3 [#1]>','[#2]</h3>', '','']],
			['bop',['@h4'			,0,'<h4 [#1]>','[#2]</h4>', '','']],
			['bop',['@h5'			,0,'<h5 [#1]>','[#2]</h5>', '','']],
			['bop',['@h6'			,0,'<h6 [#1]>','[#2]</h6>', '','']],
			
			['bop',['@hr'			,0,'<hr [#1]>','</hr>', '','', '(',')','', '', '[#1]','',]],
			
			['bop',['@li'			,0,'<li [#1]>','[#2]</li>', '','']],
			['bop',['@ol'			,0,'<ol [#1]>','[#2]</ol>', '','']],
			['bop',['@rp'			,0,'<rp [#1]>','[#2]</rp>', '','']],
			['bop',['@rt'			,0,'<rt [#1]>','[#2]</rt>', '','']],
			['bop',['@td'			,0,'<td [#1]>','[#2]</td>', '','']],
			['bop',['@th'			,0,'<th [#1]>','[#2]</th>', '','']],
			['bop',['@tr'			,0,'<tr [#1]>','[#2]</tr>', '','']],
			['bop',['@ul'			,0,'<ul [#1]>','[#2]</ul>', '','']],
			
			['bop',['@a'			,0,'<a [#1]>','[#2]</a>', '','']],
			['bop',['@b'			,0,'<b [#1]>','[#2]</b>', '','']],
			['bop',['@i'			,0,'<i [#1]>','[#2]</i>', '','']],
			['bop',['@p'			,0,'<p [#1]>','[#2]</p>', '','']],
			['bop',['@q'			,0,'<q [#1]>','[#2]</q>', '','']],
			
			['bof',['@:'			,0,'',';','{','}']], // affiche son resulta
			['bop',['@.'			,0,'','', '','','','','','']], // comment cat invisible
			['bop',['@?'			,0,'','', '','','<','>','', '', '[#1]','']], // comment cat invisible
			['bof',['@!'			,0,'','','<','>','<!-- ',' -->']], // comment html
			['bof',['@*'			,0,'/* $','*/','<','>']], // comment php
			['bof',['@>'			,0,'echo ',';','{','}']], // affiche son resulta
			['bof',['@'				,0,'echo $',';','{','}']], // affiche son resulta
			
			
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
		$hashdata = hash('adler32',$data);
		$filehash = @file_get_contents( $this->_CACHE . $this->_filename . '.hash');
		if($hashdata = $filehash or (strlen(file_get_contents(CACHE . $this->_filename . '.php')) == 0) ) {
		
			$fp = fopen( $this->_CACHE . $this->_filename . '.hash', 'w');
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
			$time = $time_end - $time_start;
		}
		
		
		
		
		
		
		if($this->_optimised) {
		
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
				
				
				
		}




		if($timetest) {echo "execusion $time secondes\n";}
		$this->_final_page = $data;
			
		
	}
	

	
	
	private function bsp($find,$replace,$debx='<?php ',$endx=' ?>'){
		//var_dump([$find,$replace,$debx,$endx]);
		global $data;
		$data = str_replace($find,"$debx$replace$endx", $data);
	}
	
	private function bop($find,$fdb,$deb='', $fin='',$debx='<?php ',$endx=' ?>',$bdeb1='(',$bfin1=')',$bdeb2='{',$bfin2='}',$masque1='[#1]',$masque2='[#2]',$exp=TRUE,$b=0, $mex=''){
			
			global $data;
			
			$dd = strlen($data);
			$s = strlen($find);
			while(--$fdb >= 0) {
				$b = strpos($data,$find,$b);
				$bs = ($b + $s);
				if($b !== false ) {
					
					
					 // cas 0  @XXXX
					 // cas 1  @XXXX()
					 // cas 2  @XXXX ()
					 
							
							$nodeb1 = true;
							if(($data[$bs] . $data[$bs + 1]) == ($bdeb1 . $bfin1)){ // si = @XXXX()	
								$c = $bs + 2;
								$nodeb1 = true;
							}
							elseif(($data[$bs] . $data[$bs + 1] . $data[$bs+2]) == (chr(32) . $bdeb1 . $bfin1)){ // si = @XXXX ()
								$c = $bs + 3;
								$nodeb1 = true;
							}
							elseif( $data[$bs] == $bdeb1 && $data[$bs+1] != $bfin1 ){ // si @XXXX(*
								
								$c = strpos($data,$bfin1,$bs);
								$nodeb1 = false; 
							}
							elseif($data[$bs] . $data[$bs + 1] == (chr(32) .$bdeb1) && $data[$bs+2] != $bfin1){ //si @XXXX (* 
								$bs = $bs + 1;
								$c = strpos($data,$bfin1,$bs);
								$nodeb1 = false; 
							}
							else{ 
								$c = $bs;
								$nodeb1 = true;
							}
					
					
							
							if($nodeb1 === false){
							
							$c = ($data[$c] == $data[$bs+1]) ? ($bs+2) : $c-=1 ;
							
										for($j=0; $j < $dd ;$j++) {
											$g = substr($data,$bs,($c - $bs));
											$k = substr_count( $g , $bdeb1);
											$l = substr_count( $g , $bfin1);
											if($k == $l) { break; }
											$c = strpos($data,$bfin1, $c+1) + 1;
											
										}
										
									$mex = trim( substr($data,($bs + 1),( $c - $bs) - 2) );
							}
					
						/*
								$nodeb1 = false;
								// si les parenthéses existes 
								if( 	$data[$bs] == $bdeb1 && $data[$bs + 1] == $bfin1 &&  // si y a ()
										($bdeb2 == '' || $bdeb2 == false) 
									) {
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
								
								} */
								
								
								
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

									if( ($masque2) && ($exp === false) && ($nodeb1 === false)) { $m2 = "echo <<<END2 \r\n$m2\r\nEND2;\r\n"; }

								} else { $e = $c; }



								// si masque 1 existe
								if($masque1) {
									
									$debo = $deb;
									$fino = $fin;
									
									// si $deb à une exception  précise
									switch($find) { // si masque 1 est true

										case '@getfile':
										
											$debx = "\r\n<!-- start invoc file : $mex -->\r\n" ;
											$debo = file_get_contents( $this->_CAT . trim($mex,'\'') );
											$endx = "\r\n<!-- END invoc file : $mex -->\r\n";
											$fino = '';
											$mex  = '';
											
										break;
										
										case '@structure':
												$debx = "\r\n<!-- start import file : $mex -->\r\n";
												$endx = "\r\n<!-- END import file : $mex -->\r\n";
												$debf = file_get_contents( $this->_CAT . trim($mex,'\''));
												$mxxx = array_filter( explode('|',$m2), function($v){ return trim($v); } );

												//var_dump($mxxx);
												foreach($mxxx as $k => $l) {
													$l = trim($l);
													
													if(strpos($l, '~') === false) { 
														$ddt = (file_exists( $this->_CAT . trim($l,'\'')) ? file_get_contents( $this->_CAT . trim($l,'\'')) : trim($l,'\'') ); 
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
										
											$debf = file_get_contents( $this->_CAT . trim($mex,'\''));
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

	
	private function bof($find,$fdb,$deb='', $fin='',$bdeb='(',$bfin=')',$debx='<?php ',$endx=' ?>',$b=0,$fbmax=0,$rpl=''){
			global $data, $style_p, $jsr_p, $js_p;
			
			$d = strlen($data);
			$s = strlen($find);
			//$i=1;	
			$fbmax = $fdb - 1;
			while(--$fdb > -1) { 
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
								//$rpl = "$debx$deb$m$fin$endx";
							
								/*if($find == '@PHP') {
									
										//echo '<br/> >>>', $m, '<<< <br/>', PHP_EOL;
									
								}*/
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
							
							
							// expérimental
							//$b += strlen($rpl);
							$b = $bs + 1;

				
				} else { return false; }

			}

	}
	
}

?>
