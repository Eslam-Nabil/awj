<div class="form-group">
    <label for="inputText1" class="col-form-label">Page Name</label>
    <input id="inputText1" type="text" value="{{ $pagedata->page_name ?? '' }}" name="page_name" class="form-control">
</div>
<div class="form-group">
    <label for="inputText2" class="col-form-label">Slug</label>
    <input id="inputText2" type="text" value="{{ $pagedata->slug ?? '' }}" name="slug" class="form-control">
</div>
<div class="form-group">
    <label for="inputText2" class="col-form-label">Slogan</label>
    <input id="inputText2" type="text" value="{{ $pagedata->slogan ?? '' }}" name="slogan" class="form-control">
</div>
<div class="form-group">
    <label for="inputText2" class="col-form-label">Main Title</label>
    <input id="inputText2" type="text" value="{{ $pagedata->main_title ?? '' }}" name="main_title" class="form-control">
</div>

<div class="form-group">
    <label for="inputText2" class="col-form-label">meta Title</label>
    <input id="inputText2" type="text" value="{{ $pagedata->meta_title ?? '' }}" name="meta_title" class="form-control">
</div>
<div class="form-group">
    <label for="inputText2" class="col-form-label">Meta Description</label>
    <input id="inputText2" type="text" value="{{ $pagedata->meta_description ?? '' }}" name="meta_description" class="form-control">
</div>
<div class="custom-file mb-3">
    <input type="file" class="custom-file-input" value="{{ $pagedata->main_image_path ?? '' }}" name="main_image_path" id="customFile">
    <label class="custom-file-label"  for="customFile">page main image</label>
</div>
<div class="custom-file mb-3">
    <input type="file" class="custom-file-input" value="{{ $pagedata->second_image_path ?? '' }}" name="second_image_path" id="customFile">
    <label class="custom-file-label"  for="customFile">page second image</label>
</div>
<div class="form-group">
    <label for="exampleFormControlTextarea1">Main Description</label>
    <textarea class="form-control"  name="main_description" id="exampleFormControlTextarea1" rows="5">
        {{ $pagedata->main_description ?? '' }}
    </textarea>
</div>
<div class="form-group">
    <label for="exampleFormControlTextarea1">Second Description</label>
    <textarea class="form-control"  name="second_description" id="exampleFormControlTextarea1" rows="5">
        {{ $pagedata->second_description ?? '' }}
    </textarea>
</div>
