@extends('layout.app')
@section('content')

  @include('component.Menubar');
  @include('component.Verify');
  @include('component.TopBrands');
  @include('component.Footer')

  <script>
    (async ()=>{
        $(".preloader").delay(90).fadeOut(100).addClass('loaded');
    })()
  </script>

@endsection