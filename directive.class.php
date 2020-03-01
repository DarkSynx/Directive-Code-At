<?php 
/*
 USE 7.4
*/
class directive {
	
	private $_final_page;
	private $_data;

	
	
	// on récupére que par get_data mais on peu initialisé par le constructeur ou par set_data
	public function __construct( $data=false ) { if($data){$this->set_data($data);} }	
	public function set_data($data) { $this->_data = $data; $this->gen(); }	
	public function get_data() { return $this->_final_page; }
	public function get_directive() { 
		$sortir = '';
		foreach($this->gen(TRUE) as $key => $val) {
			$sortir .= 	$key . PHP_EOL;
		}
		return $sortir;
	}
	
	private function gen($rerturn_tag=FALSE) {
		
		$iner_var = array();
		$data = $this->_data;
		
		$bop = [$this,'bop'];
		$bof = [$this,'bof'];
		$bsp = [$this,'bsp'];
		
		$tags = [
			// toujours au début get
			[$bop,['@getfile'		,$nbf,&$data,'', '','(',')',FALSE,'','','#','']],
			[$bop,['@getsegment'	,$nbf,&$data,'', '','(',')',',','{','}','#','%']],
			[$bof,['@setsegment'	,$nbf,&$data,'<!--SEGMENT:', '','(',')','','']],
			[$bsp,['@endsegment' 	,$nbf,&$data,'-->','','']],


			[$bof,['@setvar'		,$nbf,&$data]], // initialisé des variables
			[$bof,['@unsetvar'		,$nbf,&$data,'unset(',');']], // initialisé des variables
			[$bop,['@use'			,$nbf,&$data,'',');',':',')']], // utilisé une function 
			[$bop,['@instancy'		,$nbf,&$data,'# = new ', '%;','(',')',FALSE,'{','}','#','%']], // instancier l'objet
			[$bof,['@object'		,$nbf,&$data,'', ';']], // utilisé un objet
			[$bop,['@setclass'		,$nbf,&$data,'class # ', '{%}','(',')',FALSE,'{','}','#','%']],	// créé des class		
		
			[$bsp,['@dowhile'		,$nbf,&$data,"do{ echo <<<END\r\n",'<?php ','']],
			[$bof,['@whiledo'		,$nbf,&$data,"\r\nEND;\r\n}while(", ');','(',')','']],
			[$bop,['@dow'			,$nbf,&$data,'do{', '%}while(#);','(',')',',','{','}','#','%']],	

			[$bof,['@if'			,$nbf,&$data,'if(', '):']],
			[$bof,['@elseif'		,$nbf,&$data,'elseif(', '):']],
			[$bsp,['@else'			,$nbf,&$data,'else:']],
			[$bsp,['@endif'			,$nbf,&$data,'endif;']],
			[$bof,['@for'			,$nbf,&$data,'for(','):']],
			[$bsp,['@endfor'		,$nbf,&$data,'endfor;']],
			[$bof,['@foreach'		,$nbf,&$data,'foreach(','):']],
			[$bsp,['@endforeach'	,$nbf,&$data,'endforeach;']],
			[$bof,['@while'			,$nbf,&$data,'while(','):']],
			[$bsp,['@endwhile'		,$nbf,&$data,'endwhile;']],
			[$bof,['@switch'		,$nbf,&$data,'switch (','):']],
			[$bof,['@case'			,$nbf,&$data,'case (','):']],
			[$bsp,['@break'			,$nbf,&$data,'break;']],
			[$bsp,['@continue'		,$nbf,&$data,'continue;']],
			[$bsp,['@default'		,$nbf,&$data,'default:']],
			[$bsp,['@endswitch'		,$nbf,&$data,'endswitch;']],
			[$bof,['@goto'			,$nbf,&$data,'goto ', ';']],
			[$bof,['@label'			,$nbf,&$data,'', ':']],

			// code encapsuler 
			[$bof,['@istrue'		,$nbf,&$data,'echo ((',")? <<<END\r\n",'(',')','<?php ', '']],
			[$bsp,['@endistrue'		,$nbf,&$data,"\r\nEND\r\n:'');",'']],
			[$bof,['@isfalse'		,$nbf,&$data,'echo ((',")? '':<<<END\r\n",'(',')','<?php ', '']],
			[$bsp,['@endisfalse'	,$nbf,&$data,"\r\nEND\r\n);",'']],
			

			[$bsp,['@sessionstart'	,$nbf,&$data,'session_start();']],

			////////////////////////////////////

			[$bsp,['@timetest'		,$nbf,&$data,'$microtime_start_test = microtime(true);']],
			[$bsp,['@endtimetest'	,$nbf,&$data,'$microtime_end_test = microtime(true); echo round(($microtime_end_test - $microtime_start_test),4);']],


			[$bsp,['@headpage'		,$nbf,&$data,'<html><head>','','']],
			[$bsp,['@bodypage'		,$nbf,&$data,'</head><body>','','']],
			[$bsp,['@endpage'		,$nbf,&$data,'</body></html>','','']],
			
			[$bof,['@html'			,$nbf,&$data,'<html>','</html>', '{', '}', '','']],			
			[$bof,['@head'			,$nbf,&$data,'<head>','</head>', '{', '}', '','']],			
			[$bof,['@title'			,$nbf,&$data,'<title>','</title>', '(', ')', '','']],
			[$bof,['@meta'			,$nbf,&$data,'<meta ', '>', '(', ')', '','']],
			[$bof,['@link'			,$nbf,&$data,'<link rel="stylesheet" href="', '">', '(', ')', '','']],
			[$bof,['@script'		,$nbf,&$data,'<script src="', '"></script>', '(', ')', '','']],
			[$bof,['@style'			,$nbf,&$data,'<style>', '</style>', '{', '}', '','']],
			//[$bof,['@body'			,$nbf,&$data,'<body>','</body>', '{', '}','','']],
			[$bop,['@body'			,$nbf,&$data,'<body #>','%</body>', '(',')',true,'{', '}', '#','%','','']],
	
			[$bof,['@PHP'			,$nbf,&$data,'','','{','}']], // affiche son resulta
			[$bof,['@JS'			,$nbf,&$data,'','','{','}','<script>','</script>']], // affiche son resulta
			
			[$bop,['@blockquote'	,$nbf,&$data,'<blockquote #>','%</blockquote>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@figcaption'	,$nbf,&$data,'<figcaption #>','%</figcaption>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@colgroup'		,$nbf,&$data,'<colgroup #>','%</colgroup>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@datalist'		,$nbf,&$data,'<datalist #>','%</datalist>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@fieldset'		,$nbf,&$data,'<fieldset #>','%</fieldset>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@noscript'		,$nbf,&$data,'<noscript #>','%</noscript>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@optgroup'		,$nbf,&$data,'<optgroup #>','%</optgroup>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@progress'		,$nbf,&$data,'<progress #>','%</progress>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@textarea'		,$nbf,&$data,'<textarea #>','%</textarea>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@address'		,$nbf,&$data,'<address #>','%</address>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@article'		,$nbf,&$data,'<article #>','%</article>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@caption'		,$nbf,&$data,'<caption #>','%</caption>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@command'		,$nbf,&$data,'<command #>','%</command>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@details'		,$nbf,&$data,'<details #>','%</details>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@section'		,$nbf,&$data,'<section #>','%</section>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@summary'		,$nbf,&$data,'<summary #>','%</summary>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@button'		,$nbf,&$data,'<button #>','%</button>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@canvas'		,$nbf,&$data,'<canvas #>','%</canvas>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@figure'		,$nbf,&$data,'<figure #>','%</figure>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@footer'		,$nbf,&$data,'<footer #>','%</footer>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@header'		,$nbf,&$data,'<header #>','%</header>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@hgroup'		,$nbf,&$data,'<hgroup #>','%</hgroup>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@iframe'		,$nbf,&$data,'<iframe #>','%</iframe>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@keygen'		,$nbf,&$data,'<keygen #>','%</keygen>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@legend'		,$nbf,&$data,'<legend #>','%</legend>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@object'		,$nbf,&$data,'<object #>','%</object>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@option'		,$nbf,&$data,'<option #>','%</option>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@output'		,$nbf,&$data,'<output #>','%</output>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@select'		,$nbf,&$data,'<select #>','%</select>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@source'		,$nbf,&$data,'<source #>','%</source>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@strong'		,$nbf,&$data,'<strong #>','%</strong>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@aside'			,$nbf,&$data,'<aside #>','%</aside>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@audio'			,$nbf,&$data,'<audio #>','%</audio>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@embed'			,$nbf,&$data,'<embed #>','%</embed>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@input'			,$nbf,&$data,'<input #>','%</input>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@label'			,$nbf,&$data,'<label #>','%</label>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@meter'			,$nbf,&$data,'<meter #>','%</meter>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@param'			,$nbf,&$data,'<param #>','%</param>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@small'			,$nbf,&$data,'<small #>','%</small>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@table'			,$nbf,&$data,'<table #>','%</table>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@tbody'			,$nbf,&$data,'<tbody #>','%</tbody>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@tfoot'			,$nbf,&$data,'<tfoot #>','%</tfoot>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@thead'			,$nbf,&$data,'<thead #>','%</thead>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@title'			,$nbf,&$data,'<title #>','%</title>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@track'			,$nbf,&$data,'<track #>','%</track>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@video'			,$nbf,&$data,'<video #>','%</video>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@abbr'			,$nbf,&$data,'<abbr #>','%</abbr>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@area'			,$nbf,&$data,'<area #>','%</area>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@base'			,$nbf,&$data,'<base #>','%</base>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@cite'			,$nbf,&$data,'<cite #>','%</cite>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@code'			,$nbf,&$data,'<code #>','%</code>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@form'			,$nbf,&$data,'<form #>','%</form>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@mark'			,$nbf,&$data,'<mark #>','%</mark>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@math'			,$nbf,&$data,'<math #>','%</math>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@menu'			,$nbf,&$data,'<menu #>','%</menu>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@ruby'			,$nbf,&$data,'<ruby #>','%</ruby>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@samp'			,$nbf,&$data,'<samp #>','%</samp>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@span'			,$nbf,&$data,'<span #>','%</span>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@time'			,$nbf,&$data,'<time #>','%</time>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@bdo'			,$nbf,&$data,'<bdo #>','%</bdo>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@col'			,$nbf,&$data,'<col #>','%</col>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@del'			,$nbf,&$data,'<del #>','%</del>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@dfn'			,$nbf,&$data,'<dfn #>','%</dfn>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@div'			,$nbf,&$data,'<div #>','%</div>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@img'			,$nbf,&$data,'<img #>','%</img>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@ins'			,$nbf,&$data,'<ins #>','%</ins>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@kbd'			,$nbf,&$data,'<kbd #>','%</kbd>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@map'			,$nbf,&$data,'<map #>','%</map>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@nav'			,$nbf,&$data,'<nav #>','%</nav>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@pre'			,$nbf,&$data,'<pre #>','%</pre>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@sub'			,$nbf,&$data,'<sub #>','%</sub>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@sup'			,$nbf,&$data,'<sup #>','%</sup>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@svg'			,$nbf,&$data,'<svg #>','%</svg>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@var'			,$nbf,&$data,'<var #>','%</var>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@wbr'			,$nbf,&$data,'<wbr #>','%</wbr>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@br'			,$nbf,&$data,'<br #>','%</br>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@dd'			,$nbf,&$data,'<dd #>','%</dd>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@dl'			,$nbf,&$data,'<dl #>','%</dl>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@dt'			,$nbf,&$data,'<dt #>','%</dt>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@em'			,$nbf,&$data,'<em #>','%</em>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@h1'			,$nbf,&$data,'<h1 #>','%</h1>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@h2'			,$nbf,&$data,'<h2 #>','%</h2>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@h3'			,$nbf,&$data,'<h3 #>','%</h3>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@h4'			,$nbf,&$data,'<h4 #>','%</h4>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@h5'			,$nbf,&$data,'<h5 #>','%</h5>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@h6'			,$nbf,&$data,'<h6 #>','%</h6>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@hr'			,$nbf,&$data,'<hr #>','%</hr>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@li'			,$nbf,&$data,'<li #>','%</li>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@ol'			,$nbf,&$data,'<ol #>','%</ol>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@rp'			,$nbf,&$data,'<rp #>','%</rp>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@rt'			,$nbf,&$data,'<rt #>','%</rt>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@td'			,$nbf,&$data,'<td #>','%</td>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@th'			,$nbf,&$data,'<th #>','%</th>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@tr'			,$nbf,&$data,'<tr #>','%</tr>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@ul'			,$nbf,&$data,'<ul #>','%</ul>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@a'				,$nbf,&$data,'<a #>','%</a>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@b'				,$nbf,&$data,'<b #>','%</b>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@i'				,$nbf,&$data,'<i #>','%</i>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@p'				,$nbf,&$data,'<p #>','%</p>', '(',')',true,'{', '}', '#','%','','']],
			[$bop,['@q'				,$nbf,&$data,'<q #>','%</q>', '(',')',true,'{', '}', '#','%','','']],
			
			[$bof,['@'				,$nbf,&$data,'echo $',';','{','}']], // affiche son resulta
			
		];
		
		
		
		// préparation création d'un tableau de revers
		$tabloc = array();	
		foreach($tags as $k => $fnc) { $tabloc[$fnc[1][0]] = $k; }
		if($rerturn_tag) { return $tabloc; }
		
		// on charge les invoc et le import avant tout
		$rx = ['@invoc' => 0,'@import' => 0, '@load' => 0];
		foreach($rx as $fnd => &$nbf) { $nbf = substr_count($data, $fnd); }
		while( array_sum($rx) > 0 ){ 	
			foreach($rx as $fnd => $nbf) {
				if($nbf > 0) {
					$fndx = $tabloc[$fnd];
					$tags[$fndx][1][1] = $nbf;
					call_user_func_array($tags[$fndx][0],$tags[$fndx][1]);
				}
			}
			foreach($rx as $fnd => &$nbf) { $nbf = substr_count($data, $fnd); }	
		}
	
		// on supprime les functions non présente dans data
		// et on prépare les variables du talbleau
		foreach($tags as $k => &$fnc) {
			if(($fnc[1][1] = substr_count($data, $fnc[1][0])) == 0) {
				unset($tags[$k]);
			} 
		}
		
		$time_start = microtime(true);
		
		// on demarre la fabrication
		foreach($tags as &$fnc) {
			call_user_func_array($fnc[0],$fnc[1]);
		}
		
		$data = str_ireplace(["\r\n\r\n","\r\n ?>",' >'], ["\r\n", "\r\n?>",'>'], $data);
		
		$time_end = microtime(true);
		$time = $time_end - $time_start;

		echo "execusion $time secondes\n";
		$this->_final_page = $data;
		
		
	}
	
	private function bsp($find,$fdb,&$data,$replace,$debx='<?php ',$endx=' ?>'){
		$data = str_ireplace($find,"$debx$replace$endx", $data);
	}
	
	private function bop($find,$fdb,&$data,$deb='', $fin='',$bdeb1='(',$bfin1=')',$exp=',',$bdeb2='{',$bfin2='}',$masque1=false,$masque2=false,$debx='<?php ',$endx=' ?>',$b=0){
			
			$dd = strlen($data);
			$s = strlen($find);
			while(--$fdb >= 0) {
				$b = stripos($data,$find,$b);
				$bs = ($b + $s);
				if($b !== false ) {
								$nodeb1 = false;
								// si les parenthéses existes 
								if( 
								 ($data[$bs] == $bdeb1 )  && 
								 (($c = stripos($data,$bfin1,$bs)) !== false ) 
								) {
									
										$c--;
										for($j=0; $j < $dd ;$j++) {
											$k = substr_count( substr($data,$bs,($c - $bs)) , $bdeb1);
											$l = substr_count( substr($data,$bs,($c - $bs)) , $bfin1);
											if($find == '@getsegment') { echo $k , '==' ,$l, ' :: ',$c, PHP_EOL; }
											if($k == $l) { break; }
											$c = stripos($data,$bfin1, $c+1) + 1; //execusion 0.0077269077301025 secondes
											//$c++; // execusion 0.0082950592041016 secondes
										}
										
										//if($find == '@body') { echo '>>',$data[$bs],  PHP_EOL; sleep(1000); }
										
										$mex = trim( substr($data,($bs + 1),( $c - $bs) - 2) );
										
										
										if($exp == ',') {
											$t = explode($exp,$mex);
											if($t[1] == 'PHP') {
												$mex = $t[0];
											}
										}

								} else { $nodeb1 = true; }
								
								
								// si les acolades existes 
								if(
								(($data[$c] == $bdeb2) &&
								(($e = stripos($data,$bfin2,$c+1)) !== false )) 
								|| 
								(($data[$bs] == $bdeb2) &&
								(($e = stripos($data,$bfin2,$bs)) !== false ))
								  ){
									if($nodeb1) { $c = $bs; }
									$e--;
										for($j=0; $j < $dd ;$j++) {
											$o = substr_count( substr($data,$c,($e - $c)) , $bdeb2);
											$p = substr_count( substr($data,$c,($e - $c)) , $bfin2);
											echo $o , '<==>' ,$p, ' :: ',$c, PHP_EOL;
											if($o == $p) { break; }
											$e = stripos($data,$bfin2, $e+1) + 1; //execusion 0.0077269077301025 secondes
											//$e++; // execusion 0.0082950592041016 secondes
										}
									
									
									$m2 = substr($data,$c+1,($e - $c)-2);
									
									if( ($masque2) && ($exp === false) && ($nodeb1 === false) ) { $m2 = "echo <<<END2 $nodeb1 \r\n$m2\r\nEND2;\r\n"; }
									//if($find == '@getsegment') { echo '>>',$m2,'<<',  PHP_EOL; sleep(1000); }
								}



								// si masque 1 existe
								if($masque1) {
									
									$debo = $deb;
									$fino = $fin;
									
									// si $deb à une exception  précise
									switch($find) { // si masque 1 est true
										
										case '@getfile':
										
											$debx = "\r\n<!-- start invoc file : $mex -->\r\n" ;
											$debo = file_get_contents($mex);
											$endx = "\r\n<!-- END invoc file : $mex -->\r\n";
											$fino = '';
											$mex  = '';
											
										break;
										case '@getsegment':
										
											if($t[1] != 'PHP') {
												$debx = "\r\n<!-- start import:segment $m2 to file : $mex -->\r\n";
												$endx = "\r\n<!-- END import:segment $m2 to file : $mex -->\r\n";
											}
											
											$debf = file_get_contents(trim($mex,'\''));
											$tds  = strlen("@setsegment($m2)");
											$ddms = stripos($debf,"@setsegment($m2)",0);
											$fdms = stripos($debf,'@endsegment',$ddms+1);
											$debo = substr($debf,($ddms + $tds),($fdms - ($ddms + $tds)));
											$fino = ''; unset($debf);
											$mex  = '';
											
										break;
									}
									
									$rpl = "$debx$debo$fino$endx";
									$rpl = ($masque2 ? str_ireplace($masque2,$m2,str_ireplace($masque1,$mex,$rpl)) : str_ireplace($masque1,$mex,$rpl));
									
								}	
								else {
									$rpl = "$debx$deb$mex$fin$endx";
								}
						
							$data = substr_replace($data, $rpl, $b, (($e - $b)) );
							$b += strlen($rpl);

								//if($find == '@body') { echo '>>',$data,'<<',  PHP_EOL;  }
								//if($find == '@body') { echo '>>',$rpl,'<<',  PHP_EOL; sleep(1000); }
				
				} else { return false; }

			}
	
	}

	
	private function bof($find,$fdb,&$data,$deb='', $fin='',$bdeb='(',$bfin=')',$debx='<?php ',$endx=' ?>',$b=0){

			$d = strlen($data);
			$s = strlen($find);
			$i=1;	
			while(--$fdb >= 0) { 
				$b = stripos($data,$find,$b);
				$bs = ($b + $s);
				if(
					( $b !== false ) && 
					( $data[$bs] == $bdeb )  && 
					( ($c = stripos($data,$bfin,$bs)) !== false ) 
				) {  
							

							$c--; 
								for($j=0; $j < $d ;$j++) {
									$k = substr_count( substr($data,$bs,($c - $bs)) , $bdeb);
									$l = substr_count( substr($data,$bs,($c - $bs)) , $bfin);
									//echo $k , '==' ,$l, ' :: ',$c, PHP_EOL;
									if($k == $l) { break; }
									$c = stripos($data,$bfin, $c+1) + 1; //execusion 0.0077269077301025 secondes
									//$c++; // execusion 0.0082950592041016 secondes
								}
								

								
								$m = trim( substr($data,($bs + 1),( ($c - $bs) -2)) );
								$rpl = "$debx$deb$m$fin$endx";
							
							$data = substr_replace($data, $rpl, $b, (($c - $b)) );
							$b += strlen($rpl);

				
				} else { return false; }

			}

	}
	
}

?>