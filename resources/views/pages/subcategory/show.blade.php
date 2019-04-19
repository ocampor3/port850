@extends('layouts.master')

@section('title')
    {{$category->Name}}'s Subcategory | Appzmate 
@endsection

@section('page-title')
    <div class="top-btn">
        @if($category->ParentId == 0)
            <a href="{{ route('category.show', Session::get('SiteId')) }}" class="btn btn-primary"><span class="fa fa-arrow-left"></span> Return</a>
        @else
            <a href="{{ route('subcategory.show', $category->ParentId) }}" class="btn btn-primary"><span class="fa fa-arrow-left"></span> Return</a>
        @endif
    </div>

    <b>{{$category->Name}}</b>'s Subcategories:

@endsection

@section('content')

    @if(Auth::user()->UserGroup != 'Visitor')
        <div class="top-btn">
            <a href="{{ route('subcategory.create') }}" class="btn btn-success" role="button"> <span class="glyphicon glyphicon-plus"></span><b> CREATE NEW SUBCATEGORY </b></a>
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
                <th class="div-center">Icon</th>
                <th class="div-center">Category Name</th>
                <th class="div-center">Status</th>
                <th class="div-center">Allow Upload</th>
                <th class="div-center">Show Menu Footer</th>
                <th class="div-center">Allow Share</th>
                <th class="div-center">Show Top Banner</th>
                <th class="div-center">Sort Order</th>
                <th class="div-center">View Color</th>

                @if(Auth::user()->UserGroup != 'Visitor')
                    <th class="div-center">Action</th>
                @endif
            </tr>  

            @foreach($subcat as $cat) 
                <tr>                       
                    <td>
                        <div class="theme-icon">
                            <img src="{{$cat->Icon}}">
                        </div>  
                    </td>

                    <td>{{$cat->Name}}</td>

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

                    <td class="div-center">
                        {{$cat->SortOrder}}
                    </td>

                    <td class="div-center">
                        @if ($cat->ViewColor == null)
                            NO COLOR
                        @else
                            <div class="theme-displaycolor" style="background-color:{{$cat->ViewColor}};"></div> 
                        @endif
                    </td>

                    @if(Auth::user()->UserGroup != 'Visitor')
                        <td class="div-center">       
                            <!-- add subcategory-->
                            <a href="{{ route('subcategory.show', $cat->Id) }}" class="btn btn-primary" title="View Subcategories" role="button"><span class="fa fa-eye"></span></a>  
                            <!-- edit subcategory -->                         
                            <a href="{{ route('subcategory.edit', $cat->Id) }}" class="btn btn-warning" role="button"><span class="fa fa-edit"></span></a>

                            <!-- delete subcategory -->
                            {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['subcategory.destroy', $cat->Id],
                                'style' => 'display:inline-block;',
                            ]) !!}

                            {!! Form::button('<span class="fa fa-trash">   </span>', 
                                    array(  'id' => 'btnDel', 
                                            'class' => 'btn btn-danger',
                                            'title' => 'Delete subcategory?',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#confirmDelete',
                                            'data-title' => 'Delete Subcategory: '.$cat->Name,
                                            'data-message' => 'Subcategory, and articles associated with it will be permanently deleted. Are you sure you want to delete this Subcategory?',
                                            'data-btncancel' => 'btn-default',
                                            'data-btnaction' => 'btn-danger',
                                            'data-btntxt' => 'Confirm'
                            )) !!}

                            {!! Form::close() !!}
                        </td>
                    @endif

                </tr>  
            @endforeach
            
            </tr>
        </tbody>
    </table>

    @if ($subcat->items() == [])           
        <div class="none-available"> NO SUBCATEGORIES TO DISPLAY </div>            
    @endif

    <div class="dataTables_paginate paging_simple_numbers" style="text-align: center;">   
        {{ $subcat->links('vendor.pagination.bootstrap-4') }} 
    </div>

    <br>

    <hr style="border-top: 1px solid #000;">

    <!-- article list -->
    {{ Session::put('return_page',Request::url()) }}

    <section class="content-header" style="padding-left: 0px;">
        <h1 style="word-wrap: break-word;"> <b> {{ $category->Name }} </b>'s Article List</h1>
    </section>
    <br>
    <!-- 
    @if(Auth::user()->UserGroup != 'Visitor')
        <div class="top-btn">
            <a href="{{ route('article.create') }}" class="btn btn-success" role="button"> <span class="glyphicon glyphicon-plus"></span><b> CREATE NEW ARTICLE </b></a>
        </div>
    @endif -->

    <table class="theme">
        <tbody>
            <tr>
                <th>Article Title</th>
                <th>File Type</th>
                <th>Value</th>
                <th>Modified Date</th>
                <th>Action</th>
            </tr>   

            @foreach($category->Article as $art)
                <tr>
                    <td>{{$art->Title}}</td>
                    <td>{{$art->Type}}</td> 
                    
                    @if($art->Type == 'DIRECTTEXT' || $art->Type == 'DIRECTTEXTFULL')
                        <td>{!! $art->Value !!}</td>
                    
                    @elseif($art->Type == 'LINKPassword')
                        <td>{!! $art->EncryptedLinkValue !!}</td>

                    @elseif($art->Type == 'LINK' || $art->Type == 'LINKEXTERNAL' || $art->Type == 'LINKLogin' || 
                            $art->Type == 'LINKAutofill' || $art->Type == 'LINKCredential')
                        <td><a href="{{$art->Value}}" target="_blank">{{ $art->Value }}</a></td>

                    @elseif($art->Type == 'File' || $art->Type == 'TEXT')
                        <td><a href="{{$art->Value}}" target="_blank">{{ $art->FileName }}</a></td>

                    @else
                        <td></td>
                    @endif

                    <td>
                        @if ($art->ModifiedDate == null)
                            {{$art->CreatedDate}}
                        @else
                            {{$art->ModifiedDate}}
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('showArticle', $art->Id) }}" class="btn btn-primary" role="button"><span class="fa fa-eye"></span></a>    
                    </td>
                </tr>
            @endforeach
            
        </tbody>
    </table>

    @if (count($category->Article) <= 0)           
        <div class="none-available"> NO ARTICLES TO DISPLAY </div>            
    @endif

    @include('modals.delete') 

    <script type="text/javascript">

        //if dropdown value of pagination
        $('#showpage').change(function(){
            var paginate = $(this).val();

            $.cookie("subcategory_pageshow",paginate);
            
            location.reload();
        });

    </script>
    
@endsection


