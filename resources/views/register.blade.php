<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include("header");
        <title>Laravel</title>

    </head>
    <body>
    <form action="register" method="post" enctype="multipart/form-data">
        註冊<br>
        帳號:<input type="text" name="account"><br>
        密碼:<input type="password" name="password"><br>
        類型:
        <input type=radio value="C" name="role" checked="checked"> 顧客
        <input type=radio value="B" name="role"> 商家
        <br>
        姓名/商家名稱:<input type="text" name="name"><br>
        身分證字號/商家統一編號:<input type="text" name="sn"><br>
        性別:
        <input type=radio value="男" name="gender" checked="checked"> 男
        <input type=radio value="女" name="gender"> 女
        <br>
        信箱:<input type="text" name="email"><br>
        生日:<input type="date" id="bookdate" value="2014-09-18" name="birthday" ><br>
        圖片:<input type="file" name="file" id="uploader"><br>
        <div>
            <img class="preview" style="max-width: 150px; max-height: 150px;">
            <div class="size"></div>
        </div>
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="submit" value="送出">

    </form>
    </body>
</html>

<script>
    var re = /\.(jpg|gif|png)$/; //允許的圖片副檔名
    $("#uploader").change(function(){
        var file=this.files[0];
        if (file.name.length !=0 && !re.test(file.name)) {
            alert("只允許上傳JPG、PNG或GIF影像檔");
            this.value="";
            return false;
        }
        else
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    })
</script>