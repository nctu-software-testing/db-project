 <form action="sell" method="post" enctype="multipart/form-data" id="Form">
        商品名稱:<input type="text" name="title" required><br>
        商品類別:<select name="category" required>
        @for ($i = 0; $i < count($category); $i++)
                　<option value={{$category[$i]->id}}>{{$category[$i]->product_type}}</option>
        @endfor
        </select><br>
        價格:<input type="number" name="price" id="price" min="0" required><br>
        上架日期:
        <div>
        <input type="date" name="expiration_date" id="d1" required>
        <input type="time" name="expiration_time" id="t1" required>
        </div>
        下架日期:
        <div>
            <input type="date" name="end_date" id="d2" required>
            <input type="time" name="end_time" id="t2" required>
        </div>
        商品描述:<br>
        <textarea rows="4" cols="50" name="info" style="resize: none;" required></textarea><br>
        商品圖片:<br>
        @for ($i = 1; $i <= 5; $i++)
            <div>
                圖片{{$i}}:
                <input type="file" name="file{{$i}}" id="image{{$i}}"><br>
                <img class="preview{{$i}}" style="max-width: 150px; max-height: 150px;">
            </div>      　
        @endfor


        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="submit" value="送出">
    </form>


<Script>
    @for ($i = 1; $i <= 5; $i++)
$("#image{{$i}}").change(function(){
        Check(this,$('.preview{{$i}}'));
    })
    @endfor

$("#Form").submit(function () {
        var d1=$("#d1").val();
        var d2=$("#d2").val();
        var t1=$("#t1").val();
        var t2=$("#t2").val();
        var p=$("#price").val();
        var dt1=d1+" "+t1;
        var dt2=d2+" "+t2;
        if(dt2<dt1)
        {
            alert("下架時間需大於上架時間");
            return false;
        }
        if(p<=0)
        {
            alert("價格需大於0");
            return false;
        }
    })
    var re = /\.(jpg|gif|png)$/; //允許的圖片副檔名
    function Check(f,p) {
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
</Script>
