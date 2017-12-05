@extends('verification.verification')
@section('verify')
    <div class="row" id="verify_note">
        <div class="col-12">
            <h2 class="page-title">會員驗證</h2>
            <h4>
                驗證通過後，才可以使用本站的功能喔
            </h4>
            <section>
                <h6>
                    <i class="material-icons">error</i> 注意事項
                </h6>
                <ol>
                    <li>
                        每個證件只能綁定一個帳號
                    </li>
                    <li>
                        證件照不清晰或與輸入的訊息不符，將無法通過驗證
                    </li>
                    <li>
                        您提供的證件訊息將受到嚴格保護，僅用於身份驗證，未經本人許可不會用於其他用途
                    </li>
                </ol>
            </section>
            <section>
                <h6>證件要求</h6>
                <ol>
                    <li>證件正反面照片</li>
                    <li>證件必須在有效期內，且有效期需在一個月以上</li>
                </ol>
            </section>
        </div>
    </div>
    <hr/>
    @if($data)
        @if ($data->verify_result==="驗證失敗")
            <div class="alert alert-warning" role="alert">
                你上次的驗證無法通過審核，請再上傳您的文件。
            </div>
        @elseif ($data->verify_result==='驗證成功')
            <div class="alert alert-success" role="alert">
                驗證成功。
            </div>
        @else
            <div class="alert alert-info" role="alert">
                請耐心等待管理員審核。
            </div>
        @endif
    @endif
    @if(is_null($data) || $data->verify_result==="驗證失敗")
        @include("verification.verificationForm")
    @endif
@endsection
