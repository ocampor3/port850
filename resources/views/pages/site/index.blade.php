@extends('layouts.master')

@section('title')
    Site Management | Appzmate 
@endsection

@section('page-title')
	SITE MANAGEMENT
@endsection

@section('content')
	
	@if(Auth::user()->UserGroup != "User")
		<div class="top-btn">
			<!-- create new site -->
			<a href="{{ route('site.create') }}" class="btn btn-success" role="button"> <span class="glyphicon glyphicon-plus"></span><b> NEW SITE </b></a>
		</div>
	@endif

	<table class="theme">
		<tbody>
			<tr>
				<th>Icon</th>
			 	<th>Site Code</th>
			  	<th>Title</th>
			  	<th>Status</th>
			  	<th>View Type</th>
			  	<th>SubView Type</th>
			  	<th>Parent Site</th>
			  	<th>Subsites</th>
			  	<th>Action</th>
			</tr>

			@foreach($sites as $site)
				<tr>

                    <td class="div-center">
                        <div class="theme-icon">
                            <img src="{{$site->Icon}}">
                        </div>  
                    </td>
			  		<td>{{$site['SiteCode']}}</td>
				  	<td>{{$site->Title}}</td>
				  	<td>{{$site->Status}}</td>
				  	<td>{{$site['theme']->ViewType}}</td>
				  	<td>{{$site['theme']->SubViewType}}</td>
				  	<td>{{$site->parentSite['SiteCode']}}</td>
				  	<td>
				  		@if($site->parentSite == null)
					  		<!-- view subsites -->
				  			<a href="{{ route('subsites.index', $site->Id) }}" class="btn btn-primary" title="View Subsites" role="button">
				  				<span class="fa fa-eye"></span>
				  			</a>
			  			@endif
				  	</td>
				  	<td>			  		
				  		<!-- view site details -->
			  			<a href="{{ route('site.show', $site->SiteCode) }}" class="btn btn-success" title="Show more details" role="button">
			  				<span class="fa fa-cogs"></span>
			  			</a>

			  			<!-- edit site details -->
			  			<a href="{{ route('site.edit', $site->SiteCode) }}" class="btn btn-warning" title="Edit site details" role="button">
			  				<span class="fa fa-edit"></span>
			  			</a>

			  			@if(Auth::user()->UserGroup != "User")
				  			<!-- delete site -->
							{!! Form::open([
	                            'method' => 'DELETE',
	                            'route' => ['site.destroy', $site->Id],
	                            'style' => 'display:inline-block;',
	                        ]) !!}

	                        {!! Form::button('<span class="fa fa-trash">   </span>', 
	                                array(  'id' => 'btnDel', 
	                                        'class' => 'btn btn-danger',
	                                        'title' => 'Delete site?',
	                                        'data-toggle' => 'modal',
	                                        'data-target' => '#confirmDelete',
	                                        'data-title' => 'Delete Site: '.$site->SiteCode,
	                                        'data-message' => 'Site and all data associated with this will be permanently deleted. Are you sure you want to delete this Site?',
	                                        'data-btncancel' => 'btn-default',
	                                        'data-btnaction' => 'btn-danger',
	                                        'data-btntxt' => 'Confirm'
	                        )) !!}

	                        {!! Form::close() !!} 
	                    @endif
	  			  	</td>
				</tr>
			@endforeach	
		</tbody>
	</table>

	@include('modals.delete')

@endsection
