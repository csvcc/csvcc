<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-3-8 0008 15:34:40
 */

namespace hidsvip\form\Builder;

class H extends \hidsvip\form\Builder
{

    public function text($name, $title, $value = '', $option = [])
    {
        // TODO: Implement text() method.
    }

    public function hidden($name, $title, $value = '', $option = [])
    {
        // TODO: Implement hidden() method.
    }

    public function password($name, $title, $value = '', $option = [])
    {
        // TODO: Implement password() method.
    }

    public function date($name, $title, $value = '', $option = [])
    {
        // TODO: Implement date() method.
    }

    public function radio($name, $title, $enum, $value = '', $option = [])
    {
        $radio = '';
        foreach ($enum as $k => $v) {
            if ($k === $value) {
                $radio .= <<<EOF
    <div class="radio-inline i-checks">
      <label> <input type="radio" value="$k" name="$name" checked="checked"> <i></i> $v</label>
    </div>
EOF;
            } else {
                $radio .= <<<EOF
    <div class="radio-inline i-checks">
      <label> <input type="radio" value="$k" name="$name"> <i></i> $v</label>
    </div>
EOF;
            }
        }

        $html = <<<EOF
<div class="form-group">
  <label class="col-sm-2 control-label">$title </label>
  <div class="col-sm-5">
    $radio
  </div>
  <label class="col-sm-5"><span></span></label>
</div>
<div class="hr-line-dashed"></div>
EOF;

        return $html;
    }

    public function checkbox($name, $title, $enum, $value = [], $option = [])
    {
        // TODO: Implement checkbox() method.
    }

    public function submit($action, $option = [])
    {
        // TODO: Implement submit() method.
    }

    public function reset($option = [])
    {
        // TODO: Implement reset() method.
    }

    public function file($name, $title, $value = '', $option = [])
    {
        // TODO: Implement file() method.
    }

    public function files($name, $title, $value = [], $option = [])
    {
        // TODO: Implement files() method.
    }

    public function image($name, $title, $value = '', $option = [])
    {
        $name_id = str_replace(['[', ']', '.', '"'], '-', $name);
        $value   = empty($value) ? '/public/plugins/hid/form-builder/add-image.png' : $value;
        $html    = <<<EOF
<div class="form-group">
  <label class="col-sm-2 control-label">$title</label>
  <div class="col-sm-5">
    <div id="hid-$name_id" class="input-group">
        <input type="text" name="$name" value="$value" readonly class="form-control">
        <span class="input-group-btn"> <button type="button" class="btn btn-primary">选择图片</button> </span>
    </div>
    <div class="hid-image-view">
      <img src="$value" />
      <span class="fa fa-close hid-js-image-remove"></span>
    </div>
  </div>
  <div class="col-sm-5">
  </div>
</div>
<div class="hr-line-dashed"></div>
<script>
$('#hid-$name_id').click(function() {
    require(["layer/layer.min"], function(){
        layer.open({
          title: '选择图片',
          type: 1,
          resize:false,
          area: ['650px', '600px'], //宽高
          //content: '<div id="upload_wrapper"><div id="container"><div id="uploader"><div class="queueList"><div id="dndArea" class="placeholder"><div id="filePicker"></div><p>还可使用QQ截图粘贴或直接将照片拖到这里</p></div></div><div class="statusBar" style="display:none;"><div class="progress"><span class="text">0%</span><span class="percentage"></span></div><div class="info"></div><div class="btns"><div id="filePicker2"></div><div class="uploadBtn">开始上传</div></div></div></div></div></div>',
          content:  '<div class="tabs-container">' +
                        '<ul class="nav nav-tabs">' +
                            '<li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 本地上传</a></li>' +
//                            '<li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false"> 远程下载</a></li>' +
//                            '<li class=""><a data-toggle="tab" href="#tab-3" aria-expanded="false"> 图片库</a></li>' +
                        '</ul>' +
                        '<div class="tab-content">' +
                            '<div id="tab-1" class="tab-pane active"><div id="upload_wrapper"><div id="container"><div id="uploader"><div class="queueList"><div id="dndArea" class="placeholder"><div id="filePicker"></div><p>还可使用QQ截图粘贴或直接将照片拖到这里</p></div></div><div class="statusBar" style="display:none;"><div class="progress"><span class="text">0%</span><span class="percentage"></span></div><div class="info"></div><div class="btns"><div id="filePicker2"></div><div class="uploadBtn">开始上传</div></div></div></div></div></div></div>' +
                            '<div id="tab-2" class="tab-pane">暂不支持远程下载</div>' +
                            '<div id="tab-3" class="tab-pane">暂不支持图片库</div' +
                        '</div>' +
                    '</div>',
        });
        require(["web-uploader/webuploader", "web-uploader/app"], function(WebUploader) {
          hid.webuploader.init(WebUploader, '$name', '$name_id', 1);
        });
    });
});
require(["hid/form-builder/form-builder"]);
</script>
EOF;

        return $html;
    }

    public function images($name, $title, $value = [], $option = [])
    {
        $name_id = str_replace(['[', ']', '.', '"'], '-', $name);
        $value   = addslashes(json_encode($value));
        $html    = <<<EOF
<div class="form-group">
  <label class="col-sm-2 control-label">$title</label>
  <div class="col-sm-10 hid-images-view">
    <input type="hidden" name="{$name}" value="">
    <ul></ul>
    <div id="hid-{$name_id}" class="input-group hid-images-add">
        <img src="/public/plugins/hid/form-builder/add-image.png" />
    </div>
  </div>
</div>
<div class="hr-line-dashed"></div>
<script>
$('#hid-{$name_id}').click(function() {
    require(["layer/layer.min"], function(){
        layer.open({
          title: '选择图片',
          type: 1,
          resize:false,
          area: ['650px', '600px'], //宽高
          content:  '<div class="tabs-container">' +
                        '<ul class="nav nav-tabs">' +
                            '<li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true"> 本地上传</a></li>' +
//                            '<li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false"> 远程下载</a></li>' +
//                            '<li class=""><a data-toggle="tab" href="#tab-3" aria-expanded="false"> 图片库</a></li>' +
                        '</ul>' +
                        '<div class="tab-content">' +
                            '<div id="tab-1" class="tab-pane active"><div id="upload_wrapper"><div id="container"><div id="uploader"><div class="queueList"><div id="dndArea" class="placeholder"><div id="filePicker"></div><p>或将照片拖到这里，单次最多可选300张</p></div></div><div class="statusBar" style="display:none;"><div class="progress"><span class="text">0%</span><span class="percentage"></span></div><div class="info"></div><div class="btns"><div id="filePicker2"></div><div class="uploadBtn">开始上传</div></div></div></div></div></div></div>' +
                            '<div id="tab-2" class="tab-pane">暂不支持远程下载</div>' +
                            '<div id="tab-3" class="tab-pane">暂不支持图片库</div' +
                        '</div>' +
                    '</div>',
        });
        require(["web-uploader/webuploader", "web-uploader/app"], function(WebUploader) {
          hid.webuploader.init(WebUploader, '$name', '$name_id', 300);
        });
    });
});
require(["hid/form-builder/form-builder", "web-uploader/app"], function() {
  hid.formBuilder.fillImagesView('$name_id', '$name', '$value');
});
</script>
EOF;

        return $html;
    }

    public function video($name, $title, $value = '', $option = [])
    {
        // TODO: Implement video() method.
    }

    public function textarea($name, $title, $value = '', $option = [])
    {
        // TODO: Implement textarea() method.
    }

    public function editor($name, $title, $value = '', $option = [])
    {
        $html = <<<EOF
<div class="form-group">
  <label class="col-sm-2 control-label">$title </label>
  <label class="col-sm-10">
    <textarea name="$name" class="summernote">$value</textarea>
  </label>
</div>
<script>
require(["bootstrap/js/bootstrap.min", "summernote/summernote.min"], function () {
    require(["summernote/lang/summernote-zh-CN"], function () {
        $('.fadeInRight').removeClass('fadeInRight');
        $('.summernote').summernote({
          lang: "zh-CN",
          callbacks: {
            onImageUpload: function(files) {
                var sendFile = function(item, file) {
                  data = new FormData();
                    data.append("file", file);
                    $.ajax({
                        data: data,
                        type: "post",
                        url: "/api/uploader/image",
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(url) {
                          $(item).summernote('insertImage', url, 'image name');
                        }
                    });
                }
              img = sendFile(this, files[0]);
            }
          }
        });
    });
});
</script>
<div class="hr-line-dashed"></div>
EOF;

        return $html;
    }

    public function select($name, $title, $enum, $value = '', $option = [])
    {
        // TODO: Implement select() method.
    }

    public function area($name, $title, $value = '', $option = [])
    {

    }
}