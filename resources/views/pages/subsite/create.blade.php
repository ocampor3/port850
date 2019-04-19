@extends('layouts.master')

@section('title')
    Create Subsite | Appzmate 
@endsection

@section('page-title')
   Create Subsite for {{$site->Title}}
@endsection

@section('content')

    {!! Form::open([      
        'method' => 'POST',
        'action' => 'SiteController@store',
        'files' => 'true'
    ]) !!}

    <div id="panel-style" class="panel panel-primary">              
        <div class="panel-body">

            <div class="form-horizontal"> 
                <div class="form-group">
                    <label class="col-sm-2 control-label">Icon: </label>
                    <div class="col-sm-10">
                        <input class="imgInput" type="file" name="images">
                        <img class="inputPreview" src="#" alt="your image">
                    </div>
                </div>

                <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">              
                    <label class="col-sm-2 control-label">Title: </label>
                    <div class="col-sm-10">
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" maxlength="160" required />
                    
                        @if ($errors->has('title'))
                            <span class="help-block">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group {{ $errors->has('url') ? ' has-error' : '' }}">   
                    <label for="title" class="col-sm-2 control-label">Site URL:</label>    
                    <div class="col-sm-10">                              
                        <input type="text" value="{{ old('url') }}" name="url" class="form-control" maxlength = "255" required />

                        @if ($errors->has('url'))
                            <span class="help-block">
                                <strong>{{ $errors->first('url') }}</strong>
                            </span>
                        @endif
                    </div>       
                </div>

                <div class="form-group {{ $errors->has('sitecode') ? ' has-error' : '' }}">   
                    <label for="title" class="col-sm-2 control-label">Site Code:</label>    
                    <div class="col-sm-10">                              
                        <input type="text" value="{{ old('sitecode') }}" name="sitecode" class="form-control" maxlength = "100" required />

                        @if ($errors->has('sitecode'))
                            <span class="help-block">
                                <strong>{{ $errors->first('sitecode') }}</strong>
                            </span>
                        @endif
                    </div>       
                </div>

                <input type=hidden name="parentid" value={{ $site->Id}}>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Password Required: </label>
                    <div class="col-sm-10">
                        <input type="checkbox" name="passwordrequired" value="1"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Show Menu Footer: </label>
                    <div class="col-sm-10">
                        <input type="checkbox" name="menufooter" value="1"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Allow Favorites: </label>
                    <div class="col-sm-10">
                        <input type="checkbox" name="allowfavorites" value="1"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Show Top Banner: </label>
                    <div class="col-sm-10">
                        <input type="checkbox" name="topbannershow" value="1"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Show Hamburger Footer: </label>
                    <div class="col-sm-10">
                        <input type="checkbox" name="hamburgerfooter" value="1"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Show In Login: </label>
                    <div class="col-sm-10">
                        <input type="checkbox" name="showinlogin" value="1"/>
                    </div>
                </div>

                <div class="form-group">   
                    <label for="title" class="col-sm-2 control-label">Status:</label>    
                    <div class="col-sm-4">                              
                        {!! Form::select('status', ['Live' => 'Live', 'Test' => 'Test', 'New' => 'New', 'TurnOff' => 'Turn Off'], null, 
                            ['id' => 'col-half-width', 'class' => 'form-control', 
                            'placeholder' => '--Select Status--', 'required']) !!} 
                    </div>       
                </div>

                <div class="form-group">   
                    <label for="title" class="col-sm-2 control-label">View Type:</label>    
                    <div class="col-sm-4">                              
                        {!! Form::select('viewtype',['None' => 'None', 'GridView' => 'Grid View','ListView' => 'List View', 'WindowsView' => 'Windows View'], null, 
                            ['id' => 'col-half-width', 'class' => 'form-control', 
                            'placeholder' => '--Select View Type--', 'required']) !!} 
                    </div>       
                </div>     

                <div class="form-group">   
                    <label for="title" class="col-sm-2 control-label">Subview Type:</label>    
                    <div class="col-sm-4">                              
                        {!! Form::select('subviewtype',['None' => 'None', 'GridView' => 'Grid View', 'ListView' => 'List View', 'WindowsView' => 'Windows View'], null, 
                            ['id' => 'col-half-width', 'class' => 'form-control', 
                            'placeholder' => '--Select View Type--', 'required']) !!} 
                    </div>       
                </div>         

                <div class="form-group">
                    <label class="col-sm-2 control-label">Is Article: </label>
                    <div class="col-sm-10">
                        <input type="checkbox" id="isArticle" name="isArticle" value="1"/>
                    </div>
                </div>

                <div id="Article" class="site-hide">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Article: </label>
                        <div class="col-sm-10">
                        {!! Form::select('articleId', $allArticles, null, ['id' => 'type-option', 'class' => 'form-control selArticle', 'placeholder' => '--Select Article--']) !!} 
                        </div>
                    </div>
                </div>

                <div class="form-group">   
                    <label for="title" class="col-sm-2 control-label"></label>    
                    <div class="col-sm-10">                              
                        <input type="submit" class="btn btn-success" value="Save"> 
                        <a href="{{ route('site.index') }}" class="btn btn-danger">Cancel</a>
                    </div>       
                </div>

            </div> <!-- form horizontal -->

        </div> <!-- panel body -->
    </div> <!-- primary panel -->

    {!! Form::close() !!}
    
    <script type="text/javascript">

        $("#isArticle").change(function() {
            if($("#isArticle:checked").val()) {
                // show article combo box
                $('#Article').removeClass('site-hide').addClass('site-show');
                $(".selArticle").attr('required',true);
            } else {
                // hide article combo box
                $('#Article').removeClass('site-show').addClass('site-hide');
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
                            $input.next('.inputPreview').attr('src', e.target.result).show();
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
    </script>

@endsection