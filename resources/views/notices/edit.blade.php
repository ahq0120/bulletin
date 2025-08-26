@extends('layouts.app')
@section('content')
<div class="card shadow-sm">
  <div class="card-header fw-bold">編輯公佈事項</div>
  <div class="card-body">
    <form method="post" action="{{ route('notices.update', $notice, false) }}">
      @csrf @method('PUT')
      @include('notices._form')
    </form>
  </div>
</div>
@endsection
