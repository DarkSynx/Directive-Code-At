# C@t CODE at.
<img src='logo_.png' /><br/>

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
* __@set__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@var__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@exe__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@fct__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@use__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@print__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@echo__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@inst__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@obj__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@class__
*Explication de la fonction*
```LUA
Exemple de code
```
[Retour au Menu](./README.md#menu)

---
## do while
* __@dowhile__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@whiledo__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@dow__
*Explication de la fonction*
```LUA
Exemple de code
```
[Retour au Menu](./README.md#menu)

---
## structures de code
* __@if__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@elseif__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@else__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@endif__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@for__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@endfor__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@foreach__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@endforeach__
*Explication de la fonction*
```LUA
Exemple de code
```
	
* __@while__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@endwhile__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@switch__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@case__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@break__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@continue__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@default__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@endswitch__
*Explication de la fonction*
```LUA
Exemple de code
```
	
* __@goto__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@label__
*Explication de la fonction*
```LUA
Exemple de code
```
[Retour au Menu](./README.md#menu)

---
## controle de code
* __@initab__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@say__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@see__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@endsee__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@is__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@endis__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@on__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@off__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@init__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@int__
*Explication de la fonction*
```LUA
Exemple de code
```
[Retour au Menu](./README.md#menu)

---
## fonctions diverses
* __@timetest__
*Explication de la fonction*
```LUA
Exemple de code
```
		
* __@endtimetest__
*Explication de la fonction*
```LUA
Exemple de code
```
	

* __@\RN__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@\R__
*Explication de la fonction*
```LUA
Exemple de code
```
			
* __@\N__
*Explication de la fonction*
```LUA
Exemple de code
```
			

* __@__
*Explication de la fonction*
```LUA
Exemple de code
```
				
