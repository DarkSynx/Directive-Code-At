DIRECTIVE Code At
=================
LE HTML FACILE SANS BALISE 

# <img src='logo2.png' /><br/> PHP 7.4

###### USE DIRECTIVE.class.php
```PHP
<?php
	
	$userpage = new directive('userpage.cat');
	include $userpage->cache_it();
	
?>
```

Exemple code :
```PHP
new options...

@div[$foo]( id='bar' ){ foobar }
// if $foo is true 
<div>foobar</div>
// else no div

@p[&foo]( id='bar' ){ click me }
// <a href="#foo"><p>click me</p></a>

@div[#idname]  add to attribut 
@div[.classname]


@loadsegment('..myfile.segment'){mysegment}
@p[mysegment,{{foo}}]( id='bar' ){ click me }
// load mysegment and replace {{foo}} tag in mysegment by <p>click me</p>

@div[+Flex,<direction>,<wrap>,<justify>,<align-items>,<align-content>]
//default is flex-direction: row; flex-wrap: nowrap; justify-content: flex-start; align-items: stretch; align-content: stretch;
@div[+Flex] // use default row;nowrap;flex-start;stretch;stretch;
@div[+Flex,column] // use column
@div[+Flex,column,nowrap,center,center,center] //you want to use spécifique flex
@div[+Flex=flex-direction: row; flex-wrap: nowrap] //you want to use spécifique flex
@div[+Flex.myclassname] // use spécifique flex class
// use +Flex not +flex 

```
```PHP
 @!DOCTYPE(html)
  @headpage
    @title(foobarpage) 
  @bodypage
   
    @div(id='foobar'){ 
      
        @:{ $USER['foo'] = true; $a='foo'; $b='bar';}
        
        @if($USER['foo'])
            foo @{b}
         @else
            @{a} bar 
         @endif
         
    }
  @endpage 
```
###### LISTING DIRECTIVE
```PHP
@include( 'link file.php' ) 

CB: @structure( 'link file.seg' ){ 
  |TITLE~'MYTITLE'
  |DATA~@getfile( 'file.seg' )
} 
<BODY>{{TITLE}}{{DATA}}</BODY>

NN: @getfile( 'link file.seg' )
CB: @getsegment( 'link file.seg' ){ name segment }
CB: @phpsegment( 'link file.seg' ){ name segment }
NN: @setsegment( name segment )
NN: @endsegment

NN: @dowhile // only html
    <body> foobar </body>
NN: @whiledo( operation )

//only php
CB: @dow( operation ){ PHP synthax }


NN: @PHP{ PHP synthax }
NN: @JSR{ JavaScript synthax width '$(document).ready' }
NN: @JS{ JavaScript synthax }

// JSR+ and JS+ will harvest it all and combine it all into one <script></script>
NN: @JSR+{ JavaScript synthax  } 
NN: @JS+{ JavaScript synthax  }

NN: @if( PHP synthax  )
    <body> foo </body>
NN: @elseif( PHP synthax  )
    <body> boo </body>
NN: @else
    <body> bar </body>
NN: @endif


NN: @foreach( PHP synthax )
    <body> foobar </body>
NN: @endforeach

NN: @for( PHP synthax )
  <body> foobar </body>
NN: @endfor

NN: @while( PHP synthax )
  <body> foobar </body>
NN: @endwhile

NN: @switch( PHP synthax )
NN: @case( PHP synthax )
    <body> foobar1 </body>
NN: @break
NN: @continue
NN: @default
    <body> foobar2 </body>
NN: @endswitch


NN: @goto( 'name label' )
NN: @label( 'name label' )

NN: @isTRUE( PHP synthax )
    <body> foobar </body>
NN: @endisTRUE

NN: @isfalse( PHP synthax )
    <body> foobar </body>
NN: @endisfalse

NN: @sessionstart // PHP session start

NN: @timetest // test time execut
    <body> foobar </body>
NN: @endtimetest

NN: @headpage // <html></head>
    <link rel="icon" type="image/svg+xml" href="atomes/foobar.svg">
NN: @bodypage // </head><body>
    <body> foobar </body>
NN: @endpage  // </body></html>

CB: @html( attributes ){ CAT or HTML Syntax }

NN: @head{ CAT or HTML Syntax }

NN: @title( 'String title' )
NN: @meta( attributes )

NN: @link( attributes ) // exemple @link( rel="stylesheet" type="text/css" href="foo.css" )
NN: @filecss( 'href css file' ) // similar to <link rel="stylesheet" type="text/css" href="foo.css"> exemple : @filecss('foo/bar.css')

NN: @script+( attributes ) 
NN: @script( 'src script file' ) // @script( 'fobar.js' )

NN: @style+{ CSS Syntax } // similar to JS+ and JSR+ combine it all @style+ into one <style></style>
NN: @style{ CSS Syntax }


CB: @body(  attributes ){ CAT or HTML Syntax }
CB: @blockquote( attributes ){ CAT or HTML Syntax }
CB: @figcaption(  attributes ){ CAT or HTML Syntax }
CB: @colgroup(  attributes ){ CAT or HTML Syntax }
CB: @datalist( attributes ){ CAT or HTML Syntax }
CB: @fieldset(  attributes ){ CAT or HTML Syntax }
CB: @noscript(  attributes ){ CAT or HTML Syntax }
CB: @optgroup( attributes ){ CAT or HTML Syntax }
CB: @progress(  attributes ){ CAT or HTML Syntax }
CB: @textarea(  attributes ){ CAT or HTML Syntax }

NN: @!DOCTYPE( attributes )

CB: @address(  attributes ){ CAT or HTML Syntax }
CB: @article(  attributes ){ CAT or HTML Syntax }
CB: @caption(  attributes ){ CAT or HTML Syntax }
CB: @command( attributes ){ CAT or HTML Syntax }
CB: @details(  attributes ){ CAT or HTML Syntax }
CB: @section(  attributes ){ CAT or HTML Syntax }
CB: @summary(  attributes ){ CAT or HTML Syntax }
CB: @button(  attributes ){ CAT or HTML Syntax }
CB: @canvas(  attributes ){ CAT or HTML Syntax }
CB: @figure(  attributes ){ CAT or HTML Syntax }
CB: @footer(  attributes ){ CAT or HTML Syntax }
CB: @header(  attributes ){ CAT or HTML Syntax }
CB: @hgroup(  attributes ){ CAT or HTML Syntax }
CB: @iframe(  attributes ){ CAT or HTML Syntax }
CB: @keygen(  attributes ){ CAT or HTML Syntax }
CB: @legend(  attributes ){ CAT or HTML Syntax }
CB: @object(  attributes ){ CAT or HTML Syntax }
CB: @option(  attributes ){ CAT or HTML Syntax }
CB: @output(  attributes ){ CAT or HTML Syntax }
CB: @select(  attributes ){ CAT or HTML Syntax }
CB: @source(  attributes ){ CAT or HTML Syntax }
CB: @strong(  attributes ){ CAT or HTML Syntax }
CB: @center(  attributes ){ CAT or HTML Syntax }
CB: @aside(  attributes ){ CAT or HTML Syntax }
CB: @audio(  attributes ){ CAT or HTML Syntax }
CB: @embed(  attributes ){ CAT or HTML Syntax }

NN: @input(attributes )

CB: @label(  attributes ){ CAT or HTML Syntax }
CB: @meter(  attributes ){ CAT or HTML Syntax }
CB: @param(  attributes ){ CAT or HTML Syntax }
CB: @small(  attributes ){ CAT or HTML Syntax }
CB: @table(  attributes ){ CAT or HTML Syntax }
CB: @tbody(  attributes ){ CAT or HTML Syntax }
CB: @tfoot(  attributes ){ CAT or HTML Syntax }
CB: @thead(  attributes ){ CAT or HTML Syntax }
CB: @title(  attributes ){ CAT or HTML Syntax }
CB: @track(  attributes ){ CAT or HTML Syntax }
CB: @video(  attributes ){ CAT or HTML Syntax }
CB: @abbr(  attributes ){ CAT or HTML Syntax }
CB: @area(  attributes ){ CAT or HTML Syntax }
CB: @base(  attributes ){ CAT or HTML Syntax }
CB: @cite(  attributes ){ CAT or HTML Syntax }
CB: @code(  attributes ){ CAT or HTML Syntax }
CB: @form(  attributes ){ CAT or HTML Syntax }
CB: @mark(  attributes ){ CAT or HTML Syntax }
CB: @math(  attributes ){ CAT or HTML Syntax }
CB: @menu(  attributes ){ CAT or HTML Syntax }
CB: @ruby(  attributes ){ CAT or HTML Syntax }
CB: @samp(  attributes ){ CAT or HTML Syntax }
CB: @span(  attributes ){ CAT or HTML Syntax }
CB: @time(  attributes ){ CAT or HTML Syntax }
CB: @bdo(  attributes ){ CAT or HTML Syntax }
CB: @col(  attributes ){ CAT or HTML Syntax }
CB: @del(  attributes ){ CAT or HTML Syntax }
CB: @dfn(  attributes ){ CAT or HTML Syntax }
CB: @div(  attributes ){ CAT or HTML Syntax }
CB: @img(  attributes ){ CAT or HTML Syntax }
CB: @ins(  attributes ){ CAT or HTML Syntax }
CB: @kbd(  attributes ){ CAT or HTML Syntax }
CB: @map(  attributes ){ CAT or HTML Syntax }
CB: @nav(  attributes ){ CAT or HTML Syntax }
CB: @pre(  attributes ){ CAT or HTML Syntax }
CB: @sub(  attributes ){ CAT or HTML Syntax }
CB: @sup(  attributes ){ CAT or HTML Syntax }
CB: @svg(  attributes ){ CAT or HTML Syntax }
CB: @var(  attributes ){ CAT or HTML Syntax }
CB: @wbr(  attributes ){ CAT or HTML Syntax }

NN: @br

CB: @dd(  attributes ){ CAT or HTML Syntax }
CB: @dl(  attributes ){ CAT or HTML Syntax }
CB: @dt(  attributes ){ CAT or HTML Syntax }
CB: @em(  attributes ){ CAT or HTML Syntax }
CB: @h1(  attributes ){ CAT or HTML Syntax }
CB: @h2(  attributes ){ CAT or HTML Syntax }
CB: @h3(  attributes ){ CAT or HTML Syntax }
CB: @h4(  attributes ){ CAT or HTML Syntax }
CB: @h5(  attributes ){ CAT or HTML Syntax }
CB: @h6(  attributes ){ CAT or HTML Syntax }

NN: @hr( attributes )

CB: @li(  attributes ){ CAT or HTML Syntax }
CB: @ol(  attributes ){ CAT or HTML Syntax }
CB: @rp(  attributes ){ CAT or HTML Syntax }
CB: @rt(  attributes ){ CAT or HTML Syntax }
CB: @td(  attributes ){ CAT or HTML Syntax }
CB: @th(  attributes ){ CAT or HTML Syntax }
CB: @tr(  attributes ){ CAT or HTML Syntax }
CB: @ul(  attributes ){ CAT or HTML Syntax }
CB: @a(  attributes ){ CAT or HTML Syntax }
CB: @b(  attributes ){ CAT or HTML Syntax }
CB: @i(  attributes ){ CAT or HTML Syntax }
CB: @p(  attributes ){ CAT or HTML Syntax }
CB: @q(  attributes ){ CAT or HTML Syntax }

NN: @:{ value 1 } // Alias of @PHP for small phpcode exemple: @:{ $_SESSION['foo'] = 'bar'; }
NN: @. // concatenation @{test}@.@{test2}

NN: @?< value 1 > // comment CAT 
NN: @!< value 1 > // comment HTML
NN: @*< value 1 > // comment PHP

NN: @>{ value 1 } // for test use @>{$test} equal to echo $test
NN: @{ Name Var PHP } // for $test use @{test} equal to echo $test



```
