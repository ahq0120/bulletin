@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <form class="row gy-2 gx-2 mb-3" method="get">
      <div class="col-auto">
        <input name="q" value="{{ $q }}" class="form-control" placeholder="搜尋標題/公布者">
      </div>
      <div class="col-auto">
        <button class="btn btn-outline-secondary">搜尋</button>
        <a class="btn btn-link" href="{{ route('notices.index') }}">清除</a>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead>
        <tr>
          <th>標題</th>
          <th class="text-center" width="140">發佈日期</th>
          <th class="text-center" width="140">截止日期</th>
          <th class="text-center" width="120">公布者</th>
          <th class="text-center" width="80">修改</th>
          <th class="text-center" width="80">刪除</th>
        </tr>
        </thead>
        <tbody>
        @forelse($notices as $n)
          <tr>
            <td><a href="{{ route('notices.show',$n) }}">{{ $n->title }}</a></td>
            <td class="text-center">{{ optional($n->published_at)->format('Y-m-d') }}</td>
            <td class="text-center">{{ optional($n->due_date)->format('Y-m-d') ?? '—' }}</td>
            <td class="text-center">{{ $n->author }}</td>
            <td class="text-center">
              <a class="btn btn-sm btn-primary" href="{{ route('notices.edit',$n) }}">修改</a>
            </td>
            <td class="text-center">
              <form method="post" action="{{ route('notices.destroy',$n) }}" onsubmit="return confirm('確認刪除這筆公告？')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">刪除</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="table-empty">尚無資料</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-between align-items-center">
      <div>{{ $notices->links() }}</div>
      <a href="{{ route('notices.create') }}" class="btn btn-success">新增</a>
    </div>
  </div>
</div>
@endsection
