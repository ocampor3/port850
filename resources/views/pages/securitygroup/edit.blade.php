@extends('layouts.master')

@section('title')
Create Security Group | Appzmate 
@endsection



@section('page-title')
Create Security Group
@endsection

@section('content')
{!! Form::model($sgroup, [
    'method' => 'PATCH',
    'route' => ['securitygroup.update', $sgroup->Id],
    'files' => 'true'
    ]) !!}

    <div class="panel panel-primary">       
        <div class="panel-body">
            <div class="form-horizontal">

                <div class="form-group">   
                    <label class="col-sm-2 control-label">Display Name: </label>
                    <div class="col-sm-3">
                        <input class="form-control" name="displayname" type="text" maxlength="160" value="{{ $sgroup->DisplayName }}" required />
                    </div>  
                </div>          
                <input type="hidden" value="{{$sgroup->SiteId}}" name="SiteId" />
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <input type="submit" class="btn btn-primary" value="Save"> 
                        <a href="{{ route('securitygroup.show',$sgroup->SiteId) }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>

            </div> <!-- end of form-horizontal -->
        </div> <!-- panel body -->
    </div> <!-- panel-primary -->

    {!! Form::close() !!}

    @endsection 
