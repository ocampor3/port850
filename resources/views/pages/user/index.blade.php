 @extends('layouts.master')

@section('title')	 
    User Management | Appzmate
@endsection

@section('page-title')
	@if(Auth::user()->UserGroup == 'Admin')
		USER MANAGEMENT
	@else
		<b>{{ Session::get('SiteCode') }}</b>'s User Management
	@endif
@endsection

@section('content')

	<div class="top-btn">
		<a href="{{ route('user.create') }}" class="btn btn-success" role="button"> <span class="glyphicon glyphicon-plus"></span><b> CREATE NEW USER </b></a>
	</div>

	@if(!empty($inactiveusers))

	    <section class="content-header" style="padding-left: 0px;">
	        <h1 style="word-wrap: break-word;">Inactive Users</h1>
	    </section>

		<table class="theme">
			<tbody>
				<tr>
					<th>Username</th>
					<th>Name</th>
					<th>Action</th>
				</tr>

				@foreach($inactiveusers as $iuser)
				<tr>
					<td>{{ $iuser->UserName }}</td>
					<td>{{ $iuser->FullName }}</td>
					<td>
			  			<!-- edit user details -->
			  			<a href="{{ route('user.edit', $iuser->Id) }}" class="btn btn-warning" title="Edit user details" role="button">
			  				<span class="fa fa-edit"></span>
			  			</a>

			  			<!-- delete inactive user -->
						{!! Form::open([
		                    'method' => 'DELETE',
		                    'route' => ['user.destroy', $iuser->Id],
		                    'style' => 'display:inline-block;',
		                ]) !!}

		                {!! Form::button('<span class="fa fa-trash"> </span>', 
		                        array(  'id' => 'btnDel', 
		                                'class' => 'btn btn-danger',
		                                'title' => 'Delete Inactive User?',
		                                'data-toggle' => 'modal',
		                                'data-target' => '#confirmDelete',
		                                'data-title' => 'Delete Inactive User: '.$iuser->UserName,
		                                'data-message' => 'User will be permanently deleted. Are you sure you want to delete this Inactive User?',
		                                'data-btncancel' => 'btn-default',
		                                'data-btnaction' => 'btn-danger',
		                                'data-btntxt' => 'Confirm'
		                )) !!}

		                {!! Form::close() !!}
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	    <hr style="border-top: 1px solid #000;">
	@endif

    <section class="content-header" style="padding-left: 0px;">
        <h1 style="word-wrap: break-word;">Active Users</h1>
    </section>
    <br>

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
				<th>Username</th>
				<th>Name</th>
				<th>User Group</th>
				<th>Site Handled</th>
				<th>Action</th>
			</tr>	

			@foreach($users as $user)
				<tr>
					<td>{{ $user->UserName }}</td>
					<td>{{ $user->FullName }}</td>
					<td>{{ $user->UserGroup }}</td>
					<!-- <td>{{ $user['sc']['Title'] }}</td>		 -->
					<td>
						@foreach($user['usersite'] as $key => $s)
							{{ $s['sites'][0]->Title }}
							<br>
						@endforeach
					</td>		
					<td>

						@if(Auth::user()->UserGroup == "Admin" || ($user->UserGroup!="Admin"))
			  			<!-- edit user details -->
			  			<a href="{{ route('user.edit', $user->Id) }}" class="btn btn-warning" title="Edit user details" role="button">
			  				<span class="fa fa-edit"></span>
			  			</a>

			  			<!-- delete user -->
						{!! Form::open([
	                        'method' => 'DELETE',
	                        'route' => ['user.destroy', $user->Id],
	                        'style' => 'display:inline-block;',
	                    ]) !!}

	                    {!! Form::button('<span class="fa fa-trash"> </span>', 
	                            array(  'id' => 'btnDel', 
	                                    'class' => 'btn btn-danger',
	                                    'title' => 'Delete User?',
	                                    'data-toggle' => 'modal',
	                                    'data-target' => '#confirmDelete',
	                                    'data-title' => 'Delete User: '.$user->UserName,
	                                    'data-message' => 'User will be permanently deleted. Are you sure you want to delete this User?',
	                                    'data-btncancel' => 'btn-default',
	                                    'data-btnaction' => 'btn-danger',
	                                    'data-btntxt' => 'Confirm'
	                    )) !!}
	                    @endif

	                    {!! Form::close() !!}
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<div class="dataTables_paginate paging_simple_numbers" style="text-align: center;">   
		{{ $users->links('vendor.pagination.bootstrap-4') }} 
    </div>

	@include('modals.delete') 

	<script type="text/javascript">

        //if dropdown value of pagination
        $('#showpage').change(function(){
            var paginate = $(this).val();

            $.cookie("user_pageshow",paginate);
            
            location.reload();
        });

    </script>

@endsection
