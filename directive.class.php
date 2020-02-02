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
			['@load'		,function($data,$fnd){return $this->bof($data,$fnd,'include(', ');');}],
			['@set'			,function($data,$fnd){return $this->bof($data,$fnd);}],
			['@exe'			,function($data,$fnd){return $this->bof($data,$fnd);}],
			['@fct'			,function($data,$fnd){return $this->bof($data,$fnd,'function');}],
			
			['@if'			,function($data,$fnd){return $this->bof($data,$fnd,'if(', '):');}],
			['@elseif'		,function($data,$fnd){return $this->bof($data,$fnd,'elseif(', '):');}],
			['@else'		,function($data,$fnd){return $this->bsp($data,$fnd,'else:');}],
			['@endif'		,function($data,$fnd){return $this->bsp($data,$fnd,'endif;');}],
			['@for'			,function($data,$fnd){return $this->bof($data,$fnd,'for(','):');}],
			['@endfor'		,function($data,$fnd){return $this->bsp($data,$fnd,'endfor;');}],
			['@foreach'		,function($data,$fnd){return $this->bof($data,$fnd,'foreach(','):');}],
			['@endforeach'		,function($data,$fnd){return $this->bsp($data,$fnd,'endforeach;');}],
			['@while'		,function($data,$fnd){return $this->bof($data,$fnd,'while(','):');}],
			['@endwhile'		,function($data,$fnd){return $this->bsp($data,$fnd,'endwhile;');}],
			['@switch'		,function($data,$fnd){return $this->bof($data,$fnd,'switch (','):');}],
			['@case'		,function($data,$fnd){return $this->bof($data,$fnd,'case (','):');}],
			['@break'		,function($data,$fnd){return $this->bsp($data,$fnd,'break;');}],
			['@continue'		,function($data,$fnd){return $this->bsp($data,$fnd,'continue;');}],
			['@default'		,function($data,$fnd){return $this->bsp($data,$fnd,'default:');}],
			['@endswitch'		,function($data,$fnd){return $this->bsp($data,$fnd,'endswitch;');}],
			['@goto'		,function($data,$fnd){return $this->bof($data,$fnd,'goto ', ';');}],
			['@label'		,function($data,$fnd){return $this->bof($data,$fnd,'', ':');}],
			['@see'			,function($data,$fnd){return $this->bof($data,$fnd,'if($tab[', ']):');}],
			['@endsee'		,function($data,$fnd){return $this->bsp($data,$fnd,'endif;');}],
			
			['@is'			,function($data,$fnd){return $this->bof($data,$fnd,'if($tab', '):');}],
			['@endis'		,function($data,$fnd){return $this->bsp($data,$fnd,'endif;');}],
			['@say'			,function($data,$fnd){return $this->bof($data,$fnd,'$tab[', ']=null;');}],
			['@on'			,function($data,$fnd){return $this->bof($data,$fnd,'$tab[', ']=true;');}],
			['@off'			,function($data,$fnd){return $this->bof($data,$fnd,'$tab[', ']=false;');}],
			['@tab'			,function($data,$fnd){return $this->bof($data,$fnd,'$tab', ';');}],
			
			
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
		foreach($tag as $find) { 
				$data = $find[1]($data,$find[0]);
		}
		
		$this->_final_page = $data;
		//echo $data;
		
	}
	private function bsp($data,$fnd,$replace){
		return str_ireplace($fnd,'<?php ' . $replace . ' ?>', $data);
	}

	private function bof($data,$find,$deb='', $fin='',$bdeb='(',$bfin=')'){
		$fdb = substr_count($data, $find);
		if($fdb > 0){
			
			$stf = strlen($find);
				
			while(--$fdb >= 0) { 
				
				$b = stripos($data,$find,$a);
				if(($b !== false) && ( $data[ ($b + $stf) ] == $bdeb )  ) {  

					$c = stripos($data,$bfin,($b + $stf));
					if($c !== false) {
							

							$k = substr_count(  substr($data,($b + $stf),($c - ($b + $stf))) , $bdeb);
							for($g=0, $c--; $g < $k; $g++) {
								$c = stripos($data,$bfin,$c+1);
							}
							
		
							$endz = '<?php' . 
							
									($deb ? (chr(32) . $deb):chr(32)) . 
									
									trim(substr($data,($b + $stf + 1),( $c - ($b + $stf + 1)) )) . 
									
									($fin ? ($fin . chr(32)):chr(32)) . 
									
									'?>';
							
							
							$data = substr_replace($data, $endz, $b, (($c - $b) +1) );
							$a = $b + strlen($endz);
							
					
					}
				
				} else { return false; }

			}
			
			return $data;
		}
		return false;
	}
	
}
