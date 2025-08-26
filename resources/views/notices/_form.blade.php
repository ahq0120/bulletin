@php $isEdit = $notice->exists; @endphp

<div class="mb-3">
  <label class="form-label">標題</label>
  <input name="title" class="form-control" value="{{ old('title',$notice->title) }}" required>
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <label class="form-label">發佈日期</label>
    <input type="date" name="published_at" class="form-control"
           value="{{ old('published_at', optional($notice->published_at)->format('Y-m-d')) }}" required>
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">截止日期</label>
    <input type="date" name="due_date" class="form-control"
           value="{{ old('due_date', optional($notice->due_date)->format('Y-m-d')) }}">
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">公布者</label>
    <input class="form-control" value="{{ $notice->author ?? 'Administrator' }}" disabled>
  </div>
</div>

<div class="mb-3">
  <label class="form-label">公布內容</label>
  <textarea id="content" name="content" class="form-control" rows="12">{{ old('content',$notice->content) }}</textarea>
  <div class="mt-2">
    <button type="button" id="btnUploadAttachment" class="btn btn-sm btn-outline-secondary">
      上傳附件
    </button>
    <input type="file" id="attachmentInput" class="d-none" multiple>
  </div>
</div>

<div class="d-flex gap-2">
  <button class="btn btn-primary">{{ $isEdit ? '儲存變更' : '建立公告' }}</button>
  <a class="btn btn-outline-secondary" href="{{ route('notices.index') }}">返回列表</a>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
  (function () {
    const token = '{{ csrf_token() }}';
    let editor;

    ClassicEditor.create(document.querySelector('#content'), {
      ckfinder: { uploadUrl: '/uploads/ck' }
    })
    .then(ed => {
      editor = ed;
      const btn = document.getElementById('btnUploadAttachment');
      const input = document.getElementById('attachmentInput');

      btn.addEventListener('click', () => input.click());

      input.addEventListener('change', async () => {
        if (!input.files || !input.files.length) return;

        for (const file of input.files) {
          try {
            const fd = new FormData();
            fd.append('upload', file);
            fd.append('_token', token);

            const res = await fetch(`{{ route('uploads.ck', [], false) }}`, {
  		method: 'POST',
  		body: fd,
  		headers: { 'X-CSRF-TOKEN': token }
		});
            const data = await res.json();

            if (!res.ok || !data || data.uploaded !== 1) {
              const msg = (data && data.error && data.error.message) ? data.error.message : '上傳失敗';
              throw new Error(msg);
            }

            editor.model.change(writer => {
              const text = writer.createText(data.fileName || file.name, { linkHref: data.url });
              editor.model.insertContent(text, editor.model.document.selection);

              editor.model.insertContent(writer.createText(' '), editor.model.document.selection);
            });

          } catch (e) {
            alert(`檔案「${file.name}」上傳失敗：` + e.message);
          }
        }

        input.value = '';
      });
    })
    .catch(console.error);
  })();
</script>
@endpush



