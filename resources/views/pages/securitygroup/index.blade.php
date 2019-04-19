@extends('layouts.master')

@section('title')	 
Security Group Management | Appzmate
@endsection

@section('page-title')

SECURITY GROUP MANAGEMENT
@endsection

@section('content')

<div class="top-btn">
	<a href="{{ route('securitygroup.createSGSite', $SiteId) }}" class="btn btn-success" role="button"> <span class="glyphicon glyphicon-plus"></span><b> CREATE NEW SECURITY GROUP </b></a>
</div>

@if(!empty($securitygroups))

<section class="content-header" style="padding-left: 0px;">
	<h1 style="word-wrap: break-word;">Security Groups</h1>
</section>

<table class="theme">
	<tbody>
		<tr>
			<th>Display Name</th>
			<th>Action</th>
		</tr>

		@foreach($securitygroups as $sgroup)
		<tr>
			<td>{{ $sgroup->DisplayName }}</td>
			<td>
				<!-- edit security group details -->
				<a href="{{ route('securitygroup.edit', $sgroup->Id) }}" class="btn btn-warning" title="Edit security group details" role="button">
					<span class="fa fa-edit"></span>
				</a>

				{!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['securitygroup.delete', $SiteId,$sgroup->Id],
                                'style' => 'display:inline-block;',
                            ]) !!}

                            {!! Form::button('<span class="fa fa-trash">   </span>', 
                                    array(  'id' => 'btnDel', 
                                            'class' => 'btn btn-danger',
                                            'title' => 'Delete Security Group?',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#confirmDelete',
                                            'data-title' => 'Delete Security Group',
                                            'data-message' => 'Are you sure you want to delete '.$sgroup->DisplayName,
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

@endif
 @include('modals.delete')
@endsection

