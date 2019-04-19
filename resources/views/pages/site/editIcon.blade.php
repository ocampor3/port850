@extends('layouts.master')

@section('title')
    Edit Site Icon | Appzmate 
@endsection

@section('page-title')
   Editing Site Icon
@endsection

@section('content')
    
    {!! Form::model($siteDet, [
        'method' => 'PATCH',
        'route' => ['update-site-icon', $siteDet->Id],
        'files' => 'true',
    ]) !!}

    <div class="panel panel-primary">       
        <div class="panel-body">
            <div class="form-horizontal">

                <div class="form-group">
                    <label class="col-sm-2 control-label">File: </label>
                    <div class="col-sm-10">
                        <input class="imgInput" type="file" name="images" required>
                        <img class="inputPreview" src="#" alt="your image">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <input type="submit" class="btn btn-warning" value="Update"> 
                        <a href="{{ route('theme.show',Session::get('SiteId')) }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
 
            </div> <!-- end of form-horizontal -->
        </div> <!-- panel body -->
    </div> <!-- panel-primary -->  

    {!! Form::close() !!}

    <script type="text/javascript">

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