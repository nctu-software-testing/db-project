@extends('base')
@section('content')
    　  明文: <input type="text" id="plaintext">
    <button onclick="Encrypt()">加密</button>
@endsection
@section('eofScript')
    <script>
        function Encrypt() {
            let encrypt = new JSEncrypt();
            GetPublicKey().then(key => {
                encrypt.setKey(key);
                var encrypted = encrypt.encrypt($("#plaintext").val());
                ajax('POST', '{{action('KeyController@postDecrypt')}}',
                    {
                        cipher_text: encrypted
                    }
                ).then(d => {
                    if (d.success) {
                        let text = d.result;
                        alert(text);
                        toastr.success(d.result);
                        // location.reload();
                    } else {
                        toastr.error(d.result);
                    }
                });
            });
        }
    </script>
@endsection

