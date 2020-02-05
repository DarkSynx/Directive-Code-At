# C@t Code
<img src='logo.png' /><br/>


init.php is first page  

	phrase qui s'affiche
	@set( $a = 'test')
	@exe( echo mafonction() )
	@fct( mafonction() { 
		return 'test';
	})
	@if($a == 'test')
	ok c'est cool
	@elseif( $a == 'toto')
	@else
	non c'est pas cool
	@endif
  
  to 
```
phrase qui s'affiche
<?php  $a = 'test' ?>
<?php  echo mafonction()  ?>
<?php function  mafonction() { 
	return 'test';
} ?>
<?php if( $a == 'test'):  ?>
ok c'est cool
<?php elseif(  $a == 'toto'):  ?>
<?php else: ?>
non c'est pas cool
<?php endif; ?>
