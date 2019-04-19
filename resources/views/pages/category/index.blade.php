@extends('layouts.master')

@section('page-title')

	CATEGORY MANAGEMENT

@endsection

@section('content')

	<div class="top-btn">
		<a href="{{ route('category.create') }}" class="btn btn-success" role="button"> <span class="glyphicon glyphicon-plus"></span><b> CREATE NEW CATEGORY </b></a>
	</div>

	<table class="theme">
		<tbody>
			<tr>
				<th>Icon</th>
				<th>Category Name</th>
				<th class="div-center">Allow Upload</th>
				<th class="div-center">Sort Order</th>
				<th class="div-center">View Color</th>
				<th class="div-center">Action</th>
			</tr>	
		@foreach($category as $cat)
			<tr>
				<td>
					<div class="theme-icon">
						<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Roche_Logo.svg/2000px-Roche_Logo.svg.png">
	            	</div>	
	            </td>
				<td>{{$cat->Name}}</td>
				<td class="div-center">
					@if ($cat->AllowUpload == 1)
	                     <span class="fa fa-check"></span>
	                @else
	                    <span class="fa fa-close"></span>
	                @endif
	            </td>
	            <td class="div-center">
	            	{{$cat->SortOrder}}
	            </td>
	            <td>
	            	<div class="theme-displaycolor" style="background-color:{{$cat->ViewColor}};">
	            	</div>	
	            </td>
				<td class="div-center">
					<a href="{{ route('category.show', $cat->Id) }}" class="btn btn-primary" role="button"><span class="fa fa-eye"></span></a>	
					<a href="{{ route('category.edit', $cat->Id) }}" class="btn btn-warning" role="button"><span class="fa fa-edit"></span></a>

	                <a href="#" class="btn btn-danger" role="button" ata-toggle="modal" data-target="#delModal"><span class="fa fa-trash"></span></a>
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>

@endsection
