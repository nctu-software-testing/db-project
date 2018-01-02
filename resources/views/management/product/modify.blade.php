@extends('base')
@section('extraScript')
    <script type="text/javascript" src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
@endsection
@section('content')

    <form action="{{action('ProductController@postSell')}}" method="post" enctype="multipart/form-data" id="Form">
        <input type="hidden" name="Edit_id" id="Edit_id" value={{$id}}>
        <div class="col-md-12 col-md-offset-0 payment-method">
            <table class="table">
                <tbody>
                <tr>
                    <td width="10%">商品名稱:</td>
                    <td><input type="text" name="title" value="{{$editdata->product_name}}" required><br></td>
                </tr>
                <tr>
                    <td>商品類別:</td>
                    <td>
                        <select class="mdb-select" name="category" required>
                            @for ($i = 0; $i < count($category); $i++)
                                　
                                <option value={{$category[$i]->id}}
                                @if($category[$i]->id==$editdata->category_id)
                                        selected
                                        @endif
                                >{{$category[$i]->product_type}}
                                </option>
                            @endfor
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>價格:</td>
                    <td><input type="number" name="price" id="price" value="{{$editdata->price}}" min="0" required><br>
                    </td>
                </tr>
                <tr>
                    <td>上架日期:</td>
                    <td>
                        <div>
                            <input type="date" name="start_date" value="{{$editdata->GetDateTime(0)}}" id="d1" required>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td>下架日期:</td>
                    <td>
                        <div>
                            <input type="date" name="end_date" value="{{$editdata->GetDateTime(2)}}" id="d2" required>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td>數量:</td>
                    <td>
                        <div>
                            <input type="number" name="amount" min="1" value="{{$editdata->amount or 10}}" required>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td colspan="2">
                        <label for="info">商品描述:</label>
                        <textarea rows="4" cols="50" name="info" id="info" style="resize: none;"
                                  required>{{$editdata->product_information}}</textarea><br>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label>
                            商品圖片:
                        </label>
                        <p></p>
                        <div id="imagesContainer">
                            @for ($i = 0; $i < $count; $i++)
                                <div class="upload-container">
                                    <div class="close-btn">&times;</div>
                                    <img class="img"
                                         src="{{action('ProductController@getImage', ['pid'=>$id, 'id'=>$i])}}">
                                    <span class="addImageBtn">&plus;</span>
                                    <input type="hidden" class="del" name="delImage[{{$i}}]" value="0"/>
                                </div>
                            @endfor
                            <div class="upload-container" id="uploaderBase">
                                <div class="close-btn">&times;</div>
                                <img class="img" src="">
                                <input type="file" accept="image/*" name="productImage[]"/>
                                <span class="addImageBtn">&plus;</span>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    </td>
                </tr>
                </tbody>
            </table>
            @if($id==='add')
                <input type="submit" value="上架" class="btn btn-primary">
            @else
                <button type="submit" class="btn btn-primary">編輯</button>
                <button type="button" onclick="Cancel()" class="btn btn-warning">取消編輯</button>
            @endif
        </div>


    </form>


    <script>
        function Cancel() {
            location.href = "{{action('ProductController@getProducts')}}";
        }

        $("#Form").submit(function () {
            var d1 = $("#d1").val();
            var d2 = $("#d2").val();
            var p = $("#price").val();
            if (d2 < d1) {
                alert("下架時間需大於上架時間");
                return false;
            }
            if (p <= 0) {
                alert("價格需大於0");
                return false;
            }
            $(".upload-container").each(function(){
                let input = this.querySelector('input[type="file"]');
                if(input.value===''){
                    input.parentNode.removeChild(input);
                }
            })
        });

        (function () {
            const IMG_LIMIT = parseInt('{{$imgLimit}}');
            let base = $(".upload-container");
            const UPLOAD_CONTAINER_VAILD_SELECTOR = '.upload-container:visible';
            base
                .find("input[type='file']")
                .on('change', function () {
                    let container = $(this.parentNode.parentNode);
                    let uploader = $(this.parentNode).clone(true);
                    let img = uploader.find('img')[0];
                    uploader.removeAttr('id');
                    if (this.value) {
                        let fr = new FileReader();
                        fr.onload = () => {
                            img.src = fr.result;
                            uploader.insertBefore(this.parentNode);
                            if ($(UPLOAD_CONTAINER_VAILD_SELECTOR).length > IMG_LIMIT) {
                                base.filter('#uploaderBase').hide();
                            }
                            this.value = "";
                        };
                        fr.readAsDataURL(this.files[0]);
                    } else {
                        img.src = "";
                    }
                });

            base.find('.close-btn').on('click', function () {
                let p = $(this.parentNode);
                p.hide();
                p.find('.del').val(1);
                if ($(UPLOAD_CONTAINER_VAILD_SELECTOR).length < IMG_LIMIT) {
                    base.filter('#uploaderBase').show();
                }
            });
            if (base.length > IMG_LIMIT) base.filter('#uploaderBase').hide();
        })();
        CKEDITOR.replace('info');
    </script>

@endsection