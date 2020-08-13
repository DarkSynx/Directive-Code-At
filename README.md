# <img src='logo.png' /><br/> PHP 7.4

###### LISTING DIRECTIVE

```PHP
@include( 'link file.php' ) 

@structure( 'link file.seg' ){ 
  |TITLE~'MYTITLE'
  |DATA~@getfile( 'file.seg' )
} 
<BODY>{{TITLE}}{{DATA}}</BODY>

@getfile( 'link file.seg' )
@getsegment( 'link file.seg' ){ name segment }
@phpsegment( 'link file.seg' ){ name segment }
@setsegment( name segment )
@endsegment

@dowhile // only html
<body> foobar </body>
@whiledo( operation )

//only php
@dow( operation ){ PHP synthax }


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

@html( attributes ){ CAT or HTML Syntax }

@head{ CAT or HTML Syntax }

@title( 'String title' )
@meta( attributes )

@link( attributes ) // exemple @link( rel="stylesheet" type="text/css" href="foo.css" )
@filecss( 'href css file' ) // similar to <link rel="stylesheet" type="text/css" href="foo.css"> exemple : @filecss('foo/bar.css')

@script+( attributes )
@script( attributes )

@style+{ CSS Syntax } // similar to JS+ and JSR+ combine it all @style+ into one <style></style>
@style{ CSS Syntax }


@body(  attributes ){ CAT or HTML Syntax }
@blockquote( attributes ){ CAT or HTML Syntax }
@figcaption(  attributes ){ CAT or HTML Syntax }
@colgroup(  attributes ){ CAT or HTML Syntax }
@datalist( attributes ){ CAT or HTML Syntax }
@fieldset(  attributes ){ CAT or HTML Syntax }
@noscript(  attributes ){ CAT or HTML Syntax }
@optgroup( attributes ){ CAT or HTML Syntax }
@progress(  attributes ){ CAT or HTML Syntax }
@textarea(  attributes ){ CAT or HTML Syntax }
@!DOCTYPE( attributes )
@address(  attributes ){ CAT or HTML Syntax }
@article(  attributes ){ CAT or HTML Syntax }
@caption(  attributes ){ CAT or HTML Syntax }
@command( attributes ){ CAT or HTML Syntax }
@details(  attributes ){ CAT or HTML Syntax }
@section(  attributes ){ CAT or HTML Syntax }
@summary(  attributes ){ CAT or HTML Syntax }
@button(  attributes ){ CAT or HTML Syntax }
@canvas(  attributes ){ CAT or HTML Syntax }
@figure(  attributes ){ CAT or HTML Syntax }
@footer(  attributes ){ CAT or HTML Syntax }
@header(  attributes ){ CAT or HTML Syntax }
@hgroup(  attributes ){ CAT or HTML Syntax }
@iframe(  attributes ){ CAT or HTML Syntax }
@keygen(  attributes ){ CAT or HTML Syntax }
@legend(  attributes ){ CAT or HTML Syntax }
@object(  attributes ){ CAT or HTML Syntax }
@option(  attributes ){ CAT or HTML Syntax }
@output(  attributes ){ CAT or HTML Syntax }
@select(  attributes ){ CAT or HTML Syntax }
@source(  attributes ){ CAT or HTML Syntax }
@strong(  attributes ){ CAT or HTML Syntax }
@center(  attributes ){ CAT or HTML Syntax }
@aside(  attributes ){ CAT or HTML Syntax }
@audio(  attributes ){ CAT or HTML Syntax }
@embed(  attributes ){ CAT or HTML Syntax }
@input(attributes )
@label(  attributes ){ CAT or HTML Syntax }
@meter(  attributes ){ CAT or HTML Syntax }
@param(  attributes ){ CAT or HTML Syntax }
@small(  attributes ){ CAT or HTML Syntax }
@table(  attributes ){ CAT or HTML Syntax }
@tbody(  attributes ){ CAT or HTML Syntax }
@tfoot(  attributes ){ CAT or HTML Syntax }
@thead(  attributes ){ CAT or HTML Syntax }
@title(  attributes ){ CAT or HTML Syntax }
@track(  attributes ){ CAT or HTML Syntax }
@video(  attributes ){ CAT or HTML Syntax }
@abbr(  attributes ){ CAT or HTML Syntax }
@area(  attributes ){ CAT or HTML Syntax }
@base(  attributes ){ CAT or HTML Syntax }
@cite(  attributes ){ CAT or HTML Syntax }
@code(  attributes ){ CAT or HTML Syntax }
@form(  attributes ){ CAT or HTML Syntax }
@mark(  attributes ){ CAT or HTML Syntax }
@math(  attributes ){ CAT or HTML Syntax }
@menu(  attributes ){ CAT or HTML Syntax }
@ruby(  attributes ){ CAT or HTML Syntax }
@samp(  attributes ){ CAT or HTML Syntax }
@span(  attributes ){ CAT or HTML Syntax }
@time(  attributes ){ CAT or HTML Syntax }
@bdo(  attributes ){ CAT or HTML Syntax }
@col(  attributes ){ CAT or HTML Syntax }
@del(  attributes ){ CAT or HTML Syntax }
@dfn(  attributes ){ CAT or HTML Syntax }
@div(  attributes ){ CAT or HTML Syntax }
@img(  attributes ){ CAT or HTML Syntax }
@ins(  attributes ){ CAT or HTML Syntax }
@kbd(  attributes ){ CAT or HTML Syntax }
@map(  attributes ){ CAT or HTML Syntax }
@nav(  attributes ){ CAT or HTML Syntax }
@pre(  attributes ){ CAT or HTML Syntax }
@sub(  attributes ){ CAT or HTML Syntax }
@sup(  attributes ){ CAT or HTML Syntax }
@svg(  attributes ){ CAT or HTML Syntax }
@var(  attributes ){ CAT or HTML Syntax }
@wbr(  attributes ){ CAT or HTML Syntax }
@br
@dd(  attributes ){ CAT or HTML Syntax }
@dl(  attributes ){ CAT or HTML Syntax }
@dt(  attributes ){ CAT or HTML Syntax }
@em(  attributes ){ CAT or HTML Syntax }
@h1(  attributes ){ CAT or HTML Syntax }
@h2(  attributes ){ CAT or HTML Syntax }
@h3(  attributes ){ CAT or HTML Syntax }
@h4(  attributes ){ CAT or HTML Syntax }
@h5(  attributes ){ CAT or HTML Syntax }
@h6(  attributes ){ CAT or HTML Syntax }
@hr( attributes )
@li(  attributes ){ CAT or HTML Syntax }
@ol(  attributes ){ CAT or HTML Syntax }
@rp(  attributes ){ CAT or HTML Syntax }
@rt(  attributes ){ CAT or HTML Syntax }
@td(  attributes ){ CAT or HTML Syntax }
@th(  attributes ){ CAT or HTML Syntax }
@tr(  attributes ){ CAT or HTML Syntax }
@ul(  attributes ){ CAT or HTML Syntax }
@a(  attributes ){ CAT or HTML Syntax }
@b(  attributes ){ CAT or HTML Syntax }
@i(  attributes ){ CAT or HTML Syntax }
@p(  attributes ){ CAT or HTML Syntax }
@q(  attributes ){ CAT or HTML Syntax }

@:{ value 1 } // Alias of @PHP for small phpcode exemple: @:{ $_SESSION['foo'] = 'bar'; }
@. // concatenation @{test}@.@{test2}

@?< value 1 > // comment CAT 
@!< value 1 > // comment HTML
@*< value 1 > // comment PHP

@>{ value 1 } // for test use @>{$test} equal to echo $test
@{ Name Var PHP } // for $test use @{test} equal to echo $test



```
