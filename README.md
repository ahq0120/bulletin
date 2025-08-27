# Bulletin

## 功能（Features）

- 公告 CRUD：標題、公布者（固定 `Administrator`）、公布日期、截止日期、內容（HTML）
- 富文字編輯：CKEditor 5（Classic build）
- 圖片／附件上傳：前端 POST 至 `/uploads/ck`，回傳 URL 直接插入 CKEditor
- 列表分頁：每頁 10 筆（`?page=`）
- 簡易搜尋：`?q=`（針對標題 / 公布者模糊查詢）
- 媒體公開路徑：`/storage/notice-uploads/YYYY/MM/...`（相對路徑，避免入口網域/埠號變更後失效）

---

## 技術棧（Tech Stack）

- Laravel 10/11、PHP 8.1+
- MySQL 8 或 MariaDB 10.x（皆可）
- 前端：CKEditor 5、Bootstrap 5

---

## 專案結構（重點）

app/
  Http/Controllers/
    NoticeController.php      # 列表/搜尋/分頁/CRUD
    UploadController.php      # CKEditor 圖片/附件上傳端點
  Models/Notice.php
database/
  migrations/*_create_notices_table.php
resources/views/notices/
  index.blade.php             # 列表 + 搜尋 + 分頁（10 筆/頁）
  _form.blade.php             # 表單 + CKEditor +「上傳附件並插入連結」
  create.blade.php / edit.blade.php / show.blade.php
routes/web.php                # RESTful 路由 + 上傳端點
public/storage -> storage/app/public   # 由 `php artisan storage:link` 建立

---

## 路由與 API（Routes & API）

RESTful（Route::resource('notices', NoticeController::class)）
- notices.index   | GET        | /notices                   | 列表（支援 `?q=` 搜尋、`?page=` 分頁）
- notices.create  | GET        | /notices/create            | 新增表單
- notices.store   | POST       | /notices                   | 建立
- notices.show    | GET        | /notices/{notice}          | 檢視
- notices.edit    | GET        | /notices/{notice}/edit     | 編輯表單
- notices.update  | PUT/PATCH  | /notices/{notice}          | 更新
- notices.destroy | DELETE     | /notices/{notice}          | 刪除

列表搜尋與分頁（index）
- `?q=keyword`：對 `title` / `author` 進行 LIKE 模糊搜尋
- `?page=2`：切換頁碼（每頁 10 筆；withQueryString() 會保留查詢參數）

上傳端點（CKEditor 5）
- 方法：POST /uploads/ck
- 表單欄位：upload=<file>
- 回應（JSON）：
  { "uploaded": 1, "fileName": "原檔名.ext", "url": "/storage/notice-uploads/YYYY/MM/隨機檔名.ext" }

> 回傳 `url` 為 **相對路徑 `/storage/...`**；正式環境請確保 CSRF/CORS 設定妥當。

---

## 資料庫結構（Database Schema）

業務表：notices
- id            BIGINT UNSIGNED  PK AI        # 主鍵
- title         VARCHAR(200)     NOT NULL     # 標題
- author        VARCHAR(100)     NOT NULL     DEFAULT 'Administrator'  # 公布者（固定）
- published_at  DATE             NOT NULL     # 公布日期
- due_date      DATE             NULL         # 截止日期
- content       LONGTEXT         NULL         # CKEditor HTML
- created_at    TIMESTAMP        NULL
- updated_at    TIMESTAMP        NULL

說明
- 專案可能同時包含 Laravel 預設/可選系統表（如 `users`、`migrations`、`jobs` 等）；日常 CRUD 僅需關注 `notices`。
