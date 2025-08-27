# Bulletin

## 功能

- 公告：標題、發佈者、發佈日期、截止日期、內容
- 圖片／附件上傳
- 列表分頁：每頁 10 筆
- 簡易搜尋：針對標題 / 公布者模糊查詢
- 修改／刪除

---

## 技術

- Laravel 12、PHP 8.3
- MySQL 8
- 前端：CKEditor 5、Bootstrap 5

---

## 專案結構

app/
  Http/Controllers/
    NoticeController.php      # 列表/搜尋/分頁/CRUD
    UploadController.php      # CKEditor 圖片/附件上傳
  Models/Notice.php
database/
  migrations/*_create_notices_table.php
resources/views/notices/
  index.blade.php             # 列表 + 搜尋 + 分頁（10 筆/頁）
  _form.blade.php             # 表單 + CKEditor + 上傳附件並插入連結
  create.blade.php / edit.blade.php / show.blade.php
routes/web.php                # RESTful 路由 + 上傳端點

---

## Routes 與 API

RESTful（Route::resource('notices', NoticeController::class)）
- notices.index   | GET        | /notices                   | 列表
- notices.create  | GET        | /notices/create            | 新增表單
- notices.store   | POST       | /notices                   | 建立
- notices.show    | GET        | /notices/{notice}          | 檢視
- notices.edit    | GET        | /notices/{notice}/edit     | 編輯表單
- notices.update  | PUT/PATCH  | /notices/{notice}          | 更新
- notices.destroy | DELETE     | /notices/{notice}          | 刪除

---

## 資料庫

notices
- id            BIGINT UNSIGNED  PK AI        # 主鍵
- title         VARCHAR(200)     NOT NULL     # 標題
- author        VARCHAR(100)     NOT NULL     DEFAULT 'Administrator'  # 發布者
- published_at  DATE             NOT NULL     # 公布日期
- due_date      DATE             NULL         # 截止日期
- content       LONGTEXT         NULL         # CKEditor HTML
- created_at    TIMESTAMP        NULL
- updated_at    TIMESTAMP        NULL

