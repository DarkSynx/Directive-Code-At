
phrase qui s'affiche
<?php $a = 'test' ?>
<?php $tab['content']=null; ?>
<?php $tab['content']=false; ?>
<?php $tab['content'] = true; ?>
<?php if($tab['content']): ?>
	mon code html 1 
<?php endif; ?>
<?php if($tab['content'] == false): ?>
	mon code html 2
<?php endif; ?>
<?php goto a; ?>
<?php echo mafonction() ?>
<?php function mafonction() { 
	return 'test';
} ?>
<?php a: ?>
<?php if($a == 'test'): ?>
ok c'est cool 111111
<?php elseif($a == 'toto'): ?>
<?php else: ?>
non c'est pas cool 111111
<?php endif; ?>