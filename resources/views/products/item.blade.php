@extends('base')
@section('extraScript')
    <script type="text/javascript" src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
@endsection
@section('content')
    <div class="container product-header">
        <div class="row">
            <div class="col-6">
                <!--Carousel Wrapper-->
                <div id="carousel-example-1z" class="carousel slide carousel-fade" data-ride="carousel">
                    <!--Indicators-->
                    <ol class="carousel-indicators">
                        @for ($i = 0; $i < $count; $i++)
                            @if ($i == '0')
                                <li data-target="#carousel-example-1z" data-slide-to={{$i}} class="active"></li>
                            @else
                                <li data-target="#carousel-example-1z" data-slide-to={{$i}}></li>
                            @endif
                        @endfor
                    </ol>
                    <!--/.Indicators-->
                    <!--Slides-->
                    <div class="carousel-inner" role="listbox">
                        @for ($i = 0; $i < $count; $i++)
                            @if ($i == '0')
                                <div class="carousel-item active">
                                    <img class="preview{{$i}} product-image" alt="placeholder"
                                         src="{{action('ProductController@getImage', ['pid'=>$p->id, 'id'=>$i])}}">
                                </div>
                            @else
                                <div class="carousel-item ">
                                    <img class="preview{{$i}} product-image" alt="placeholder"
                                         src="{{action('ProductController@getImage', ['pid'=>$p->id, 'id'=>$i])}}">
                                </div>
                            @endif
                        @endfor

                    </div>
                    <!--/.Slides-->
                    <!--Controls-->
                    <a class="carousel-control-prev" href="#carousel-example-1z" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carousel-example-1z" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                    <!--/.Controls-->
                </div>
                <!--/.Carousel Wrapper-->
            </div>
            <div class="col-6">
                <div class="product-information">
                    <div class="info-header">
                        <p class="text-right">供應商：{{$p->provider->name}}</p>
                        <h1>{{$p->product_name}}</h1>
                        <h6 class="suggest">建議售價 NT$
                            <del>{{$p->price*1.2}}</del>
                        </h6>
                        <h2 class="product-price">NT$ {{$p->price}}</h2>
                    </div>
                    <div class="info-about">
                        <a class="chip"
                           href="{{action('ProductController@getProducts')}}?search[category]={{$p->category_id}}">
                            {{$p->product_type}}
                        </a>
                        <h6>販售截止日 {{$p->end_date}}</h6>
                    </div>
                    <div class="info-price">
                        <h6>數量&nbsp;&nbsp;&nbsp;
                            <input id="amount" type="number" class="input-alternate" min="0" max="10" value="1">
                            &nbsp;&nbsp;
                            <span class="remaining"> 還剩 {{$p->getRemaining()}} 件</span></h6>
                        <button type="button" class=" buy-btn btn btn-default" onclick="Buy('{{$p->id}}')">加入購物車
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row introduce">
            <h2 class="col-12 introduce-title">商品詳情</h2>

            <div id="description" hidden>{{$p->product_information}}</div>
        </div>
    </div>

@endsection
@section('eofScript')
    <script>
        $('#amount').change(function () {
            var amount = $('#amount').val();
            if (isNaN(amount) || amount <= 0 || amount == "") {
                $('#amount').val("1");
                alert("請輸入正確數字");
            }
        });

        function getBBCodeFromHtml(code) {
            let fragment = CKEDITOR.htmlParser.fragment.fromBBCode(code),
                writer = new CKEDITOR.htmlParser.basicWriter();
            fragment.writeHtml(writer);
            return writer.getHtml();
        }

        $(() => {
            let desc = $("#description");
            let html = getBBCodeFromHtml(desc.text());
            desc.empty().html(html);
            desc.removeAttr('hidden');
        });

        function Buy(id) {
            /*
                    var amount = prompt("請輸入購買數量!", "1");
                    if (isNaN(amount) || amount < 0 || !amount) {
                        alert("請輸入正確數字");
                        return;
                    }
            */
            var number_element = document.getElementById('amount');
            var number = number_element.value;
            encryptAjax("POST", "{{action('ShoppingCartController@buyProduct')}}",
                {
                    id: id,
                    amount: number,
                }).then(function (data) {
                if (!data.success) {
                    toastr.error(data.result);
                } else {
                    toastr.success('已加入購物車');
                    if (window.updateShoppingCartCount) updateShoppingCartCount();
                }
            });
        }
    </script>
@endsection