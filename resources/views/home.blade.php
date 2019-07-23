@extends('layouts.app')

@section('content')

    You are logged in!

    <ul>
    	<li><a href="{{ route('distretti.index') }}">distretti</a></li>
    	<li><a href="{{ route('utg.index') }}">utg</a></li>
    	<li><a href="{{ route('squadre.index') }}">squadre</a></li>
    	<li><a href="{{ route('cacciatori.index') }}">cacciatori</a></li>
    	<li><a href="{{ route('zone.index') }}">zone</a></li>
    	<li><a href="{{ route('province.index') }}">province</a></li>
    	<li><a href="{{ route('comuni.index') }}">comuni</a></li>
    </ul>
          
@endsection
