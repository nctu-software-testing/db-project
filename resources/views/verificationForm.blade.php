<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
    </head>
    <body>
    <form action="newverification" method="post" enctype="multipart/form-data">
        <div>
            正面圖片:<input type="file" name="file1" id="uploader1" required><br>
            <img class="preview1" style="max-width: 150px; max-height: 150px;">
            <div class="size"></div>
        </div>
        <div>
            背面圖片:<input type="file" name="file2" id="uploader2" required><br>
            <img class="preview2" style="max-width: 150px; max-height: 150px;">
            <div class="size"></div>
        </div>
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="submit" value="送出">
    </form>


    </body>
</html>
<script>
    var re = /\.(jpg|gif|png)$/; //允許的圖片副檔名
    $("#uploader1").change(function(){
        Check(this,$('.preview1'));
    })
    $("#uploader2").change(function(){
        Check(this,$('.preview2'));
    })
    function  Check(f,p) {
        var file=f.files[0];
        if (file.name.length !=0 && !re.test(file.name)) {
            alert("只允許上傳JPG、PNG或GIF影像檔");
            p.attr('src', "");
            f.value="";
            return false;
        }
        else
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                p.attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    }
</script>
