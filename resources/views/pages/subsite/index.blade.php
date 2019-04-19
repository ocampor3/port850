@extends('layouts.master')

@section('title')
    {{$site->Title}}'s Subsite | Appzmate 
@endsection

@section('page-title')
    <div class="top-btn">
        <a href="{{ route('site.index') }}" class="btn btn-primary"><span class="fa fa-arrow-left"></span> Return</a>
    </div>

    <b>{{$site->Title}}</b>'s Subsites:

@endsection

@section('content')
	
	@if(Auth::user()->UserGroup != "User")
		<div class="top-btn">
			<!-- create new site -->
			<a href="{{ route('subsite.create') }}" class="btn btn-success" role="button"> <span class="glyphicon glyphicon-plus"></span><b> CREATE NEW SUBSITE </b></a>
		</div>
	@endif
        
    <div class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
            <div class="col-sm-6">
                <div class="dataTables_length">
                    <label style="font-weight:400 !important;">
                        Show
                        
                        {!! Form::select('pageshow', ['25' => '25','50' => '50','100' => '100','250' => '250','500' => '500'], 
                                         $paginate, ['id' =>'showpage' ,'class' => 'form-control input-sm', '
                                         style' => 'width: 60px;' ,'placeholder' => '--Select Type--']) !!} 
                        
                        entries
                    </label>
                </div>
            </div>
        </div>
    </div>

    <table class="theme">
        <tbody>
			<tr>
			 	<th>Site Code</th>
			  	<th>Title</th>
			  	<th>View Type</th>
			  	<th>SubView Type</th>
			  	<th>Action</th>
			</tr>

            @foreach($subsites as $subsite) 
                <tr>

			  		<td>{{$subsite['SiteCode']}}</td>
				  	<td>{{$subsite->Title}}</td>
				  	<td>{{$subsite->ViewType}}</td>
				  	<td>{{$subsite->SubviewType}}</td>
				  	<td>			  		
				  		<!-- view subsite details -->
			  			<a href="{{ route('site.show', $subsite->SiteCode) }}" class="btn btn-success" title="Show more details" role="button">
			  				<span class="fa fa-cogs"></span>
			  			</a>

			  			<!-- edit subsite details -->
			  			<a href="{{ route('site.edit', $subsite->SiteCode) }}" class="btn btn-warning" title="Edit subsite details" role="button">
			  				<span class="fa fa-edit"></span>
			  			</a>

			  			@if(Auth::user()->UserGroup != "User")
				  			<!-- delete subsite -->
							{!! Form::open([
	                            'method' => 'DELETE',
	                            'route' => ['site.destroy', $subsite->Id],
	                            'style' => 'display:inline-block;',
	                        ]) !!}

	                        {!! Form::button('<span class="fa fa-trash">   </span>', 
	                                array(  'id' => 'btnDel', 
	                                        'class' => 'btn btn-danger',
	                                        'title' => 'Delete subsite?',
	                                        'data-toggle' => 'modal',
	                                        'data-target' => '#confirmDelete',
	                                        'data-title' => 'Delete Site: '.$subsite->SiteCode,
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

    @if ($subsites->items() == [])           
        <div class="none-available"> NO SUBSITES TO DISPLAY </div>            
    @endif

    @include('modals.delete') 

    <script type="text/javascript">

        //if dropdown value of pagination
        $('#showpage').change(function(){
            var paginate = $(this).val();

            $.cookie("subsite_pageshow",paginate);
            
            location.reload();
        });

    </script>
    
@endsection


