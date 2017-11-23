@section('extraScript')
<script src="https://code.essoduke.org/js/twzipcode/jquery.twzipcode-1.7.14.min.js"></script>
@endsection
@section('content')
    <table border="1">
        <tr>
              <td>product_id</td>
            　<td>product_name</td>
            　<td>price</td>
              <td>amount</td>
        </tr>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                　<td>{{$data[$i]->product_id}}</td>
                  <td><a href="{{$data[$i]->product->GetLink()}}"> {{$data[$i]->product->product_name}} </a></td>
                  <td>{{$data[$i]->product->price}}</td>
                　<td>{{$data[$i]->amount}}</td>
            </tr>
        @endfor
    </table>
    總價:{{$final}}
    <Form action="checkout" method="post">
        訂單送達地址:<select name="location"required>
            @for ($i = 0; $i < count($location); $i++)
                　<option value={{$location[$i]->id}}>{{$location[$i]->address}}
                  </option>
            @endfor
        </select>
        <br>
        優惠:不知怎弄
        <br>
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="submit" value="送出">
    </Form>
@endsection
@section('eofScript')
@endsection
@include('base')