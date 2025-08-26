@extends('layouts.app')
@section('content')
<div class="card shadow-sm">
  <div class="card-header fw-bold">新增公佈事項</div>
  <div class="card-body">
    <form method="post" action="{{ route('notices.store', [], false) }}">
      @csrf
      @include('notices._form')
    </form>
  </div>
</div>
@endsection
