@extends('layouts.master')

@section('title')
    {{Session::get('SiteCode')}}'s Category | Appzmate 
@endsection

@section('page-title')
    <b>{{Session::get('SiteCode')}}</b>'s CATEGORY MANAGEMENT
@endsection

@section('content')

    @if(Auth::user()->UserGroup != 'Visitor')
        <div class="top-btn">
            <a href="{{ route('category.create') }}" class="btn btn-success" role="button"> <span class="glyphicon glyphicon-plus"></span><b> CREATE NEW CATEGORY </b></a>
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

            <!-- column titles -->
            <tr>
                <th class="div-center">Icon</th>
                <th class="div-center">Category Name</th>
                <th class="div-center">Status</th>
                <th class="div-center">Allow Upload</th>
                <th class="div-center">Show Menu Footer</th>
                <th class="div-center">Allow Share</th>
                <th class="div-center">Show Top Banner</th>
                <th class="div-center">Sort Order</th>
                <th class="div-center">View Color</th>
                <th class="div-center">Action</th>
            </tr>   

            @foreach($category as $cat)
                <tr>
                    <td class="div-center">
                        <div class="theme-icon">
                            <img src="{{$cat->Icon}}">
                        </div>  
                    </td>

                    <td>{{ $cat->Name}}</td>

                    <td class="div-center">{{ $cat->Status}}</td>

                    <td class="div-center">
                        @if ($cat->AllowUpload == 1)
                             <span class="fa fa-check"></span>
                        @else
                            <span class="fa fa-close"></span>
                        @endif
                    </td>

                    <td class="div-center">
                        @if ($cat->MenuFooter == 1)
                             <span class="fa fa-check"></span>
                        @else
                            <span class="fa fa-close"></span>
                        @endif
                    </td>

                    <td class="div-center">
                        @if ($cat->AllowShare == 1)
                             <span class="fa fa-check"></span>
                        @else
                            <span class="fa fa-close"></span>
                        @endif
                    </td>
                    
                    <td class="div-center">
                        @if ($cat->TopBannerShow == 1)
                             <span class="fa fa-check"></span>
                        @else
                            <span class="fa fa-close"></span>
                        @endif
                    </td>

                    <td class="div-center">{{$cat->SortOrder}}</td>

                    <td class="div-center">
                        @if ($cat->ViewColor == null)
                            NO COLOR
                        @else
                            <div class="theme-displaycolor" style="background-color:{{$cat->ViewColor}};"></div> 
                        @endif
                    </td>

                    <td class="div-center">
                        <a href="{{ route('subcategory.show', $cat->Id) }}" class="btn btn-primary" title="View Subcategories" role="button"><span class="fa fa-eye"></span></a>  
                        
                        @if(Auth::user()->UserGroup != 'Visitor')
                            <a href="{{ route('category.edit', $cat->Id) }}" class="btn btn-warning" title="Edit Category Details" role="button"><span class="fa fa-edit"></span></a>

                            {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['category.destroy', $cat->Id],
                                'style' => 'display:inline-block;',
                            ]) !!}

                            {!! Form::button('<span class="fa fa-trash">   </span>', 
                                    array(  'id' => 'btnDel', 
                                            'class' => 'btn btn-danger',
                                            'title' => 'Delete Category?',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#confirmDelete',
                                            'data-title' => 'Delete Category: '.$cat->Name,
                                            'data-message' => 'Category, its subcategories, and articles associated with it will be permanently deleted. Are you sure you want to delete this Category?',
                                            'data-btncancel' => 'btn-default',
                                            'data-btnaction' => 'btn-danger',
                                            'data-btntxt' => 'Confirm'
                            )) !!}

                            {!! Form::close() !!}
                        @endif
                    </td>
                </tr>
            @endforeach <!-- foreach category -->
        </tbody>
    </table> <!-- table theme -->

    @if ($category->items() == [])           
        <div class="none-available"> NO CATEGORIES TO DISPLAY </div>            
    @endif

    <div class="dataTables_paginate paging_simple_numbers" style="text-align: center;">   
        {{ $category->links('vendor.pagination.bootstrap-4') }} 
    </div>

    @include('modals.delete')

    <script type="text/javascript">

        //if dropdown value of pagination
        $('#showpage').change(function(){
            var paginate = $(this).val();

            $.cookie("category_pageshow",paginate);
            
            location.reload();
        });

    </script>

@endsection


