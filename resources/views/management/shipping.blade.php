@extends('management.base')
@section('content')

    <div class="card card-body">
        <h4 class="card-title">管理運費</h4>
        <div class="card-text">
            <!--Table-->
            <table class="table table-bordered">

                <!--Table head-->
                <thead class="blue-grey lighten-4">
                <tr>
                    <th>總價下限</th>
                    <th>總價上限</th>
                    <th>運費</th>
                </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody>
                @for ($i = 0; $i < count($data); $i++)
                    <tr>
                        <td>{{$data[$i]->lower_bound}}</td>
                        <td>{{$data[$i]->upper_bound}}</td>
                        <td>{{$data[$i]->price}}</td>
                    </tr>
                @endfor
                </tbody>

                <!--Table body-->
            </table>
            <!--Table-->

        </div>
    </div>
@endsection
@section('eofScript')

@endsection