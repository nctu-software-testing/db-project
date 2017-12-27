@extends('management.base')
@section('content')
    <div class="card card-body">
        <h4 class="card-title">管理會員</h4>
        <div class="card-text">
            <!--Table-->
            <table class="table table-hover table-striped table-bordered table-sm">

                <!--Table head-->
                <thead class="blue-grey lighten-4">
                <tr>
                    <th>帳號</th>
                    <th>姓名 (性別)</th>
                    <th>身分</th>
                    <th>生日</th>
                    <th>Email</th>
                    <th>驗證狀態</th>
                    <th>密碼</th>
                </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                @for ($i = 0; $i < count($data); $i++)
                    <tr>
                        <td>{{$data[$i]->account}}</td>
                        <td>{{$data[$i]->name}}</td>
                        <td>{{$data[$i]->roleCh()}}</td>
                        <td>{{$data[$i]->birthday}}</td>
                        <td>{{$data[$i]->email}}</td>
                        <td>{{$data[$i]->enable?'通過':'未通過'}}</td>
                        <td><a href="javascript: ChangePwd({{$data[$i]->id}})" style="color:#0275d8;">更改</a></td>
                    </tr>
                @endfor
                </tbody>

                <!--Table body-->
            </table>
            <!--Table-->
    </div>
@endsection
@section('eofScript')
<script>
    function ChangePwd(id) {
        let alert = bAlert('修改密碼', `
            <form class="form">
                <div class="md-form form-group">
                    <input type="password" id="newPwdInput" class="form-control validate" required>
                    <label for="newPwdInput">Type your new password</label>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-sm btn-amber">更改</button>
                    <input type="hidden" name="id" value="${id}" id="userID"/>
                </div>
            </form>
            `);
        alert.find('.modal-footer').remove();
        alert.find('form').on('submit', function () {
            ajax('POST', '{{action('AdminController@changePassword')}}', {id: $("#userID").val(), newpassword: $("#newPwdInput").val()})
                .then(d => {
                    if (d.success) {
                        toastr.success(d.result);
                        location.reload();
                    } else {
                        toastr.error(d.result);
                    }
                });
        });
    }

</script>
@endsection