@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
  <div class="card-header fw-bold">{{ $notice->title }}</div>
  <div class="card-body">
    <div class="mb-2 text-muted">
      公佈者：{{ $notice->author }}　
      發佈日期：{{ optional($notice->published_at)->format('Y-m-d') }}　
      截止日期：{{ optional($notice->due_date)->format('Y-m-d') ?? '—' }}
    </div>
    <hr>
    <div class="content">{!! $notice->content !!}</div>

    <div class="mt-3 d-flex gap-2">
      <a class="btn btn-outline-secondary" href="{{ route('notices.index') }}">返回列表</a>
      <a class="btn btn-primary" href="{{ route('notices.edit',$notice) }}">修改</a>
    </div>
  </div>
</div>
@endsection
