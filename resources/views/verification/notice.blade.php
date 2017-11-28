@if (session()->has('user') && session('user.enable', 0)!==1)
    <script>
        $(() => toastr.warning('你未通過驗證，快去驗證', '', {
            positionClass: 'toast-bottom-right',
            timeOut: 1e20,
            onclick: () => location.href = '{{action('VerificationController@getVerification')}}'
        }));
    </script>
@endif