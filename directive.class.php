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
			[$bop,['@getfile'		,$nbf,&$data,'__INVOCFILE__', '','(',')',FALSE,'','','#','']],
			[$bop,['@getsegment'	,$nbf,&$data,'__INVOCSEGMENT__', '','(',')',',','{','}','#','%']],
			[$bof,['@setsegment'	,$nbf,&$data,'<!--SEGMENT:', '','(',')','','']],
			[$bsp,['@endsegment' 	,$nbf,&$data,'-->','','']],


			[$bof,['@setvar'		,$nbf,&$data]], // initialisé des variables
			[$bof,['@unsetvar'		,$nbf,&$data,'unset(',');']], // initialisé des variables
			[$bop,['@getuse'		,$nbf,&$data,'',');',':',')']], // utilisé une function 
			[$bof,['@echo'			,$nbf,&$data,'echo ',';']], // afficher une valeur
			[$bop,['@setinstant'	,$nbf,&$data,'# = new ', '%;','(',')',FALSE,'{','}','#','%']], // instancier l'objet
			[$bof,['@obj'			,$nbf,&$data,'', ';']], // utilisé un objet
			[$bop,['@setclass'		,$nbf,&$data,'class # ', '{%}','(',')',FALSE,'{','}','#','%']],	// créé des class		
		
			[$bsp,['@dowhile'		,$nbf,&$data,"do{ echo <<<END\r\n",'<?php ','']],
			[$bof,['@whiledo'		,$nbf,&$data,"\r\nEND;\r\n}while(", ');','(',')','']],
			[$bop,['@dow'			,$nbf,&$data,'do{', '%}while(#);','(',')',';','{','}','#','%']],	


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

			//
			[$bof,['@inervar'		,$nbf,&$data,''," = <<<END\r\n",'(',')','<?php ', '']], 	//charger dans une variable du code			
			[$bsp,['@inertitle'		,$nbf,&$data,"\$__title = <<<END\r\n",'<?php ','']], 		//charger dans la variable __title      du code			
			[$bsp,['@inermeta'		,$nbf,&$data,"\$__metalist = <<<END\r\n",'<?php ','']], 	//charger dans la variable __metalist   du code			
			[$bsp,['@inerlink'		,$nbf,&$data,"\$__linklist = <<<END\r\n",'<?php ','']], 	//charger dans la variable __linklist   du code			
			[$bsp,['@inerscript'	,$nbf,&$data,"\$__scriptlist = <<<END\r\n",'<?php ','']], 	//charger dans la variable __scriptlist du code			
			[$bsp,['@inerstyle'		,$nbf,&$data,"\$__style = <<<END\r\n",'<?php ','']], 		//charger dans la variable __style  	du code					
			[$bsp,['@inerheader'	,$nbf,&$data,"\$__header = <<<END\r\n",'<?php ','']], 		//charger dans la variable __header 	du code			
			[$bsp,['@inernav'		,$nbf,&$data,"\$__nav = <<<END\r\n",'<?php ','']], 			//charger dans la variable __nav 		du code			
			[$bsp,['@inersection'	,$nbf,&$data,"\$__section = <<<END\r\n",'<?php ','']], 		//charger dans la variable __section 	du code			
			[$bsp,['@ineraside'		,$nbf,&$data,"\$__aside = <<<END\r\n",'<?php ','']], 		//charger dans la variable __aside  	du code			
			[$bsp,['@inerfooter'	,$nbf,&$data,"\$__footer = <<<END\r\n",'<?php ','']], 		//charger dans la variable __footer		du code			
			[$bsp,['@endiner'		,$nbf,&$data,"\r\nEND;\r\n",'']],
			
			// 
			[$bsp,['@skulfull'		,$nbf,&$data,"echo <<<END\r\n<html><head>\$__title\$__metalist\$__linklist\$__scriptlist\$__style</head><body><header>\$__header</header><div id='middle'><nav>\$__nav</nav><section>\$__section</section><aside>\$__aside</aside></div><footer>\$__footer</footer></body></html>\r\nEND;\r\n"]],
			[$bsp,['@skullow'		,$nbf,&$data,"echo <<<END\r\n<html><head>\$__title\$__metalist\$__linklist\$__scriptlist\$__style</head><body>\$__body</body></html>\r\nEND;\r\n"]],
			//
			[$bsp,['@headpage'		,$nbf,&$data,'<html><head>','','']],
			[$bsp,['@bodypage'		,$nbf,&$data,'</head><body>','','']],
			[$bsp,['@endpage'		,$nbf,&$data,'</body></html>','','']],
			
			[$bof,['@html'			,$nbf,&$data,'<html>','</html>', '{', '}', '','']],			
			[$bof,['@head'			,$nbf,&$data,'<head>','</head>', '{', '}', '','']],			
			[$bof,['@title'			,$nbf,&$data,'<title>','</title>', '(', ')', '','']],
			[$bof,['@meta'			,$nbf,&$data,'<meta ', '>', '(', ')', '','']],
			[$bof,['@linkcss'		,$nbf,&$data,'<link rel="stylesheet" href="', '">', '(', ')', '','']],
			[$bof,['@script'		,$nbf,&$data,'<script src="', '"></script>', '(', ')', '','']],
			[$bof,['@style'			,$nbf,&$data,'<style>', '</style>', '{', '}', '','']],
			[$bof,['@body'			,$nbf,&$data,'<body>','</body>', '{', '}', '','']],


			
			[$bsp,['@br'			,$nbf,&$data,'<br/>','','']],
			[$bsp,['@hr'			,$nbf,&$data,'<hr/>','','']],
			
			[$bof,['@#'				,$nbf,&$data,'','','{','}']], // affiche son resulta
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
		
		$data = str_ireplace(["\r\n\r\n","\r\n ?>"], ["\r\n", "\r\n?>"], $data);
		
		$time_end = microtime(true);
		$time = $time_end - $time_start;

		echo "execusion $time secondes\n";
		$this->_final_page = $data;
		
		
	}
	
	private function bsp($find,$fdb,&$data,$replace,$debx='<?php ',$endx=' ?>'){
		$data = str_ireplace($find,"$debx$replace$endx", $data);
	}
	
	private function bop($find,$fdb,&$data,$deb='', $fin='',$bdeb1='(',$bfin1=')',$exp=';',$bdeb2='{',$bfin2='}',$masque1=false,$masque2=false,$debx='<?php ',$endx=' ?>',$b=0){

			//$d = strlen($data);
			$s = strlen($find);
			while(--$fdb >= 0) {
				$b = stripos($data,$find,$b);
				$bs = ($b + $s);
				if(
					( $b !== false ) && 
					( $data[$bs] == $bdeb1 )  && 
					( ($c = stripos($data,$bfin1,$bs)) !== false ) 
				) {  

								$c--;
								for($j=0; $j < $d ;$j++) {
									$k = substr_count( substr($data,$bs,($c - $bs)) , $bdeb1);
									$l = substr_count( substr($data,$bs,($c - $bs)) , $bfin1);
									//echo $k , '==' ,$l, ' :: ',$c, PHP_EOL;
									if($k == $l) { break; }
									$c = stripos($data,$bfin1, $c+1) + 1; //execusion 0.0077269077301025 secondes
									//$c++; // execusion 0.0082950592041016 secondes
								}
								$c++;
							
							
								
								// detection du premier { et dernier }
								$e = $c;
								if(
								 ($data[($c+1)] == $bdeb2) &&
								 (($e = stripos($data,$bfin2,$c+1)) !== false ) 
								) {

									$e--;
										for($j=0; $j < $d ;$j++) {
											$o = substr_count( substr($data,$c,($e - $c)) , $bdeb2);
											$p = substr_count( substr($data,$c,($e - $c)) , $bfin2);
											echo $o , '==' ,$p, ' :: ',$c, PHP_EOL;
											if($o == $p) { break; }
											$e = stripos($data,$bfin2, $e+1) + 1; //execusion 0.0077269077301025 secondes
											//$e++; // execusion 0.0082950592041016 secondes
										}
									$e++;
									
									$m2 = substr($data,$c+2,($e - $c)-2);

								}
									
								//substr($data,($bs + 1),( ($c - $bs) -2))
								$mex = trim( substr($data,($bs + 1),( $c - ($bs + 1)) ) );
								$pcode = false;
								
								
								
								
									
								if( $exp !== false &&  $exp != '' && stripos($mex,$exp,0) !== false ) {
										$t = explode($exp,$mex);
										
										if($t[1] == 'PHP') {
											$mex = $t[0];
											$pcode = true;
										}
										else {
											if($masque2) { $m2 = "echo <<<END\r\n$m2\r\nEND;\r\n"; }
										}
								}

								
								
								
								

								
								
								if($masque1) { 
								
									$debo = $deb;
									$fino = $fin;
									
									if($deb == '__INVOCFILE__') { 
										$debx = "\r\n<!-- start invoc file : $mex -->\r\n" ;
										$debo = file_get_contents($mex);
										$fino = '';
										$endx = "\r\n<!-- END invoc file : $mex -->\r\n";
									}
									elseif($deb == '__INVOCSEGMENT__') {

										if($pcode) {
											$debx = "<?php \r\n";
											$endx = " ?>";
										} 
										else {
											$debx = "\r\n<!-- start import:segment $m2 to file : $mex -->\r\n";
											$endx = "\r\n<!-- END import:segment $m2 to file : $mex -->\r\n";
										}
										
										$debf = file_get_contents(trim($mex,'\''));
										$tds  = strlen("@setsegment($m2)");
										$ddms = stripos($debf,"@setsegment($m2)",0);
										$fdms = stripos($debf,'@endsegment',$ddms+1);
										$debo  = substr($debf,($ddms + $tds),($fdms - ($ddms + $tds)));
										$fino  = ''; unset($debf);

									}
									
									$rpl = "$debx$debo$fino$endx";
									//$rpl = str_ireplace($masque1,$mex,$rpl);
									//if($masque2) { $rpl = str_ireplace($masque2,$m2,$rpl); }
									
									$rpl = ($masque2 ? str_ireplace($masque2,$m2,str_ireplace($masque1,$mex,$rpl)) : str_ireplace($masque1,$mex,$rpl));
									
									
								} else {
									$rpl = "$debx$deb$mex$fin$endx";
								}
							

							
							$data = substr_replace($data, $rpl, $b, (($e - $b) +1) );
							$b += strlen($rpl);

				
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
								if($find == '@html') { echo substr($data,$b, (($c - $b)) ) , '<//////////>',$m , ' ## ' , $c; }
								$rpl = "$debx$deb$m$fin$endx";
							
							$data = substr_replace($data, $rpl, $b, (($c - $b)) );
							$b += strlen($rpl);

				
				} else { return false; }

			}

	}
	
}

?>