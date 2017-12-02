@extends('management.base')
@section('content')
    <div class="row">
        <div class="col">
            <!--Panel-->
            <div class="card card-body">
                <h4 class="card-title">會員資料</h4>
                <div class="card-text">
                    <div class="profile-avatar">
                        <div class="img-container rounded-circle hoverable">
                            <img src="{{$data->getAvatarUrl()}}"
                                 class=""/>
                            <input type="file" accept="image/*" id="avatar"/>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="label">帳號</span>
                            <span class="content">{{$data->account}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="label">姓名 (性別)</span>
                            <span class="content">{{$data->name}}&nbsp;({{$data->gender}})</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="label">身分</span>
                            <span class="content">{{$data->roleCh()}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="label">生日</span>
                            <span class="content">{{$data->birthday}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="label">Email</span>
                            <span class="content">
                                {{$data->email}}
                                <a class="edit-link waves-effect" href="#">
                                    修改
                                    <i class="material-icons">keyboard_arrow_right</i>
                                </a>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="label">驗證狀態</span>
                            <span class="content">{{$data->enable?'通過':'未通過'}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="label">密碼</span>
                            <span class="content">
                                &nbsp;
                                <a class="edit-link waves-effect" href="#">
                                    修改
                                    <i class="material-icons">keyboard_arrow_right</i>
                                </a>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <!--/.Panel-->
        </div>
    </div>
    <div>
        <table border="1">
            <tr>
                　
                <td>account</td>
                　
                <td>sn</td>
                <td>name</td>
                <td>身份</td>
                <td>birthday</td>
                　
                <td>gender</td>
                　
                <td>email</td>
                　
                <td>驗證</td>
            </tr>
            <tr>
                　
                <td>{{$data->account}}</td>
                　
                <td>{{$data->sn}}</td>
                　
                <td>{{$data->name}}</td>
                　
                <td>{{$data->role}}</td>
                　
                <td>{{$data->birthday}}</td>
                <td>{{$data->gender}}</td>
                <td>{{$data->email}}</td>
                <td>{{$data->enable}}</td>
            </tr>
        </table>
    </div>
    　
    <button onclick="ChagePassword()">修改密碼</button>
    　
    <button onclick="ChageEmail()">修改信箱</button>
    <div hidden id="ps">
        <form action="changepassword" method="post">
            舊密碼:<input type="password" name="oldpassword" required><br>
            新密碼:<input type="password" name="newpassword" required><br>
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="submit" value="送出">
        </form>
    </div>
    <div hidden id="em">
        <form action="changeemail" method="post">
            信箱:<input type="text" name="email" required><br>
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="submit" value="送出">
        </form>
    </div>
@endsection
@section('eofScript')
    <script>
        function ChagePassword() {
            $("#ps").toggle();
        }

        function ChageEmail() {
            $("#em").toggle();
        }

        $("#avatar").change(function () {
            let imgTarget = this.parentNode.querySelector('img');

            function updateAvatar(id) {
                ajax('POST', '{{action('UserController@postChangeAvatar')}}', {avatar: id})
                    .then(d => {
                        if (d.success) {
                            toastr.success('Uploaded');
                            imgTarget.src = d.result.url;
                        }
                    })
            }

            if (this.value) {
                toastr.info('Uploading...');
                Imgur.UploadImage(this.files[0])
                    .then(function (r) {
                        if (r.success) {
                            updateAvatar(r.data.id);
                        } else {
                            toastr.error('Failed to upload avatar');
                        }

                    });
            } else {
                updateAvatar('');
            }
        });
    </script>
@endsection