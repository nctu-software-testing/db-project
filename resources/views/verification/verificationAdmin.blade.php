@extends('management.base')
@section('content')
    <!--Table-->
    <table class="table table-middle">

        <!--Table head-->
        <thead class="blue-grey lighten-4">
        <tr>
            <th>姓名</th>
            <th>身分</th>
            <th>身分證正反面</th>
            <th>驗證結果</th>
            <th>註冊時間</th>
            <th>驗證</th>
        </tr>
        </thead>
        <!--Table head-->

        <!--Table body-->
        <tbody>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                <td>{{$data[$i]->name}}</td>
                <td>{{$data[$i]->role}}</td>
                <td>
                    <a href="{{action('VerificationController@getImage', ['vid'=>$data[$i]->id, 't'=>'front'])}}" class="btn btn-primary btn-sm verify-image">正面</a>
                    <a href="{{action('VerificationController@getImage', ['vid'=>$data[$i]->id, 't'=>'back'])}}" class="btn btn-primary btn-sm verify-image">反面</a>
                </td>
                <td>{{$data[$i]->verify_result}}</td>
                <td>{{$data[$i]->sign_up_datetime}}</td>
                <td>
                    @if($data[$i]->verify_result=="未驗證")
                        <button onclick="Verification('{{$data[$i]->id}}')" class="btn btn-primary btn-sm">驗證</button>
                    @endif
                </td>
            </tr>
        @endfor
        </tbody>

        <!--Table body-->
    </table>
    <!--Table-->

    {{$data->links()}}
@endsection
@section('eofScript')
    <script>
        $(".verify-image").on('click', function (e) {
            window.open(this.href, 'Image', 'height=400,width=600');
            e.preventDefault();
        });

        function Verification(id) {
            let result = "驗證失敗";
            $.post("verification",
                {
                    id: id,
                    result: result,
                    reason: reason
                },
                function (data) {
                    location.reload();
                });
        }
    </script>
@endsection
var result = "未驗證";
var reason = "";
var ok = confirm("驗證通過?");
if (ok) {
result = "驗證成功";
}
else {
reason = prompt("理由?");
