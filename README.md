# C@t CODE at.
<img src='logo.gif' /><br/>

# MENU
## [Les importations](./README.md#les-importations)
## [les executions et déclarations](./README.md#execution-et)
## [Les do while](./README.md#do-while)
## [Les structures de code](./README.md#structures-de-code)
## [Les controles de code](./README.md#controle-de-code)
## [Les fonctions diverse](./README.md#fonctions-diverses)

# importation et controle de code
## les importations 
* __@invoc__  
*Permet d'importer physiquement tout le code du fichier qui sera réécrit dans le fichier final aprés la fabrication. (notez qu'il ne faudra pas utilisé de cote ou de double cote dans la fonction)*
```LUA
@invoc(MyFile.cat)
```
* __@load__  
*Permet de charger un fichier PHP en utilisant include de PHP*
```LUA
@load('foobar.php')
```
			
* __@import__  
*Permet d'importer du code HTML d'un Fichier ___".cat"___ celui-ci balisé par @segment(nomdesegment) @endsegment. (notez que <nomdesegment> n'a pas de cote ou de double-cote alors que le nom du fichier oui*
```LUA
@import('page.cat'){nomdesegment}
```
		
* __@segment__  
*Cette directive permet de créé des pages __".cat"__ pour importer des portions de code HTML. (Note: cette directive, quand elle est executé met le code HTML en __commentaire HTML___. Il est donc conseiller de ne pas mélanger les pages contenant __les Segments__ et les pages avec les autres directive pour ne pas surcharger vos pages. Note 2: il n'y as pas de cote ou double-cote à un nom de segment celui-ci doit étre unique ) *
```LUA
@segment(nomdesegment)
<html>
<head></head>
<body>TEST</body>
</html>
@endsegment
```

[Retour au Menu](./README.md#menu)

---
## execution et
* __@set__ __@var__  
*ces deux directives permettes d'initialisé des variables à vous de d'utilisé celle qui vous conviendra*
```LUA
@set(a='test')
@var(a::'test')
```

* __@exe__  
*Cette directive permet d'executé du code PHP *
```LUA
@exe( echo mafonction(); )
```

* __@fct__  
*Permet de définir une fonction*
```LUA
@fct( mafonction() { 
	return 'foobar';
})
```

* __@use__  
*Permet d'executé une fonction définit*
```LUA
@use:mafonction()
```

* __@print__  
*Vous voulez afficher le résulta d'une variable*
```LUA
@set(a=3)
@print(a)
```

* __@__  
*Directive pour afficher des variables comme print mais sera plus simple d'utilisation. (Note: Il est possible que cette directive évolue si elle entre en conflic dans le futur donc utilisez __@print__) *
```LUA
@set(a=30)
<html>code html @{a} </html>
retourne <html>code html 30 </html>
```

* __@echo__  
*Affiche le retour d'une fonction*
```LUA
@fct( mafonction() { 
	return 'foobar';
})
@echo(mafonction())
```

* __@class__  
*Créé une class*
```LUA
@class(maclass){
	
	public function get_var() {
		echo 'ok';
	}
}
```
* __@inst__  
*Instancier ma class du nom de __maclass__ dans une variable qui dans l'exemple si dessous sera __b__*
```LUA
@inst(b){maclass()}
```

* __@obj__  
*Utilisation des fonctions de ma class prélablement instancier dans une variable si dessous __b__*
```LUA
@obj(b->get_var())
```

[Retour au Menu](./README.md#menu)

---
## do while
* __@dowhile__  __@whiledo__ 
*Cette Directive permet d'afficher le code HTML selon le context du DoWhile __Exclusivement avec du code HTML_ *
```LUA
@dowhile
	<html>code html zzz</html>
@whiledo(a!='test')
```

* __@dow__  
*Cette directive est un Dowhile qui fonctionne comme __@dowhile__ __@whiledo__ mais qui sera présentant différement. ainsi qu'elle est compatible avec du code PHP si on ajoute un __;TRUE__ comme dans l'exemple si dessous*
```LUA
@dow(a!='test'){
	<html>mon code html yyyyy@\RN</html>
}
ou
@dow(a!='test';TRUE){
	echo 'foobar';
}
```
[Retour au Menu](./README.md#menu)

---
## structures de code
* __@if__ __@elseif__ __@else__ __@endif__  
*Ces directives sont là pour offire une stucture de code qui utilise __IF/ELSEIF/ELSE__ comme en __PHP__*
```LUA
@if(a == 'test')
 <foo>BAR</foo>
@endif

ou

@if(a == 'test')
<foo>BAR</foo>
@else
<bar>FOO</bar>
@endif

ou

@if(a == 'test')
<foo>BAR</foo>
@elseif(a == 'toto')
<foobar>rox</foobar>
@else
<bar>FOO</bar>
@endif
```

* __@for__ __@endfor__  
*La boucle for est similaire à la boucle for en PHP (Note: notez qu'il faudra parcontre ne pas oublier les __$__ bien que l'on peut s'en passé dans certain cas avec C@t-code il n'y a pas encore de fonction suffisement avancé pour nous passer des __$__ mais cela reste un rare cas de figure avec __@foreach__ __@while__ et __@switch__ *
```LUA
@for($a=0; $a < $b; $a++)
<foo>BAR</foo>
@endfor
```

* __@foreach__ __@endforeach__  
*Directive similaire au Foreach de PHP*
```LUA
@set(tableau = [1,2,3,4])
@foreach($tableau as $val)
<foo>@{val}</foo>
@endforeach
```

* __@while__  __@endwhile__
*Directive similaire au While de PHP*
```LUA
@while($a < 3)
<foo>BAR</foo>
@endwhile
```

* __@switch__ __@case__ __@break__ __@continue__ __@default__ __@endswitch__  
*Directive similaire au Switch de PHP*
```LUA
@switch($a)
@case(1)
<foo>BAR</foo>
@break
@default
<foo>BAR2</foo>
@endswitch
```


* __@goto__ __@label__  
*Directive pour Aller à label*
```LUA
@goto(a)
<foo>BAR</foo>
@label(a)
<foo>BAR2</foo>

retournera <foo>BAR2</foo>
```

[Retour au Menu](./README.md#menu)

---
## controle de code
*Note: toute cette partie sont des connecteurs pour controller l'affichache de code HTML*
* __@initab__  
*Cette directive va initialisé un tableau qui contiandra des nom de variable qui permettrons d'afficher ou pas les portions de codes. (Note: n'oubliez pas d'initialisé le tableau en début de page mais s'il est déjà initialisé vous risquez de supprimer les valeurs)*
```LUA
@initab
```

* __@say__  
*Cette directive ajoute au tableau le label de la portion de code. (Note: attention __'MaPortionDeCode'__ dans le tableau est initialisé à NULL et non à False pensé bien à faire un __@off('MaPortionDeCode')__*
```LUA
@say('MaPortionDeCode')
```

* __@see__ __@endsee__  
*Cette directive permet d'afficher la Portion de Code si dans le tableau la clé __'MaPortionDeCode'__ est à true*
```LUA
@see('MaPortionDeCode')
	<html>mon code html</html>
@endsee
```

* __@is__ __@endis__  
*Cette Directive similaire à __@see__ __@endsee__ permet quand à elle d'aller plus loin et de vérifier si la Clé du tableau est à une autre valeur. (Note: attention à bien ajouter les Crochés et les simple cotes sinon ça ne fonctionnera pas)*
```LUA
@is(['MaPortionDeCode'] == 5 )
	mon code html 2
@endis
```

* __@on__  
*Cette Directive va passé dans le Tableau la clé __'MaPortionDeCode'__ à TRUE *
```LUA
@on('MaPortionDeCode')
```

* __@off__  
*Cette Directive va passé dans le Tableau la clé __'MaPortionDeCode'__ à FALSE *
```LUA
@off('MaPortionDeCode')
```

* __@init__ __@int__  
*Directives qui vont définir dans le Tableau la clé __'MaPortionDeCode'__ à la valeur que l'on désire. Vous avez deux synthaxe pour définir à la valeur que vous désirez.(Note: attention avec __@init__ il n'est pas obligatoir d'ajouter des Crochets)*
```LUA
@int(['MaPortionDeCode']=489)
ou 
@init('MaPortionDeCode'::489)
```

[Retour au Menu](./README.md#menu)

---
## fonctions diverses
* __@timetest__ __@endtimetest__  
*Cette Directive permet de placer un testeur de temps d'execution. Vous devez placer __@timetest__ au début et __@endtimetest__ à la fin de la portion que vous voulez analyser*
```LUA
@timetest
....mon code 
@endtimetest
```

* __@\RN__  
*Directive qui permet de placer un retour à la ligne comme sous windows __\r\n__ sous PHP il sera utilisé __PHP_EOL__ (Note: le retour à la ligne ne sera pas visible en HTML pour cela utilisé __\<br\/\>__)*
```LUA
<bar> Foo de mon texte @\RN seconde ligne </bar>
```

* __@\R__  
*Directive qui permet de placer un "\r" __CR (carriage return)__*
```LUA
<bar> Foo de mon texte @\R mon autre texte </bar>
```

* __@\N__  
*Directive qui permet de placer un "\n" __LF (Line Feed)__*
```LUA
<bar> Foo de mon texte @\N retour à la ligne </bar>
```



