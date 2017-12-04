@extends('management.base')
@section('content')

    <div class="row">
        <div class="col">
            <!--Panel-->
            <div class="card card-body">
                <div class="row">
                    <div class="col">
                        <h4 class="card-title">管理分類</h4>
                    </div>
                    <div class="col text-right">
                        <button class="btn btn-sm btn-amber" onclick="AddCategory()">新增分類</button>
                    </div>
                </div>
                <div class="card-text">
                    <!--Table-->
                    <table class="table table-bordered table-striped">
                        <!--Table head-->
                        <thead class="blue-grey lighten-4">
                        <tr>
                            <th width="10%">#</th>
                            <th>分類名稱</th>
                            <th width="25%">
                                功能
                            </th>
                        </tr>
                        </thead>
                        <!--Table head-->

                        <!--Table body-->
                        <tbody>
                        @foreach($category as $c)
                            <tr>
                                <td>{{$c->id}}</td>
                                <td>{{$c->product_type}}</td>
                                <td>
                                    <button class="btn btn-sm btn-amber"
                                            onclick="EditCategory({{$c->id}}, {{json_encode($c->product_type)}})">修改
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                            onclick="DeleteCategory({{$c->id}})">刪除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                        <!--Table body-->
                    </table>
                    <!--Table-->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('eofScript')
    <script>
        function AddCategory() {
            ShowCategory(-1);
        }

        function EditCategory(id, title) {
            ShowCategory(id, title);
        }

        function DeleteCategory(id) {
            if (confirm('你要刪除嗎?')) {
                ajax('DELETE', '{{action('CategoryController@deleteCategory')}}', {id: id})
                    .then(d => {
                        if (d.success) {
                            toastr.success(d.result);
                            location.reload();
                        } else {
                            toastr.error(d.result);
                        }
                    });
            }
        }

        function ShowCategory(id, title = '') {
            let showTitle = id ? '修改' : '新增';
            let alert = bAlert(`${showTitle}分類`, `
            <form class="form">
                <div class="md-form form-group">
                    <input type="text" id="categoryName" class="form-control validate" required
                        placeholder="名稱"
                    >
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-sm btn-amber">${showTitle}</button>
                    <input type="hidden" id="id" value="${id}"/>
                </div>
            </form>
            `);
            alert.find('.modal-footer').remove();
            let categoryName = alert.find('#categoryName');
            categoryName.val(title);
            alert.find('form').on('submit', function (e) {
                e.preventDefault();
                ajax('POST', '{{action('CategoryController@postManageCategory')}}', {
                    id: id,
                    product_type: categoryName.val()
                })
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