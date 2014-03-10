<input type="file" name="file_upload" id="file_upload" />
<div class="upload-info" id="upload-info"></div>
<p><a href="javascript:$('#file_upload').uploadify('settings', 'formData', {'typeCode':document.getElementById('id_file').value});$('#file_upload').uploadify('upload','*')">上传</a>
<a href="javascript:$('#file_upload').uploadify('cancel','*')">重置上传队列</a>
</p>
<input type="hidden" value="1215154" name="tmpdir" id="id_file">
<br />
<div id="uploadText"></div>