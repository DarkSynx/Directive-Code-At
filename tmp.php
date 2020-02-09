<?php $j='test1' ?>
<?php echo (($j == 'test')? '':<<<END


<oktest affiche code html>


END
); ?>

<?php $h=FALSE ?>
<?php $h++; ?>
<?php echo $h; ?>

TESTE DE VARIABALE @!{h}

<?php define('OK', 'test DEFINE'); ?>
<?php global $a;$b;$c; ?>

<?php $microtime_start_test = microtime(true); ?>


<!-- start import:segment test1 to file : 'segment.cat' -->

	<code> HTML qui sera segmenté </code>

<?php echo OK; ?>
<?php $g = str_ireplace('E','K',OK); ?>
<?php echo $g; ?>	
	

<!-- END import:segment test1 to file : 'segment.cat' -->


<!-- start import:segment test2 to file : 'segment.cat' -->

	<code> HTML qui sera segmenté 2</code>
	
<!-- start import:segment test3 to file : 'segment.cat' -->

	<code> HTML qui sera segmenté 3</code>

<!-- END import:segment test3 to file : 'segment.cat' -->


<!-- END import:segment test2 to file : 'segment.cat' -->


<!-- start invoc file : test.cat -->
<cat>code cate html </cat>
<!-- END invoc file : test.cat -->


<?php $_tabofdirective=array(); ?>
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
<?php do{
	echo $a;
}while($a!='test'); ?>
<?php do{echo <<<END

	<html>mon code html yyyyy<?php echo PHP_EOL; ?></html>

END;
}while($a!='test'); ?>
<?php do{ echo <<<END

	<html>code html zzz</html>

END;
}while($a!='test'); ?>

<?php $_tabofdirective['content']=null; ?>
<?php $_tabofdirective['content']=false; ?>
<?php $_tabofdirective['content']=false; ?>
<?php $_tabofdirective['content']=true; ?>

<?php if($_tabofdirective['content']): ?>
	<html>code html 1<?php echo PHP_EOL; ?> <@{a}> <?php echo PHP_EOL; ?></html>
<?php endif; ?>
<?php if($_tabofdirective['content222'] == false): ?>
	mon code html 2
<?php endif; ?>
<?php goto a; ?>
<?php $b='testeRR'; 
	echo mafonction(); 
	echo $b; ?>
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