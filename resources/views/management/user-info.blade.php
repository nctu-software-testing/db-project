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
                                <a class="edit-link waves-effect" href="javascript: ChangeEmail()">
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
                                <a class="edit-link waves-effect" href="javascript: ChangePwd()">
                                    修改
                                    <i class="material-icons">keyboard_arrow_right</i>
                                </a>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="label">地址</span>
                            @if(empty($locationData))
                                <span class="content">您尚未新增地址
                                    <a class="edit-link waves-effect" href="{{action('LocationController@getLocation')}}">
                                    立即新增
                                    <i class="material-icons">keyboard_arrow_right</i>
                                </a>
                                </span>
                            @else
                                <span class="content">{{$locationData->zip_code}} {{$locationData->address}}
                                    <a class="edit-link waves-effect" href="{{action('LocationController@getLocation')}}">
                                        新增地址
                                        <i class="material-icons">keyboard_arrow_right</i>
                                    </a>
                                </span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            <!--/.Panel-->
        </div>
    </div>
@endsection
@section('eofScript')
    <script>
        function ChangePwd() {
            let alert = bAlert('修改密碼', `
            <form class="form">
                <div class="md-form form-group">
                    <input type="password" id="oldPwdInput" class="form-control validate" required>
                    <label for="emailInput">Type your current password</label>
                </div>
                <div class="md-form form-group">
                    <input type="password" id="newPwdInput" class="form-control validate" required>
                    <label for="newPwdInput">Type your new password</label>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-sm btn-amber">更改</button>
                </div>
            </form>
            `);
            alert.find('.modal-footer').remove();
            alert.find('form').on('submit', function () {
                ajax('POST', '{{action('UserController@changePassword')}}', {oldpassword: $("#oldPwdInput").val(), newpassword: $("#newPwdInput").val()})
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

        function ChangeEmail() {
            let alert = bAlert('更換信箱', `
            <form class="form">
                <div class="md-form form-group">
                    <input type="email" id="emailInput" class="form-control validate" required>
                    <label for="emailInput">Type your email</label>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-sm btn-amber">更改</button>
                </div>
            </form>
            `);
            alert.find('.modal-footer').remove();
            alert.find('form').on('submit', function () {
                let email = alert.find('#emailInput')[0];
                toastr.info('Updating...');
                ajax('POST', '{{action('UserController@changeEmail')}}', {email: email.value})
                    .then(d => {
                        if (d.success) {
                            toastr.success('Updated, Refresh page.');
                            location.reload();
                        } else {
                            toastr.error(d.result);
                        }
                    });
            });
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