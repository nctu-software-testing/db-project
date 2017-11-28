 <form action="sell" method="post" enctype="multipart/form-data" id="Form">
        <input type="hidden" name="Edit_id" id="Edit_id" value={{$id}}>
        商品名稱:<input type="text" name="title" value="{{$editdata->product_name}}" required><br>
        商品類別:<select name="category"required  class="mdb-select">
        @for ($i = 0; $i < count($category); $i++)
                　<option value={{$category[$i]->id}}
                @if($category[$i]->id==$editdata->category_id)
                     selected
                     @endif
                 >{{$category[$i]->product_type}}
                  </option>
        @endfor
        </select><br>
        價格:<input type="number" name="price" id="price" value="{{$editdata->price}}" min="0" required><br>
        上架日期:
        <div>
        <input type="date" name="expiration_date" value="{{$editdata->GetDateTime(0)}}" id="d1" required>
        <input type="time" name="expiration_time" value="{{$editdata->GetDateTime(1)}}" id="t1" required>
        </div>
        下架日期:
        <div>
            <input type="date" name="end_date" value="{{$editdata->GetDateTime(2)}}" id="d2" required>
            <input type="time" name="end_time" value="{{$editdata->GetDateTime(3)}}" id="t2" required>
        </div>
        商品描述:<br>
        <textarea rows="4" cols="50" name="info" style="resize: none;" required>{{$editdata->product_information}}</textarea><br>
        商品圖片:<br>
        @for ($i = 0; $i < $count; $i++)
            <div>
                圖片{{$i}}:
                <input type="file" name="file{{$i}}" id="image{{$i}}" style="display: none;"><br>
                <img class="preview{{$i}}" style="max-width: 150px; max-height: 150px;"  src="product-image/{{$id}}/{{$i}}">
                <button type="button" id="del{{$i}}">刪除圖片</button></td>
                <input type="hidden"  id="delImage{{$i}}" name="delImage{{$i}}" value="0">
            </div>
        @endfor
        @for ($i = $count; $i < 5; $i++)
         <div>
             圖片{{$i}}:
             <input type="file" name="file{{$i}}" id="image{{$i}}"><br>
             <img class="preview{{$i}}" style="max-width: 150px; max-height: 150px;">
         </div>
       @endfor


        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        @if(!request('id'))
        <input type="submit" value="上架">
        @else
         <input type="submit" value="編輯">
         <button type="button" onclick="Cancel('{{$data->currentPage()}}')">取消編輯</button></td>
       @endif
    </form>


<Script>
    function Cancel(p) {
        location.href="/any_buy/public/product?"+"page="+p;
    }

    @for ($i = 0; $i < 5; $i++)
    $("#image{{$i}}").change(function(){
        Check(this,$('.preview{{$i}}'));
    })
    $("#del{{$i}}").click(function(){
        $("#delImage{{$i}}").val(1);
        $("#del{{$i}}").hide();
        $("#image{{$i}}").show();
        $('.preview{{$i}}').attr('src', "");
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
        if(!f.value)
            p.attr('src', "");
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
