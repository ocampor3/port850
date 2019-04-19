@extends('layouts.master')

@section('page-title')

  THEME MANAGEMENT

@endsection

@section('content')

  <div class="top-btn">
    <a href="{{ route('theme.create') }}" class="btn btn-success" role="button"> <span class="glyphicon glyphicon-plus"></span><b> CREATE NEW THEME </b></a>
  </div>

  <table class="theme">
    <tbody>
        <th>Theme Name</th>
        <th>Action</th>
      </tr> 
    @foreach($theme as $theme)
      <tr>
        <td>
          <a href="{{ route('theme.show', $theme->Id) }}" class="btn btn-primary" role="button"><span class="fa fa-eye"></span></a>  
          <a href="{{ route('theme.edit', $theme->Id) }}" class="btn btn-warning" role="button"><span class="fa fa-edit"></span></a>
          <a href="#" class="btn btn-danger" role="button" ata-toggle="modal" data-target="#delModal"><span class="fa fa-trash"></span></a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

@endsection
