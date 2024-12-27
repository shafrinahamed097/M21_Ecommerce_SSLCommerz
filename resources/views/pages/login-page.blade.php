@extends('layout.app')
@section('content')
 @include('components.Menubar');
 @include('component.TopBrand');
 @include('component.Footer')

 <script>
   (async ()=>{
    $(".preloader").delay(90).fadeOut(100).addClass("loaded");
   })()

 </script>

@endsection