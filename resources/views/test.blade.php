@extends('base')
@section('content')
　  明文: <input type="text" id="plaintext">
    <button onclick="Encrypt()">加密</button>
    <h3>
        Public Key = <br>
        {{$puk}}
    </h3>
@endsection
@section('eofScript')
    <script>
        function Encrypt() {
            var encrypt = new JSEncrypt();
            encrypt.setPublicKey(`{{$puk}}`);
            var encrypted = encrypt.encrypt($("#plaintext").val());
            var promise = ajax('POST', '{{action('TestController@decrypt')}}', {
                cipher_text: encrypted}
                );
            promise.then(d => {
                if (d.success) {
                    alert(d.result);
                    toastr.success(d.result);
                    location.reload();
                } else {
                    toastr.error(d.result);
                }
            });
        }
    </script>
@endsection

