@extends('layouts.master')

@section('title')
    {{$site->Title}}'s Settings | Appzmate 
@endsection

@section('page-title')
   <b>{{$site->Title}}</b>'s Settings
@endsection

@section('content')
    
    @if(Auth::user()->UserGroup == 'Owner' || Auth::user()->UserGroup == 'Admin')
        <div class="col-md-6">
            <!-- small box -->
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3>Theme</h3>
                </div>
                
                <a href="{{ route('theme.show',Session::get('SiteId')) }}" class="small-box-footer">
                    Show More Details <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    @endif

    <div class="col-md-6">
        <!-- small box -->
        <div class="small-box bg-blue">
            <div class="inner">
                <h3>Categories</h3>
            </div>
            
            <a href="{{ route('category.show',Session::get('SiteId')) }}" class="small-box-footer">
                Show More Details <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-md-6">
        <!-- small box -->
        <div class="small-box bg-blue">
            <div class="inner">
                <h3>Articles</h3>
            </div>
            
            <a href="{{ route('article.show',Session::get('SiteId')) }}" class="small-box-footer">
                Show More Details <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    @if(Auth::user()->UserGroup == 'Owner' || Auth::user()->UserGroup == 'Admin')
        <div class="col-md-6">
            <!-- small box -->
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3>Menu Footer</h3>
                </div>
                
                <a href="{{ route('pinnedarticle.show',Session::get('SiteId')) }}" class="small-box-footer">
                    Show More Details <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-6">
            <!-- small box -->
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3>Security Group</h3>
                </div>

                <a href="{{ route('securitygroup.show',Session::get('SiteId')) }}" class="small-box-footer">
                    Show More Details <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    @endif
    
@endsection