@extends('layouts.master')

@section('page-title')
   FILE LIST
@endsection

@section('content')

<table class="theme">
    <tbody>
        <tr>
            <th>File Name</th>
            <th class="div-center">Modified Date</th>
            <th class="div-center">Action</th>
        </tr>   

            <tr>
                <td><a href="#">ARTICLE NAME LINK TYPE</a></td>
                <td class="div-center">
                    date here
                </td>
                <td class="div-center">
                    <a href="#" class="btn btn-danger" role="button"><span class="fa fa-trash"></span> Delete</a>   
                </td>
            </tr>
    </tbody>
</table>


@endsection
