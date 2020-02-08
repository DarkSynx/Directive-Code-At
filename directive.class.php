<?php 
/*
 USE 7.4
*/
class directive {
	
	Private $_final_page;
	Private $_data;
	
	
	// on récupére que par get_data mais on peu initialisé par le constructeur ou par set_data
	public function __construct( $data=false ) { if($data){$this->set_data($data);} }	
	public function set_data($data) { $this->_data = $data; $this->gen(); }	
	public function get_data() { return $this->_final_page; }
	
	private function gen() {
		$data = $this->_data;
		$tags = [
			// toujours au début
			'@invoc'		=> [[$this,'bop'],[$nbf,&$data,$fnd, '__INVOCFILE__', '','(',')',FALSE,'','','#','']],
			'@import'		=> [[$this,'bop'],[$nbf,&$data,$fnd, '__INVOCSEGMENT__', '','(',')',FALSE,'{','}','#','%']],
			'@segment'		=> [[$this,'bof'],[$nbf,&$data,$fnd,'<!--SEGMENT:', '','(',')','','']],
			'@endsegment'	=> [[$this,'bsp'],[$nbf,&$data,$fnd,'-->','','']],
			'@load'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'include(', ');']],
			
			'@replace'		=> [[$this,'bop'],[$nbf,&$data,$fnd,'# = str_ireplace(', '%);','(',')',FALSE,'{','}','#','%']],
			'@define'		=> [[$this,'bof'],[$nbf,&$data,$fnd,'define(',');']],
			'@global'		=> [[$this,'bof'],[$nbf,&$data,$fnd,'global ',';']],
			
			'@set'			=> [[$this,'bof'],[$nbf,&$data,$fnd]],
			'@var'			=> [[$this,'bop'],[$nbf,&$data,$fnd,'#','=%;','(',':',FALSE,':',')','#','%']],
			'@exe'			=> [[$this,'bof'],[$nbf,&$data,$fnd]],
			'@fct'			=> [[$this,'bop'],[$nbf,&$data,$fnd,'function ']],
			'@use'			=> [[$this,'bop'],[$nbf,&$data,$fnd,'',');',':',')']],
			'@echo'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'echo ',';']],
			'@inst'			=> [[$this,'bop'],[$nbf,&$data,$fnd,'# = new ', '%;','(',')',FALSE,'{','}','#','%']],
			'@obj'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'', ';']],
			'@class'		=> [[$this,'bop'],[$nbf,&$data,$fnd,'class # ', '{%}','(',')',FALSE,'{','}','#','%']],			
			// partie dowhile ici c'est une gestion différente des dowhiles		
			'@dowhile'		=> [[$this,'bsp'],[$nbf,&$data,$fnd,"do{ echo <<<END\r\n",'<?php ','']],
			'@whiledo'		=> [[$this,'bof'],[$nbf,&$data,$fnd,"\r\nEND;\r\n}while(", ');','(',')','']],
			'@dow'			=> [[$this,'bop'],[$nbf,&$data,$fnd,'do{', '%}while(#);','(',')',';','{','}','#','%']],	
			//
			'@if'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'if(', '):']],
			'@elseif'		=> [[$this,'bof'],[$nbf,&$data,$fnd,'elseif(', '):']],
			'@else'			=> [[$this,'bsp'],[$nbf,&$data,$fnd,'else:']],
			'@endif'		=> [[$this,'bsp'],[$nbf,&$data,$fnd,'endif;']],
			'@for'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'for(','):']],
			'@endfor'		=> [[$this,'bsp'],[$nbf,&$data,$fnd,'endfor;']],
			'@foreach'		=> [[$this,'bof'],[$nbf,&$data,$fnd,'foreach(','):']],
			'@endforeach'	=> [[$this,'bsp'],[$nbf,&$data,$fnd,'endforeach;']],
			'@while'		=> [[$this,'bof'],[$nbf,&$data,$fnd,'while(','):']],
			'@endwhile'		=> [[$this,'bsp'],[$nbf,&$data,$fnd,'endwhile;']],
			'@switch'		=> [[$this,'bof'],[$nbf,&$data,$fnd,'switch (','):']],
			'@case'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'case (','):']],
			'@break'		=> [[$this,'bsp'],[$nbf,&$data,$fnd,'break;']],
			'@continue'		=> [[$this,'bsp'],[$nbf,&$data,$fnd,'continue;']],
			'@default'		=> [[$this,'bsp'],[$nbf,&$data,$fnd,'default:']],
			'@endswitch'	=> [[$this,'bsp'],[$nbf,&$data,$fnd,'endswitch;']],
			'@goto'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'goto ', ';']],
			'@label'		=> [[$this,'bof'],[$nbf,&$data,$fnd,'', ':']],
			//
			'@initab'		=> [[$this,'bsp'],[$nbf,&$data,$fnd,'$tab=array();']],
			'@say'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'$tab[', ']=null;']],	// initialise le nom de la portion de code
			'@see'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'if($tab[', ']):']],	// affiche le code si l'initialisation est à TRUE
			'@endsee'		=> [[$this,'bsp'],[$nbf,&$data,$fnd,'endif;']],		
			'@is'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'if($tab', '):']],		// affiche si on désire vérifier que l'initialisation à une autre valeur 
			'@endis'		=> [[$this,'bsp'],[$nbf,&$data,$fnd,'endif;']],
			'@on'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'$tab[', ']=true;']],	// passe l'initialisation à TRUE
			'@off'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'$tab[', ']=false;']], // passe l'initialisation à FALSE
			'@init'			=> [[$this,'bop'],[$nbf,&$data,$fnd,'$tab[#]','=%;','(',':',FALSE,':',')','#','%']],			// passe l'initialisation à la valeur de l'on désire
			'@int'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'$tab', ';']],			// passe l'initialisation à la valeur de l'on désire
			//
			'@timetest'		=> [[$this,'bsp'],[$nbf,&$data,$fnd,'$microtime_start_test = microtime(true);']],
			'@endtimetest'	=> [[$this,'bsp'],[$nbf,&$data,$fnd,'$microtime_end_test = microtime(true); echo round(($microtime_end_test - $microtime_start_test),4);']],
			//
			'@\RN'			=> [[$this,'bsp'],[$nbf,&$data,$fnd,'echo PHP_EOL;']],
			'@\R'			=> [[$this,'bsp'],[$nbf,&$data,$fnd,'echo "\r";']],
			'@\N'			=> [[$this,'bsp'],[$nbf,&$data,$fnd,'echo "\n";']],
			'@\T'			=> [[$this,'bsp'],[$nbf,&$data,$fnd,'echo "\t";']],
			'@\Z'			=> [[$this,'bsp'],[$nbf,&$data,$fnd,'echo "\0";']],
			'@\SP'			=> [[$this,'bsp'],[$nbf,&$data,$fnd,'echo chr(32);']],
			'@chr'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'chr(',');']],
			'@ord'			=> [[$this,'bof'],[$nbf,&$data,$fnd,'ord(',');']],
			//
			'@'				=> [[$this,'bof'],[$nbf,&$data,$fnd,'echo $',';','{','}']], // affiche son resulta
			
		];
		
		
		$time_start = microtime(true);

		// on charge les invoc et le import avant tout
		$rx = ['@invoc' => 0,'@import' => 0, '@load' => 0];

		foreach($rx as $fnd => &$nbf) { $nbf = substr_count($data, $fnd); }
		while( array_sum($rx) > 0 ){		
			foreach($rx as $fnd => $nbf) {
				if($nbf > 0) {
					$tags[$fnd][1][0] = $nbf;
					$tags[$fnd][1][2] = $fnd;
					call_user_func_array($tags[$fnd][0],$tags[$fnd][1]);
				}
			}
			foreach($rx as $fnd => &$nbf) { $nbf = substr_count($data, $fnd); }	
		}
		
		// on supprime les functions non présente
		foreach($tags as $fnd => $fnc) {
			if(($tags[$fnd][1][0] = substr_count($data, $fnd)) > 0) {
				$tags[$fnd][1][2] = $fnd;
			} else { unset($tags[$fnd]); }
		}
		
		// on demarre la fabrication
		foreach($tags as &$fnc) {
			call_user_func_array($fnc[0],$fnc[1]);
		}

		
		$time_end = microtime(true);
		$time = $time_end - $time_start;

		echo "execusion $time secondes\n";
		$this->_final_page = $data;
		
		
	}
	
	private function bsp($fdb,&$data,$fnd,$replace,$debx='<?php ',$endx=' ?>'){
		$data = str_ireplace($fnd,"$debx$replace$endx", $data);
	}
	
	private function bop($fdb,&$data,$find,$deb='', $fin='',$bdeb1='(',$bfin1=')',$exp=';',$bdeb2='{',$bfin2='}',$masque1=false,$masque2=false,$debx='<?php ',$endx=' ?>',$b=0){

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

								// $find,$deb='', $fin='',$bdeb1='(',$bfin1=')',$exp=';',$bdeb2='{',$bfin2='}',$masque1=false,$masque2=false,$debx=',$endx=''
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
	
	private function bof($fdb,&$data,$find,$deb='', $fin='',$bdeb='(',$bfin=')',$debx='<?php ',$endx=' ?>',$b=0){

			
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