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
		$data = $this->_data;
		$tags = [
			// toujours au début
			[[$this,'bop'],['@invoc'		,$nbf,&$data,'__INVOCFILE__', '','(',')',FALSE,'','','#','']],
			[[$this,'bop'],['@import'		,$nbf,&$data,'__INVOCSEGMENT__', '','(',')',FALSE,'{','}','#','%']],
			[[$this,'bof'],['@segment'		,$nbf,&$data,'<!--SEGMENT:', '','(',')','','']],
			[[$this,'bsp'],['@endsegment'	,$nbf,&$data,'-->','','']],
			[[$this,'bof'],['@load'			,$nbf,&$data,'include(', ');']],


			//Functions spécial
			[[$this,'bop'],['@dataimport'	,$nbf,&$data,'# = file_get_contents(', '%);','(',')',FALSE,'{','}','#','%']],
			[[$this,'bop'],['@replace'		,$nbf,&$data,'# = str_ireplace(', '%);','(',')',FALSE,'{','}','#','%']],
			[[$this,'bof'],['@define'		,$nbf,&$data,'define(',');']],
			
			
			
			
			
			[[$this,'bof'],['@_GLOBALS'		,$nbf,&$data,'echo $GLOBALS[','];']],
			[[$this,'bof'],['@_SERVER'		,$nbf,&$data,'echo $_SERVER[','];']],
			[[$this,'bof'],['@_POST'		,$nbf,&$data,'echo $_POST[','];']],
			[[$this,'bof'],['@_GET'			,$nbf,&$data,'echo $_GET[','];']],
			[[$this,'bof'],['@_ENV'			,$nbf,&$data,'echo $_ENV[','];']],
			[[$this,'bof'],['@_COOKIE'		,$nbf,&$data,'echo $_COOKIE[','];']],
			[[$this,'bof'],['@_SESSION'		,$nbf,&$data,'echo $_SESSION[','];']],
			
			[[$this,'bop'],['@#GLOBALS'		,$nbf,&$data,'# = $GLOBALS[', '%];','(',')',FALSE,'{','}','#','%']],
			[[$this,'bop'],['@#SERVER'		,$nbf,&$data,'# = $_SERVER[', '%];','(',')',FALSE,'{','}','#','%']],
			[[$this,'bop'],['@#POST'		,$nbf,&$data,'# = $_POST[', '%];','(',')',FALSE,'{','}','#','%']],
			[[$this,'bop'],['@#GET'			,$nbf,&$data,'# = $_GET[', '%];','(',')',FALSE,'{','}','#','%']],
			[[$this,'bop'],['@#ENV'			,$nbf,&$data,'# = $_ENV[', '%];','(',')',FALSE,'{','}','#','%']],
			[[$this,'bop'],['@#COOKIE'		,$nbf,&$data,'# = $_COOKIE[', '%];','(',')',FALSE,'{','}','#','%']],
			[[$this,'bop'],['@#SESSION'		,$nbf,&$data,'# = $_SESSION[', '%];','(',')',FALSE,'{','}','#','%']],

			[[$this,'bof'],['@global'		,$nbf,&$data,'global ',';']],
			
			[[$this,'bof'],['@inc'			,$nbf,&$data,'','++;']],
			[[$this,'bof'],['@dec'			,$nbf,&$data,'','--;']],

			[[$this,'bof'],['@set'			,$nbf,&$data]], // initialisé des variables  
			[[$this,'bop'],['@var'			,$nbf,&$data,'#','=%;','(',':',FALSE,':',')','#','%']], // initialisé des variables
			[[$this,'bof'],['@exe'			,$nbf,&$data]], // ececuté plusieurs fonction php 
			[[$this,'bop'],['@fct'			,$nbf,&$data,'function ']], // créé une function 
			[[$this,'bop'],['@use'			,$nbf,&$data,'',');',':',')']], // utilisé une function 
			[[$this,'bof'],['@echo'			,$nbf,&$data,'echo ',';']], // afficher une valeur
			[[$this,'bop'],['@inst'			,$nbf,&$data,'# = new ', '%;','(',')',FALSE,'{','}','#','%']], // instancier l'objet
			[[$this,'bof'],['@obj'			,$nbf,&$data,'', ';']], // utilisé un objet
			[[$this,'bop'],['@class'		,$nbf,&$data,'class # ', '{%}','(',')',FALSE,'{','}','#','%']],	// créé des class		
		
			[[$this,'bsp'],['@dowhile'		,$nbf,&$data,"do{ echo <<<END\r\n",'<?php ','']],
			[[$this,'bof'],['@whiledo'		,$nbf,&$data,"\r\nEND;\r\n}while(", ');','(',')','']],
			[[$this,'bop'],['@dow'			,$nbf,&$data,'do{', '%}while(#);','(',')',';','{','}','#','%']],	

			[[$this,'bof'],['@if'			,$nbf,&$data,'if(', '):']],
			[[$this,'bof'],['@elseif'		,$nbf,&$data,'elseif(', '):']],
			[[$this,'bsp'],['@else'			,$nbf,&$data,'else:']],
			[[$this,'bsp'],['@endif'		,$nbf,&$data,'endif;']],
			[[$this,'bof'],['@for'			,$nbf,&$data,'for(','):']],
			[[$this,'bsp'],['@endfor'		,$nbf,&$data,'endfor;']],
			[[$this,'bof'],['@foreach'		,$nbf,&$data,'foreach(','):']],
			[[$this,'bsp'],['@endforeach'	,$nbf,&$data,'endforeach;']],
			[[$this,'bof'],['@while'		,$nbf,&$data,'while(','):']],
			[[$this,'bsp'],['@endwhile'		,$nbf,&$data,'endwhile;']],
			[[$this,'bof'],['@switch'		,$nbf,&$data,'switch (','):']],
			[[$this,'bof'],['@case'			,$nbf,&$data,'case (','):']],
			[[$this,'bsp'],['@break'		,$nbf,&$data,'break;']],
			[[$this,'bsp'],['@continue'		,$nbf,&$data,'continue;']],
			[[$this,'bsp'],['@default'		,$nbf,&$data,'default:']],
			[[$this,'bsp'],['@endswitch'	,$nbf,&$data,'endswitch;']],
			[[$this,'bof'],['@goto'			,$nbf,&$data,'goto ', ';']],
			[[$this,'bof'],['@label'		,$nbf,&$data,'', ':']],

			////////////////////////////////////
			//TAB
			[[$this,'bsp'],['@tabload'		,$nbf,&$data,'$_tabofdirective=array();']],
			[[$this,'bof'],['@tabsay'		,$nbf,&$data,'$_tabofdirective[', ']=null;']],	// initialise le nom de la portion de code
			[[$this,'bof'],['@tabsee'		,$nbf,&$data,'if($_tabofdirective[', ']):']],	// affiche le code si l'initialisation est à TRUE
			[[$this,'bsp'],['@endtabsee'	,$nbf,&$data,'endif;']],		
			[[$this,'bof'],['@tabis'		,$nbf,&$data,'if($_tabofdirective', '):']],		// affiche si on désire vérifier que l'initialisation à une autre valeur 
			[[$this,'bsp'],['@endtabis'		,$nbf,&$data,'endif;']],
			[[$this,'bof'],['@tabon'		,$nbf,&$data,'$_tabofdirective[', ']=true;']],	// passe l'initialisation à TRUE
			[[$this,'bof'],['@taboff'		,$nbf,&$data,'$_tabofdirective[', ']=false;']], // passe l'initialisation à FALSE
			[[$this,'bop'],['@tabinit'		,$nbf,&$data,'$_tabofdirective[#]','=%;','(',':',FALSE,':',')','#','%']],			// passe l'initialisation à la valeur de l'on désire
			[[$this,'bof'],['@tabini'		,$nbf,&$data,'$_tabofdirective', ';']],			// passe l'initialisation à la valeur de l'on désire
			//VAR
			[[$this,'bsp'],['@varload'		,$nbf,&$data,'$_varofdirective=array();']],
			[[$this,'bof'],['@varsay'		,$nbf,&$data,'$_varofdirective[', ']=null;']],	// initialise le nom de la portion de code
			[[$this,'bof'],['@varsee'		,$nbf,&$data,'if($_varofdirective[', ']):']],	// affiche le code si l'initialisation est à TRUE
			[[$this,'bsp'],['@endvarsee'	,$nbf,&$data,'endif;']],		
			[[$this,'bof'],['@varis'		,$nbf,&$data,'if($_varofdirective', '):']],		// affiche si on désire vérifier que l'initialisation à une autre valeur 
			[[$this,'bsp'],['@endvaris'		,$nbf,&$data,'endif;']],
			[[$this,'bof'],['@varon'		,$nbf,&$data,'$_varofdirective[', ']=true;']],	// passe l'initialisation à TRUE
			[[$this,'bof'],['@varoff'		,$nbf,&$data,'$_varofdirective[', ']=false;']], // passe l'initialisation à FALSE
			[[$this,'bop'],['@varinit'		,$nbf,&$data,'$_varofdirective[#]','=%;','(',':',FALSE,':',')','#','%']],			// passe l'initialisation à la valeur de l'on désire
			[[$this,'bof'],['@varini'		,$nbf,&$data,'$_varofdirective', ';']],			// passe l'initialisation à la valeur de l'on désire
			//USERS
			[[$this,'bsp'],['@userload'		,$nbf,&$data,'$_userofdirective=array();']],
			[[$this,'bof'],['@usersay'		,$nbf,&$data,'$_userofdirective[', ']=null;']],	// initialise le nom de la portion de code
			[[$this,'bof'],['@usersee'		,$nbf,&$data,'if($_userofdirective[', ']):']],	// affiche le code si l'initialisation est à TRUE
			[[$this,'bsp'],['@endusersee'	,$nbf,&$data,'endif;']],		
			[[$this,'bof'],['@useris'		,$nbf,&$data,'if($_userofdirective', '):']],	// affiche si on désire vérifier que l'initialisation à une autre valeur 
			[[$this,'bsp'],['@enduseris'	,$nbf,&$data,'endif;']],
			[[$this,'bof'],['@useron'		,$nbf,&$data,'$_userofdirective[', ']=true;']],	 // passe l'initialisation à TRUE
			[[$this,'bof'],['@useroff'		,$nbf,&$data,'$_userofdirective[', ']=false;']], // passe l'initialisation à FALSE
			[[$this,'bop'],['@userinit'		,$nbf,&$data,'$_userofdirective[#]','=%;','(',':',FALSE,':',')','#','%']],			// passe l'initialisation à la valeur de l'on désire
			[[$this,'bof'],['@userini'		,$nbf,&$data,'$_userofdirective', ';']],			// passe l'initialisation à la valeur de l'on désire
			//SESSION
			[[$this,'bsp'],['@sessionload'		,$nbf,&$data,'$_sessionofdirective=array();']],
			[[$this,'bof'],['@sessionsay'		,$nbf,&$data,'$_sessionofdirective[', ']=null;']],	// initialise le nom de la portion de code
			[[$this,'bof'],['@sessionsee'		,$nbf,&$data,'if($_sessionofdirective[', ']):']],	// affiche le code si l'initialisation est à TRUE
			[[$this,'bsp'],['@endsessionsee'	,$nbf,&$data,'endif;']],		
			[[$this,'bof'],['@sessionis'		,$nbf,&$data,'if($_sessionofdirective', '):']],	// affiche si on désire vérifier que l'initialisation à une autre valeur 
			[[$this,'bsp'],['@endsessionis'		,$nbf,&$data,'endif;']],
			[[$this,'bof'],['@sessionon'		,$nbf,&$data,'$_sessionofdirective[', ']=true;']],	 // passe l'initialisation à TRUE
			[[$this,'bof'],['@sessionoff'		,$nbf,&$data,'$_sessionofdirective[', ']=false;']], // passe l'initialisation à FALSE
			[[$this,'bop'],['@sessioninit'		,$nbf,&$data,'$_sessionofdirective[#]','=%;','(',':',FALSE,':',')','#','%']],			// passe l'initialisation à la valeur de l'on désire
			[[$this,'bof'],['@sessionini'		,$nbf,&$data,'$_sessionofdirective', ';']],			// passe l'initialisation à la valeur de l'on désire
			//THEME
			[[$this,'bsp'],['@themeload'		,$nbf,&$data,'$_themeofdirective=array();']],
			[[$this,'bof'],['@themesay'			,$nbf,&$data,'$_themeofdirective[', ']=null;']],	// initialise le nom de la portion de code
			[[$this,'bof'],['@themesee'			,$nbf,&$data,'if($_themeofdirective[', ']):']],	// affiche le code si l'initialisation est à TRUE
			[[$this,'bsp'],['@endthemesee'		,$nbf,&$data,'endif;']],		
			[[$this,'bof'],['@themeis'			,$nbf,&$data,'if($_themeofdirective', '):']],	// affiche si on désire vérifier que l'initialisation à une autre valeur 
			[[$this,'bsp'],['@endthemeis'		,$nbf,&$data,'endif;']],
			[[$this,'bof'],['@themeon'			,$nbf,&$data,'$_themeofdirective[', ']=true;']],	 // passe l'initialisation à TRUE
			[[$this,'bof'],['@themeoff'			,$nbf,&$data,'$_themeofdirective[', ']=false;']], // passe l'initialisation à FALSE
			[[$this,'bop'],['@themeinit'		,$nbf,&$data,'$_themeofdirective[#]','=%;','(',':',FALSE,':',')','#','%']],			// passe l'initialisation à la valeur de l'on désire
			[[$this,'bof'],['@themeini'			,$nbf,&$data,'$_themeofdirective', ';']],			// passe l'initialisation à la valeur de l'on désire

			////////////////////////////////////

			[[$this,'bsp'],['@timetest'		,$nbf,&$data,'$microtime_start_test = microtime(true);']],
			[[$this,'bsp'],['@endtimetest'	,$nbf,&$data,'$microtime_end_test = microtime(true); echo round(($microtime_end_test - $microtime_start_test),4);']],
   
			[[$this,'bsp'],['@\RN'			,$nbf,&$data,'echo PHP_EOL;']],
			[[$this,'bsp'],['@\R'			,$nbf,&$data,'echo "\r";']],
			[[$this,'bsp'],['@\N'			,$nbf,&$data,'echo "\n";']],
			[[$this,'bsp'],['@\T'			,$nbf,&$data,'echo "\t";']],
			[[$this,'bsp'],['@\Z'			,$nbf,&$data,'echo "\0";']],
			[[$this,'bsp'],['@\SP'			,$nbf,&$data,'echo chr(32);']],
			[[$this,'bof'],['@chr'			,$nbf,&$data,'chr(',');']],
			[[$this,'bof'],['@ord'			,$nbf,&$data,'ord(',');']],
			
			[[$this,'bof'],['@_'			,$nbf,&$data,'echo ',';']],
			[[$this,'bof'],['@'				,$nbf,&$data,'echo $',';','{','}']], // affiche son resulta
			//[[$this,'bop'],['@!'			,$nbf,&$data,'echo ($# ? $#:null)', ';','{','}',FALSE,'','','#']], // affiche son resulta que s'il est true
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

		$time_end = microtime(true);
		$time = $time_end - $time_start;

		echo "execusion $time secondes\n";
		$this->_final_page = $data;
		
		
	}
	
	private function bsp($find,$fdb,&$data,$replace,$debx='<?php ',$endx=' ?>'){
		$data = str_ireplace($find,"$debx$replace$endx", $data);
	}
	
	private function bop($find,$fdb,&$data,$deb='', $fin='',$bdeb1='(',$bfin1=')',$exp=';',$bdeb2='{',$bfin2='}',$masque1=false,$masque2=false,$debx='<?php ',$endx=' ?>',$b=0){

			$s = strlen($find);
			while(--$fdb >= 0) {
				$b = stripos($data,$find,$b);
				$bs = ($b + $s);
				if(
					( $b !== false ) && 
					( $data[$bs] == $bdeb1 )  && 
					( ($c = stripos($data,$bfin1,$bs)) !== false ) 
				) {  

							$k = substr_count( substr($data,$bs,($c - $bs)) , $bdeb1);
							
							$c--; 
							while( $k-- > 0 ) $c = stripos($data,$bfin1,++$c);
								
								// detection du premier { et dernier }
								$e = $c;
								if(
								 ($data[($c+1)] == $bdeb2) &&
								 (($e = stripos($data,$bfin2,$c+1)) !== false ) 
								) {

									$o = substr_count( substr($data,$c+1,($e - $c+1)) , $bdeb2);

									$e--; 
									while( $o-- > 0 ) $e = stripos($data,$bfin2,++$e);
									$m2 = substr($data,$c+2,($e - $c-2)); //trim ?

								}

								$mex = trim( substr($data,($bs + 1),( $c - ($bs + 1)) ) );
								
								if( (stripos($mex,$exp,0) !== false and  $exp != '') ) {
									$t = explode($exp,$mex);
									$mex = ($t[1] == 'PHP' ? $t[0] : $mex);
								} 
								elseif( $exp !== FALSE) { 
									if($masque2) { $m2 = "echo <<<END\r\n$m2\r\nEND;\r\n"; }
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
										
										$debx = "\r\n<!-- start import:segment $m2 to file : $mex -->\r\n";
										$debf = file_get_contents(trim($mex,'\''));
										$tds  = strlen("@segment($m2)");
										$ddms = stripos($debf,"@segment($m2)",0);
										$fdms = stripos($debf,'@endsegment',$ddms+1);
										$debo  = substr($debf,($ddms + $tds),($fdms - ($ddms + $tds)));
										$fino  = ''; unset($debf);
										$endx = "\r\n<!-- END import:segment $m2 to file : $mex -->\r\n";
										
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

			
			$s = strlen($find);
				
			while(--$fdb >= 0) { 
				$b = stripos($data,$find,$b);
				$bs = ($b + $s);
				if(
					( $b !== false ) && 
					( $data[$bs] == $bdeb )  && 
					( ($c = stripos($data,$bfin,$bs)) !== false ) 
				) {  
							
							$k = substr_count( substr($data,$bs,($c - $bs)) , $bdeb);
							
							$c--; 
							while( $k-- > 0 ) $c = stripos($data,$bfin,++$c);
							
								
								$m = trim( substr($data,($bs + 1),( $c - ($bs + 1)) ) );
								$rpl = "$debx$deb$m$fin$endx";
							
							$data = substr_replace($data, $rpl, $b, (($c - $b) +1) );
							$b += strlen($rpl);

				
				} else { return false; }

			}

	}
	
}

?>