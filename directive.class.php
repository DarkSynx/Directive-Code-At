<?php 
class directive {
	
	Private $_final_page;
	Private $_data;
	
	
	// on récupére que par get_data mais on peu initialisé par le constructeur ou par set_data
	public function __construct( $data=false ) { if($data){$this->set_data($data);} }	
	public function set_data($data) { $this->_data = $data; $this->gen(); }	
	public function get_data() { return $this->_final_page; }
	
	private function tag_tab() {
		$tag = [
			['@load'		,function($data,$fnd){return $this->bof($data,$fnd,'include(', ');');}],
			//['@import'		,function($data,$fnd){return $this->bof($data,$fnd,'include(', ');');}],
			['@set'			,function($data,$fnd){return $this->bof($data,$fnd);}],
			['@exe'			,function($data,$fnd){return $this->bof($data,$fnd);}],
			['@fct'			,function($data,$fnd){return $this->bof($data,$fnd,'function ');}],
			
			// partie dowhile ici c'est une gestion différente des dowhiles		
			['@dowhile'		,function($data,$fnd){return $this->bsp($data,$fnd,"do{ echo <<<END\r\n",'<?php ','');}],
			['@whiledo'		,function($data,$fnd){return $this->bof($data,$fnd,"\r\nEND;\r\n}while(", ');','(',')','');}],
			['@dow'			,function($data,$fnd){return $this->bop($data,$fnd,'do{', '%}while(#);','(',')',';','{','}','#','%');}],	
			
			['@if'			,function($data,$fnd){return $this->bof($data,$fnd,'if(', '):');}],
			['@elseif'		,function($data,$fnd){return $this->bof($data,$fnd,'elseif(', '):');}],
			['@else'		,function($data,$fnd){return $this->bsp($data,$fnd,'else:');}],
			['@endif'		,function($data,$fnd){return $this->bsp($data,$fnd,'endif;');}],
			['@for'			,function($data,$fnd){return $this->bof($data,$fnd,'for(','):');}],
			['@endfor'		,function($data,$fnd){return $this->bsp($data,$fnd,'endfor;');}],
			['@foreach'		,function($data,$fnd){return $this->bof($data,$fnd,'foreach(','):');}],
			['@endforeach'	,function($data,$fnd){return $this->bsp($data,$fnd,'endforeach;');}],
			['@while'		,function($data,$fnd){return $this->bof($data,$fnd,'while(','):');}],
			['@endwhile'	,function($data,$fnd){return $this->bsp($data,$fnd,'endwhile;');}],
			['@switch'		,function($data,$fnd){return $this->bof($data,$fnd,'switch (','):');}],
			['@case'		,function($data,$fnd){return $this->bof($data,$fnd,'case (','):');}],
			['@break'		,function($data,$fnd){return $this->bsp($data,$fnd,'break;');}],
			['@continue'	,function($data,$fnd){return $this->bsp($data,$fnd,'continue;');}],
			['@default'		,function($data,$fnd){return $this->bsp($data,$fnd,'default:');}],
			['@endswitch'	,function($data,$fnd){return $this->bsp($data,$fnd,'endswitch;');}],
			['@goto'		,function($data,$fnd){return $this->bof($data,$fnd,'goto ', ';');}],
			['@label'		,function($data,$fnd){return $this->bof($data,$fnd,'', ':');}],
			
			
			['@say'			,function($data,$fnd){return $this->bof($data,$fnd,'$tab[', ']=null;');}],	// initialise le nom de la portion de code
			['@see'			,function($data,$fnd){return $this->bof($data,$fnd,'if($tab[', ']):');}],	// affiche le code si l'initialisation est à TRUE
			['@endsee'		,function($data,$fnd){return $this->bsp($data,$fnd,'endif;');}],		
			['@is'			,function($data,$fnd){return $this->bof($data,$fnd,'if($tab', '):');}],		// affiche si on désire vérifier que l'initialisation à une autre valeur 
			['@endis'		,function($data,$fnd){return $this->bsp($data,$fnd,'endif;');}],
			['@on'			,function($data,$fnd){return $this->bof($data,$fnd,'$tab[', ']=true;');}],	// passe l'initialisation à TRUE
			['@off'			,function($data,$fnd){return $this->bof($data,$fnd,'$tab[', ']=false;');}], // passe l'initialisation à FALSE
			['@init'		,function($data,$fnd){return $this->bof($data,$fnd,'$tab', ';');}],			// passe l'initialisation à la valeur de l'on désire
			
			
			//toujours à la fin 
			/*
			['@@'			,function($data,$fnd){return $this->bof($data,$fnd,'$', ';','[',']');}], // initialise une variable
			['@#'			,function($data,$fnd){return $this->bof($data,$fnd,'echo $', ';','(',')');}], // affiche son resulta
			*/
			
		];
		return $tag;
	}
	// debut de mes functions
	private function gen() {
		
		$data = $this->_data;
		$tag = $this->tag_tab();
		//var_dump($tag);
		$time_start = microtime(true);
		foreach($tag as $find) { 
				$d = $find[1]($data,$find[0]);
				
				//if($find[0] == '@dowhile') { sleep(1000); }
				if($d) { $data = $d; }
		}
		$time_end = microtime(true);
		$time = $time_end - $time_start;

		echo "execusion $time secondes\n";
		$this->_final_page = $data;
		//echo $data;
		
	}
	
	private function bsp($data,$fnd,$replace,$debx='<?php ',$endx=' ?>'){
		return str_ireplace($fnd,"$debx$replace$endx", $data);
	}
	
	private function bop($data,$find,$deb='', $fin='',$bdeb1='(',$bfin1=')',$exp=';',$bdeb2='{',$bfin2='}',$masque1=false,$masque2=false,$debx='<?php ',$endx=' ?>',$b=0){
		$fdb = substr_count($data, $find);
		echo ">",$fdb, PHP_EOL;
		if($fdb > 0){
			$s = strlen($find);
			while(--$fdb >= 0) {
				$b = stripos($data,$find,$b);
				$bs = ($b + $s);
				if(
					( $b !== false ) && 
					( $data[$bs] == $bdeb1 )  && 
					( ($c = stripos($data,$bfin1,$bs)) !== false ) 
				) {  
				echo ">ok", PHP_EOL;
							
							$k = substr_count( substr($data,$bs,($c - $bs)) , $bdeb1);
							
							$c--; 
							while( $k-- > 0 ) $c = stripos($data,$bfin1,++$c);
								
								// detection du premier { et dernier }
								if(
								 ($data[($c+1)] == $bdeb2) &&
								 (($e = stripos($data,$bfin2,$c+1)) !== false ) 
								) {
									echo ">> extrait:", substr($data,$c+1,($e - $c+1)), PHP_EOL;
									
									$o = substr_count( substr($data,$c+1,($e - $c+1)) , $bdeb2);
									echo ">> ", $data[($c+1)], PHP_EOL;
									echo ">> o : ", $o, PHP_EOL;
									
									$e--; 
									while( $o-- > 0 ) $e = stripos($data,$bfin2,++$e);
									$m2 = substr($data,$c+2,($e - $c-2)); //trim ?

									
								} else { return false; }

								echo "#>", $m2, PHP_EOL;
								$mex = trim( substr($data,($bs + 1),( $c - ($bs + 1)) ) );
								if(($j = stripos($mex,$exp,0)) !== false) {
									$t = explode($exp,$mex);
									echo "#:",$t[1],PHP_EOL;
									if($t[1] == 'TRUE') {
										$m1 = $t[0];
									}
								} else { $m1 = $mex; $m2 = "echo <<<END\r\n$m2\r\nEND;\r\n"; }
								if($masque1) { 
									$rpl = "$debx$deb$fin$endx";
									$rpl = str_ireplace($masque1,$m1,$rpl);
									if($masque2) { 
										$rpl = str_ireplace($masque2,$m2,$rpl);
									}
								}
								
								
							
								//echo '>>>',$rpl, PHP_EOL;
							//sleep(1000);
							
							$data = substr_replace($data, $rpl, $b, (($e - $b) +1) );
							$b += strlen($rpl);

				
				} else { return false; }

			}
			return $data;
		}
		return false;
	}
	
	private function bof($data,$find,$deb='', $fin='',$bdeb='(',$bfin=')',$debx='<?php ',$endx=' ?>',$b=0){
		$fdb = substr_count($data, $find);
		if($fdb > 0){
			
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
			
			return $data;
		}
		return false;
	}
	
}