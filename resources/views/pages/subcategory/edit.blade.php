@extends('layouts.master')

@section('title')
    Edit Subcategory | Appzmate 
@endsection

@section('page-title')

   Editing Subcategory: <b>{{$category->Name}}</b>

@endsection

@section('content')

    {!! Form::model($category, [
        'method' => 'PATCH',
        'route' => ['subcategory.update', $category->Id],
        'files' => 'true',
        'onsubmit' => 'return validateFields();'
    ]) !!}

    <div class="panel panel-primary">       
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group">

                    <div id="imgActive1" class="col-sm-3 div-center">
                        <div class="icon-edit">
                            <a class="btn btn-success" onClick="hideImgActive(1);">Change Icon</a>
                        </div>

                        <div class="crud-icon">
                            <div class="icon-img">
                                <img src="{{$category->Icon}}" alt="Image"> 
                            </div>
                        </div>                          
                    </div>     
                
                    <div id="imgReplace1" class="col-sm-3 div-center" style="display:none;">
                        <div class="icon-edit">
                            <input type="file" class="imgInput" id="fileUpload" type="file" name="images">
                        </div>

                        <div class="crud-icon">
                            <div class="icon-img">
                                <img id="newIcon" class="inputPreview" src="#" alt="your image">
                            </div>
                        </div>                            
                    </div>

                    <div class="col-sm-9">
                        <div class="form-group {{ $errors->has('display_name') ? ' has-error' : '' }}">
                            <label class="col-sm-2 control-label">Name: </label>
                            <div class="col-sm-10">
                                <input class="form-control" name="display_name" type="text" maxlength="160" value="{{$category->Name}}" required />
                                
                                @if ($errors->has('display_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('display_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Status: </label>
                            <div class="col-sm-3">
                                {!! Form::select('status', ['Live' => 'Live', 'Test' => 'Test', 'New' => 'New', 'TurnOff' => 'Turn Off'], $category->Status, ['class' => 'form-control', 'placeholder' => '--Select Status--' ,'required']) !!} 
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Allow Upload: </label>
                            <div class="col-sm-10">
                                @if($category->AllowUpload == 1)
                                    <input type="checkbox" name="allowupload[]" checked/>
                                @else
                                    <input type="checkbox" name="allowupload[]" />
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Show Menu Footer: </label>
                            <div class="col-sm-10">
                                @if($category->MenuFooter == 1)
                                    <input type="checkbox" name="menufooter[]" checked/>
                                @else
                                    <input type="checkbox" name="menufooter[]" />
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Allow Share: </label>
                            <div class="col-sm-10">
                                @if($category->AllowShare == 1)
                                    <input type="checkbox" name="allowshare[]" checked/>
                                @else
                                    <input type="checkbox" name="allowshare[]" />
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Show Top Banner: </label>
                            <div class="col-sm-10">
                                @if($category->TopBannerShow == 1)
                                    <input type="checkbox" name="topbannershow[]" checked/>
                                @else
                                    <input type="checkbox" name="topbannershow[]" />
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Is Expanded: </label>
                            <div class="col-sm-10">
                                @if($category->IsExpanded == 1)
                                    <input type="checkbox" name="isexpanded[]" checked/>
                                @else
                                    <input type="checkbox" name="isexpanded[]" />
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('order') ? ' has-error' : '' }}">
                            <label class="col-sm-2 control-label">Sort Order: </label>
                            <div class="col-sm-2">
                                <input class="form-control" name="order" placeholder="0" min="1" type="number" value="{{$category->SortOrder}}" required>
                                
                                @if ($errors->has('order'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('order') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">View Color: </label>
                            <div class="col-sm-2">
                                <input type="color" class="form-control" name="color" value="{{$category->ViewColor}}">
                            </div>

                            <div class="col-sm-2">
                                @if($category->ViewColor == NULL)
                                    <input type="checkbox" name="no_color" value="1" checked/> No Color                               
                                @else
                                    <input type="checkbox" name="no_color" value="1" /> No Color 
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Type: </label>
                            <div class="col-sm-3">
                                {!! Form::select('type', ['Folder' => 'Folder', 'Article' => 'Article'], $article != null ? 'Article': 'Folder', ['id' => 'type-option', 'class' => 'form-control', 'placeholder' => '--Select Category Type--' ,'required']) !!} 
                            </div>
                        </div>

                        @if($article!=null)
                        <div id="Article" class="category-show">
                        @else
                        <div id="Article" class="category-hide">
                        @endif
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Article: </label>
                                <div class="col-sm-10">
                                {!! Form::select('articleId', $allArticles, $article != null ? $article->Id: null, ['id' => 'type-option', 'class' => 'form-control selArticle', 'placeholder' => '--Select Article--']) !!} 
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Visible to Security Groups: </label>
                            <div class="col-sm-3">
                                <table>
                                    <span id="hb-secgroup" style="display:none;" class="help-block"></span>
                                    @foreach($securitygroups as $sgroup)
                                    <tr>
                                        @if(in_array($sgroup->Id, $catSecGroupIds))
                                            <td><input type="checkbox" name="securitygroups[]" id="securitygroup{{ $sgroup->Id }}" value="{{ $sgroup->Id }}" checked/></td>
                                        @else
                                            <td><input type="checkbox" name="securitygroups[]" id="securitygroup{{ $sgroup->Id }}" value="{{ $sgroup->Id }}"/></td>
                                        @endif
                                        <td><label for="securitygroup{{ $sgroup->Id }}" class="col-sm-2 control-label">{{ $sgroup->DisplayName }}</label></td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                    </div> <!-- end of col-sm-9 -->
                </div> <!-- end of form first group -->

                <div class="form-group">
                    <div class="col-sm-3">
                        <div class="icon-edit">
                            <input type="submit" class="btn btn-warning" value="Update"> 
                            <a href="{{ route('subcategory.show',Session::get('CategoryId')) }}" class="btn btn-danger">Cancel</a> 
                        </div>
                    </div>
                </div>

            </div> <!-- end of form-horizontal -->
        </div> <!-- panel body -->
    </div> <!-- panel-primary -->

    {!! Form::close() !!}


    <!-- SCRIPTS -->

    <script type="text/javascript">

        $("#type-option").change(function() {
            if($(this).val() == "Article") {
                // show article combo box
                $('#Article').removeClass('category-hide').addClass('category-show');
                $(".selArticle").attr('required',true);
            } else {
                // hide article combo box
                $('#Article').removeClass('category-show').addClass('category-hide');
                $(".selArticle").attr('required',false);
            }
        });

        //------------------ IMAGE PREVIEW -------------//
        function readURL() {
            var $input = $(this);      

            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();

            if (extn == "png" || extn == "jpg" || extn == "jpeg") 
            {
                if (typeof (FileReader) != "undefined") 
                {            
                    if (this.files && this.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#newIcon').attr('src', e.target.result).show();
                            $(".imgPreview").hide();
                        }
                        reader.readAsDataURL(this.files[0]);                
                    }
                } 
                else 
                {
                    alert("This browser does not support FileReader.");
                }
            } 
            else
            {
                $input.next('.inputPreview').hide();
                $(this)[0].value = '';

                alert("Please select images only.");
            }
        }

        $(".imgInput").change(readURL);


        // ------- HIDING PREVIEW TO CHANGE IMAGE -------- //        
        function hideImgActive($id){    
            $("#imgActive"+$id).hide();
            $("#imgReplace"+$id).show();
        }

        function validateFields() {
            // category should be visible to at least one security group
            if($('input[name^="securitygroups"]:checked').size() <= 0) {
              //  $("#hb-secgroup").css("display","block");
              //  $("#hb-secgroup").text("Category should be visible to at least one security group.");
                return true;
            } else {
                $("#hb-secgroup").css("display","none");
                return true;
            }
        }

    </script>

@endsection