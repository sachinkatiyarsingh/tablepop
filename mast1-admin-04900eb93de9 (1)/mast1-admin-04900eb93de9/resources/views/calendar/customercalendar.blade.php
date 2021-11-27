@extends('layouts.app',['class' => 'customers', 'activePage' => 'customers'])
@section('content')
<section class="content_block">
    <input type="hidden" name="" id="id" value="{{ $id ?? '' }}">
    <div class="container">

<div id='calendar'></div>
</div>

</section>

@endsection