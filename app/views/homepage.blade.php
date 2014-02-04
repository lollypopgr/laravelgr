@extends('template')

@section('main')
{{-- Main Frame --}}
<div class="theme">
    {{PictureFill::make($randomImage, "Greek Laravel Fans")}}

<div class="community"><strong>{{$devscount}}</strong> προγραμματιστές ανακαλύφθηκαν. Ας γίνουμε περισσότεροι!</div>
<div class="devthumbs">{{PictureFill::make("images/devs/devs.jpg","Greek Laravel Fans")}}</div>
@stop