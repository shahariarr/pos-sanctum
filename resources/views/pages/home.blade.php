@extends('layout.app')

@section('content')

 <div class="p-5">
     <ul>
         <li>Phase 01-------> Start</li>
         <li>Phase 02</li>
         <li>Phase 03</li>
         <li>Phase 04</li>
         <li>Phase 05</li>

     </ul>
     <a href="{{url("/userLogin")}}" class="btn float-start bg-gradient-primary">Start Here</a>
 </div>

@endsection
