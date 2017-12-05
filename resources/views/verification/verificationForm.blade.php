<hr/>
<h4>上傳證件</h4>
<form action="{{action('VerificationController@verification')}}" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-12">
            <div class="id-card">
                <img src="{{asset("images/card_front.png")}}"/>
                <input type="file" class="img-uploader" name="file1" id="uploader1" required accept="image/*">
            </div>

            <div class="id-card">
                <img src="{{asset("images/card_back.png")}}"/>
                <input type="file" class="img-uploader" name="file2" id="uploader2" required accept="image/*">
            </div>
        </div>
        <div class="col-12">

            <button type="submit" name="_token"
                    value="{{csrf_token()}}"
                    class="btn btn-dtc waves-effect waves-light">
                送出
            </button>
        </div>
    </div>
</form>
@section('eofScript')
    <script>
        $('.img-uploader').change(function () {
            const DEF_ATTR = 'data-default';
            let imgObj = this.parentNode.querySelector('img');
            let defaultSrc = imgObj.getAttribute(DEF_ATTR) || imgObj.src;
            imgObj.setAttribute(DEF_ATTR, defaultSrc);

            let f = this.files[0];
            if (!this.value || !f.type.startsWith("image/")) {
                if (this.value) {
                    toastr.error("請選擇圖片檔");
                }
                this.value = "";
                imgObj.src = defaultSrc;
                return;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                imgObj.src = this.result;
            };
            reader.readAsDataURL(f);
        });
    </script>
@endsection
