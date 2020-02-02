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
			['@load'	,function($data,$fnd){return $this->bof($data,$fnd,'include(', ');');}],
			['@set'		,function($data,$fnd){return $this->bof($data,$fnd);}],
			['@exe'		,function($data,$fnd){return $this->bof($data,$fnd);}],
			['@fct'		,function($data,$fnd){return $this->bof($data,$fnd,'function');}],
			['@if'		,function($data,$fnd){return $this->bof($data,$fnd,'if(', '):');}],
			['@elseif'	,function($data,$fnd){return $this->bof($data,$fnd,'elseif(', '):');}],
			['@else'	,function($data,$fnd){return $this->bsp($data,$fnd,'else:');}],
			['@endif'	,function($data,$fnd){return $this->bsp($data,$fnd,'endif;');}],
			['@for'		,function($data,$fnd){return $this->bof($data,$fnd,'for(','):');}],
			['@endfor'	,function($data,$fnd){return $this->bsp($data,$fnd,'endfor;');}],
			['@foreach'	,function($data,$fnd){return $this->bof($data,$fnd,'foreach(','):');}],
			['@endforeach'	,function($data,$fnd){return $this->bsp($data,$fnd,'endforeach;');}],
			['@while'	,function($data,$fnd){return $this->bof($data,$fnd,'while(','):');}],
			['@endwhile'	,function($data,$fnd){return $this->bsp($data,$fnd,'endwhile;');}],
			['@switch'	,function($data,$fnd){return $this->bof($data,$fnd,'switch (','):');}],
			['@case'	,function($data,$fnd){return $this->bof($data,$fnd,'case (','):');}],
			['@break'	,function($data,$fnd){return $this->bsp($data,$fnd,'break;');}],
			['@continue'	,function($data,$fnd){return $this->bsp($data,$fnd,'continue;');}],
			['@default'	,function($data,$fnd){return $this->bsp($data,$fnd,'default:');}],
			['@endswitch'	,function($data,$fnd){return $this->bsp($data,$fnd,'endswitch;');}],
			['@goto'	,function($data,$fnd){return $this->bof($data,$fnd,'goto ', ';');}],
			['@label'	,function($data,$fnd){return $this->bof($data,$fnd,'', ':');}],
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
			$lnd = strlen($data);
			$fdb = substr_count( $data, $find);
			for($a=$b; $a < $lnd ; $a++) {
				$b = stripos($data,$find,$a);
				if(($b !== false) and ( $data[ ($b + strlen($find)) ] == $bdeb )  ) {  
					$a = ($b + strlen($find)); 
					
						// on cherche le prochin ) 
						$c = stripos($data,$bfin,$a);
						if($c !== false) {
							
							$extrait = substr($data,$a,($c - $a));
							$a = $c-1;
							// on vérifie qu'il n'y a pas de ( entre les deux
							// permet de savoir combien de fois on va bouclé pour trouver la fin 
							$ouverture = substr_count( $extrait, $bdeb);
							
								for($g=0; $g < $ouverture; $g++) {
									$a = stripos($data,$bfin,$a+1);
								}

							// dans tout les cas $a contiendra la bonne position du dernier ')'
							$block = substr($data,$a,1);
							$endz = ($fin ? ($fin . chr(32)):null) . ' ?>';
							$data = substr_replace($data, $endz, $a,1);
							$a += strlen($endz);
							
							
						}
						
				}
				$lnd = strlen($data);
				if( --$fdb <= 0) { break; }	
			}
			$data = str_ireplace($find . $bdeb,'<?php' . chr(32) . ($deb ? ($deb . chr(32)):null) ,$data);
			return $data;
	}
	
}
