# <img src='logo.png' /><br/> PHP 7.4

@include( value 1 )
__________________________
TYPE : PHP
EXEMPLE : @include( CAT . 'foo.php')
__________________________

@structure( value 1 ){ value 2 }
__________________________
TYPE : DATA LOADING
EXEMPLE : 
  template.tpl :
  <HTML>
   <HEAD>
     <TITLE> {{TITLEPAGE}}</TITLE> 
   </HEAD>
   <BODY>
   {{BODYDATA}}
  <BODY></HTML>

  file.cat :
  @structure('template.tpl'){
   |TITLEPAGE~'MYTITLE'
   |BODYDATA~'MYDATA'
  }
__________________________

@getfile( value 1 )
__________________________
TYPE :  DATA LOADING
EXEMPLE : @getfile( 'myfile.seg' )
__________________________

@getsegment( value 1 ){ value 2 }
__________________________
TYPE :  DATA LOADING
EXEMPLE : 
			@if( $uservar[$id] )
				@getsegment('userpage/loginsegment.seg'){loaduserpage}
			@else
				@getsegment('userpage/loginsegment.seg'){loadlogin}
			@endif
__________________________

@phpsegment( value 1 ){ value 2 }
__________________________
TYPE : DATA LOADING
EXEMPLE : similar to @getsegment but allows to load php code
			@if( $uservar[$id] )
				@phpsegment('userpage/loginsegment.seg'){loaduserpage}
			@else
				@phpsegment('userpage/loginsegment.seg'){loadlogin}
			@endif
__________________________

@setsegment( value 1 )
@endsegment
__________________________
TYPE : segmentation zone
EXEMPLE : allows you to define a segmentation zone
@setsegment(loaduserpage)
	@getfile('modules/_USERS_/userpage.cat')
@endsegment
__________________________

@dowhile
@whiledo( value 1 )
__________________________
TYPE : dowhile PHP/HTML
EXEMPLE : 
@dowhile
__________________________

@dow( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@PHP{ value 1 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@JSR{ value 1 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@JS{ value 1 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@JSR+{ value 1 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@JS+{ value 1 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@if( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@elseif( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@else
__________________________
TYPE : 
EXEMPLE : 
__________________________

@endif
__________________________
TYPE : 
EXEMPLE : 
__________________________

@foreach( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@endforeach
__________________________
TYPE : 
EXEMPLE : 
__________________________

@for( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@endfor
__________________________
TYPE : 
EXEMPLE : 
__________________________

@while( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@endwhile
__________________________
TYPE : 
EXEMPLE : 
__________________________

@switch( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@case( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@break
__________________________
TYPE : 
EXEMPLE : 
__________________________

@continue
__________________________
TYPE : 
EXEMPLE : 
__________________________

@default
__________________________
TYPE : 
EXEMPLE : 
__________________________

@endswitch
__________________________
TYPE : 
EXEMPLE : 
__________________________

@goto( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@label( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@isTRUE( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@endisTRUE
__________________________
TYPE : 
EXEMPLE : 
__________________________

@isfalse( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@endisfalse
__________________________
TYPE : 
EXEMPLE : 
__________________________

@sessionstart
__________________________
TYPE : 
EXEMPLE : 
__________________________

@timetest
__________________________
TYPE : 
EXEMPLE : 
__________________________

@endtimetest
__________________________
TYPE : 
EXEMPLE : 
__________________________

@headpage
__________________________
TYPE : 
EXEMPLE : 
__________________________

@bodypage
__________________________
TYPE : 
EXEMPLE : 
__________________________

@endpage
__________________________
TYPE : 
EXEMPLE : 
__________________________

@html( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@head{ value 1 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@title( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@meta( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@link( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@filecss( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@script+( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@script( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@style+{ value 1 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@style{ value 1 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@body( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@blockquote( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@figcaption( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@colgroup( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@datalist( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@fieldset( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@noscript( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@optgroup( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@progress( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@textarea( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@!DOCTYPE( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@address( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@article( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@caption( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@command( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@details( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@section( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@summary( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@button( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@canvas( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@figure( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@footer( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@header( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@hgroup( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@iframe( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@keygen( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@legend( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@object( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@option( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@output( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@select( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@source( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@strong( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@center( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@aside( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@audio( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@embed( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@input( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@label( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@meter( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@param( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@small( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@table( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@tbody( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@tfoot( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@thead( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@title( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@track( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@video( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@abbr( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@area( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@base( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@cite( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@code( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@form( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@mark( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@math( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@menu( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@ruby( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@samp( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@span( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@time( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@bdo( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@col( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@del( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@dfn( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@div( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@img( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@ins( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@kbd( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@map( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@nav( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@pre( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@sub( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@sup( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@svg( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@var( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@wbr( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@br
__________________________
TYPE : 
EXEMPLE : 
__________________________

@dd( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@dl( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@dt( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@em( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@h1( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@h2( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@h3( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@h4( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@h5( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@h6( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@hr( value 1 )
__________________________
TYPE : 
EXEMPLE : 
__________________________

@li( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@ol( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@rp( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@rt( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@td( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@th( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@tr( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@ul( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@a( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@b( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@i( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@p( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@q( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@:{ value 1 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@.( value 1 ){ value 2 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@?< value 1 >
__________________________
TYPE : 
EXEMPLE : 
__________________________

@!< value 1 >
__________________________
TYPE : 
EXEMPLE : 
__________________________

@*< value 1 >
__________________________
TYPE : 
EXEMPLE : 
__________________________

@>{ value 1 }
__________________________
TYPE : 
EXEMPLE : 
__________________________

@{ value 1 }
__________________________
TYPE : 
EXEMPLE : 
__________________________



