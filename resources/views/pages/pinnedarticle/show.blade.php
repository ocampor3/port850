@extends('layouts.master')

@section('title')
    {{ Session::get('SiteCode') }}'s Pinned Articles | Appzmate 
@endsection

@section('page-title')
   <b>{{ Session::get('SiteCode') }}</b>'s Menu Footer
@endsection

@section('content')

    <div class="show-return">
        <a href="{{ route('site.show', Session::get('SiteCode')) }}" class="btn btn-primary"><span class="fa fa-arrow-left"></span> Return</a>
    </div>

    <div class="form-horizontal">
        <div class="pg-categories-blue">
            PINNED ARTICLES
            <div class="pull-right">
                <a href="{{ route('pinnedarticle.edit',Session::get('SiteId')) }}" class="theme-btn"><span class="glyphicon glyphicon-edit"></span> Edit</a>
            </div>
        </div>

        @if($pinned_articles->isNotEmpty())
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="show-content">
                    @foreach($pinned_articles as $key => $pa)
                        <div class="col-row">   
                            {{ $pa->Title }}
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>

        @else
            <div class="none-available"> NO ARTICLES TO DISPLAY </div>
        @endif
    </div> <!-- form horizontal-->

@endsection