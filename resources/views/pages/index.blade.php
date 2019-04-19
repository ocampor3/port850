@extends('layouts.master')

@section('page-title')
<h1>
   <b>Appzmate</b>'s Settings
</h1>
@endsection

@section('content')

<div class="col-md-6">
  <!-- small box -->
  <div class="small-box bg-blue">
    <div class="inner">
      <h3>Theme</h3>
    </div>
    <a href="#" class="small-box-footer">
      Show More Details <i class="fa fa-arrow-circle-right"></i>
    </a>
  </div>
</div>

<div class="col-md-6">
  <!-- small box -->
  <div class="small-box bg-blue">
    <div class="inner">
      <h3>Categories</h3>
    </div>
    <a href="{{route('category.index')}}" class="small-box-footer">
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
    <a href="{{route('article.index')}}" class="small-box-footer">
      Show More Details <i class="fa fa-arrow-circle-right"></i>
    </a>
  </div>
</div>

<div class="col-md-6">
  <!-- small box -->
  <div class="small-box bg-blue">
    <div class="inner">
      <h3>File List</h3>
    </div>
    <a href="#" class="small-box-footer">
      Show More Details <i class="fa fa-arrow-circle-right"></i>
    </a>
  </div>
</div>

@endsection