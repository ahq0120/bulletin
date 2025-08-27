<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    // 列表 + 分頁 + 簡單搜尋（標題／公布者）
    public function index(Request $request)
    {
        $q = $request->input('q');

        $notices = Notice::when($q, function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('author', 'like', "%{$q}%");
            })
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('notices.index', compact('notices', 'q'));
    }

    // 顯示單筆（可閱讀內容）
    public function show(Notice $notice)
    {
        return view('notices.show', compact('notice'));
    }

    // 新增頁
    public function create()
    {
        $notice = new Notice([
            'author'       => 'Administrator',               // 目前固定
            'published_at' => now()->toDateString(),
        ]);
        return view('notices.create', compact('notice'));
    }

    // 新增處理
    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['author'] = 'Administrator';                  // 後端強制，避免被竄改

        Notice::create($data);
        return redirect()->route('notices.index')->with('ok', '已新增公告');
    }

    // 編輯頁
    public function edit(Notice $notice)
    {
        return view('notices.edit', compact('notice'));
    }

    // 更新處理
    public function update(Request $request, Notice $notice)
    {
        $data = $this->validateData($request);
        $data['author'] = 'Administrator';                  // 仍固定為 Administrator

        $notice->update($data);
        return redirect()->route('notices.index')->with('ok', '已更新公告');
    }

    // 刪除
    public function destroy(Notice $notice)
    {
        $notice->delete();
        return back()->with('ok', '已刪除 1 筆');
    }

    /**
     * 驗證欄位
     * @return array<string, mixed>
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'title'        => ['required','string','max:200'],
            'published_at' => ['required','date'],
            'due_date'     => ['nullable','date','after_or_equal:published_at'],
            'content'      => ['nullable','string'], // CKEditor HTML
        ], [
            'title.required' => '請填寫標題',
            'published_at.required' => '請填寫發佈日期',
            'due_date.after_or_equal' => '截止日期需晚於或等於發佈日期',
        ]);
    }
}
