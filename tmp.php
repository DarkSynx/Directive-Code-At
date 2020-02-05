<!--SEGMENT:test
	<code> HTML qui sera segmenté </code>
-->

<!-- start import:segment test to file : 'page.cat' -->

	<code> HTML qui sera segmenté </code>

<!-- END import:segment test to file : 'page.cat' -->


<!-- start invoc file : test.cat -->
<cat>code cate html </cat>
<!-- END invoc file : test.cat -->

<?php $microtime_start_test = microtime(true); ?>
<?php $tab=array(); ?>
<?php class maclass {
	
	public function get_var() {
		echo 'ok';
	}
} ?>
<?php $b = new maclass(); ?>
<?php $b->get_var(); ?>

phrase qui s'affiche
<?php $a='test' ?>
<?php $a='test'; ?>

<?php do{echo <<<END

	<html>mon code html yyyyy<?php echo PHP_EOL; ?></html>

END;
}while($a!='test'); ?>
<?php do{ echo <<<END

	<html>code html zzz</html>

END;
}while($a!='test'); ?>

<?php $tab['content']=null; ?>
<?php $tab['content']=false; ?>
<?php $tab['content']=false; ?>
<?php $tab['content']=true; ?>

<?php if($tab['content']): ?>
	<html>code html 1<?php echo PHP_EOL; ?> <<?php echo $a; ?>> <?php echo PHP_EOL; ?></html>
<?php endif; ?>
<?php if($tab['content222'] == false): ?>
	mon code html 2
<?php endif; ?>
<?php goto a; ?>
<?php echo mafonction() ?>
<?php function mafonction() { 
	return 'test retrun';
} ?>
<?php a: ?>
<?php mafonction(); ?>
<?php echo mafonction(); ?>
<?php echo $a; ?>


<?php if($a == 'test'): ?>
ok c'est cool 111111
<?php elseif($a == 'toto'): ?>
<?php else: ?>
non c'est pas cool 111111
<?php endif; ?>
<?php echo PHP_EOL; ?>
execution : <?php $microtime_end_test = microtime(true); echo round(($microtime_end_test - $microtime_start_test),4); ?>
<?php echo PHP_EOL; ?>