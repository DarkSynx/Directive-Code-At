# <img src='logo.png' /><br/> PHP 7.4

###### LISTING DIRECTIVE
correct use of syntax

| Bad Syntax|
|:---|
| @div{  @i(class='icon-font-foobar') } |

-> if you use a directive with curly brackets natively, you must use curly brackets

| Good Syntax|
|:---|
| @div{  @i(class='icon-font-foobar'){} } |
| @div{  @i(class='icon-font-foobar'){foobar} } |
| @div{  @i{ foobar } } |

```PHP
@include( 'link file.php' ) 

CB: @structure( 'link file.seg' ){ 
  |TITLE~'MYTITLE'
  |DATA~@getfile( 'file.seg' )
} 
<BODY>{{TITLE}}{{DATA}}</BODY>

@getfile( 'link file.seg' )
CB: @getsegment( 'link file.seg' ){ name segment }
CB: @phpsegment( 'link file.seg' ){ name segment }
@setsegment( name segment )
@endsegment

@dowhile // only html
<body> foobar </body>
@whiledo( operation )

//only php
CB: @dow( operation ){ PHP synthax }


@PHP{ PHP synthax }
@JSR{ JavaScript synthax width '$(document).ready' }
@JS{ JavaScript synthax }

// JSR+ and JS+ will harvest it all and combine it all into one <script></script>
@JSR+{ JavaScript synthax  } 
@JS+{ JavaScript synthax  }

@if( PHP synthax  )
<body> foo </body>
@elseif( PHP synthax  )
<body> boo </body>
@else
<body> bar </body>
@endif


@foreach( PHP synthax )
<body> foobar </body>
@endforeach

@for( PHP synthax )
<body> foobar </body>
@endfor

@while( PHP synthax )
<body> foobar </body>
@endwhile

@switch( PHP synthax )
@case( PHP synthax )
<body> foobar1 </body>
@break
@continue
@default
<body> foobar2 </body>
@endswitch


@goto( 'name label' )
@label( 'name label' )

@isTRUE( PHP synthax )
<body> foobar </body>
@endisTRUE

@isfalse( PHP synthax )
<body> foobar </body>
@endisfalse

@sessionstart // PHP session start

@timetest // test time execut
<body> foobar </body>
@endtimetest

@headpage // <html></head>
  <link rel="icon" type="image/svg+xml" href="atomes/foobar.svg">
@bodypage // </head><body>
  <body> foobar </body>
@endpage  // </body></html>

CB: @html( attributes ){ CAT or HTML Syntax }

@head{ CAT or HTML Syntax }

@title( 'String title' )
@meta( attributes )

@link( attributes ) // exemple @link( rel="stylesheet" type="text/css" href="foo.css" )
@filecss( 'href css file' ) // similar to <link rel="stylesheet" type="text/css" href="foo.css"> exemple : @filecss('foo/bar.css')

@script+( attributes ) 
@script( 'src script file' ) // @script( 'fobar.js' )

@style+{ CSS Syntax } // similar to JS+ and JSR+ combine it all @style+ into one <style></style>
@style{ CSS Syntax }


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
@!DOCTYPE( attributes )
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
@input(attributes )
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
@br
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
@hr( attributes )
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

@:{ value 1 } // Alias of @PHP for small phpcode exemple: @:{ $_SESSION['foo'] = 'bar'; }
@. // concatenation @{test}@.@{test2}

@?< value 1 > // comment CAT 
@!< value 1 > // comment HTML
@*< value 1 > // comment PHP

@>{ value 1 } // for test use @>{$test} equal to echo $test
@{ Name Var PHP } // for $test use @{test} equal to echo $test



```
