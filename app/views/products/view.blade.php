@extends('layouts.default')

@section('content')
<div id="outerafterheader">
    <div class="container">
        <div class="row">
            <div id="afterheader" class="twelve columns">
                <h1 class="pagetitle nodesc">{{ $product->nombre }}</h1>
            </div>
        </div>
    </div>
</div>
    <!-- MAIN CONTENT -->
<div id="outermain">
    <div id="maincontainer">
        <div class="container">
            <div class="row">
                <section id="maincontent">
                     <section class="eight columns positionleft" id="content">
                        <article>
                            <div class="articlecontainer">
								<div class="flexslider black-background img-slider">
								  <ul class="slides">
								    @foreach($product->photos as $photo)
								    <li data-thumb="{{ asset($photo->path . $photo->filename) }}">
								      <img id="img-{{ $photo->id }}" src="{{ asset($photo->path . $photo->filename) }}" data-zoom-image="{{ asset($photo->path . $photo->filename) }}" />
								    </li>
								    @endforeach
								  </ul>
								</div>

                                <div class="entry-content">
                                    <br/>
                                    <h2>Detalles</h2>
                                    <ul class="listborder">
                                        <li><strong>Código: </strong><em>{{ $product->codigo }}</em></li>
                                        <li><strong>Medidas: </strong><em>{{ $product->medidas }}</em></li>
                                        @if($currentUser)
	                                        <li><strong>Precio del Lacado: </strong><em>{{ $product->precio_lacado }}</em></li>
	                                        <li><strong>Precio en Puntos del Lacado: </strong><em>{{ $product->precio_lacado_puntos }}</em></li>
	                                        <li><strong>Precio del Pulimento: </strong><em>{{ $product->precio_pulimento }}</em></li>
	                                        <li><strong>Precio en Puntos del Pulimento: </strong><em>{{ $product->precio_pulimento_puntos }}</em></li>
	                                    @endif
                                    </ul>
                                    <div class="clear"></div>
                                    <ul class="line">
                                        <li>
                                        </li>
                                        @if($currentUser)
                                            @if($currentUser->isClient())
                                                <li><a href="{{ route('pedidos.create', $product->id) }}" class="button">Realizar pedido</a></li>
                                            @endif

                                            @if($currentUser->isAdmin())
                                                <li><a href="{{ route('photos.create', $product->id) }}" class="button">Agregar fotos</a></li>
                                                <li><a href="{{ route('products.edit', $product->id) }}" class="button">Editar</a></li>
                                                <li><a href="{{ route('products.destroy', $product->id) }}" class="button">Eliminar</a></li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>

                                <div class="clear"></div>
                            </div>
                        </article><!-- end post -->

                    </section><!-- content -->

                    <aside class="four columns positionright" id="sidebar">
						<div class="widget-area">
                        <ul>
                            <li class="widget-container widget_hover">
                                <h2 class="widget-title">Información del Producto</h2>
                                <div class="textwidget">{{ $product->descripcion }}</div>
                            </li>
                            @include('layouts.partials._random-products')
                            {{--@include('layouts.partials._tags')--}}
                        </ul>
                        </div>
                    </aside><!-- sidebar -->

                </section>
            </div>
        </div>
    </div>
</div>
@stop

@section('in-situ-css')
<style>
	.img-slider .slides img {
	    width: 420px;
	    height: 580px;
	    margin: 0 auto;
	}

	.black-background {
		background-color: #e7e7e7;
	}

	ol img {
		width: 150px;
		height: 170px;
		margin-right: 5px;
		margin-left: 5px;
	}
</style>
@stop

@section('in-situ-js')
	<script src="{{ asset('js/vendor/jquery.flexslider-min.js') }}"></script>
	<script src="{{ asset('js/vendor/jquery.elevatezoom.min.js') }}"></script>
@stop

@section('script')
<script>
	jQuery(window).load(function() {
		jQuery('.flexslider').flexslider({
            animation: "slide",
            controlNav: "thumbnails",
            directionNav: true,
			prevText: "Anterior",           //String: Set the text for the "previous" directionNav item
			nextText: "Siguiente",
            keyboard: true
          });

       @foreach($product->photos as $photo)
            jQuery('ul.slides').mouseover(function() {
		        jQuery('.flex-active-slide img').elevateZoom(
		        {
		            zoomType    : "lens",
		            lensShape   : "round",
		            lensSize    : 280
		        });
            });
       @endforeach
	});
</script>
@stop