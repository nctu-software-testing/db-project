@extends('base')
@section('header')
    <section id="banner">
        <div class="container">
            <!--Carousel Wrapper-->
            <div id="carousel" class="carousel slide carousel-fade" data-ride="carousel">
                <!--Indicators-->
                <ol class="carousel-indicators">
                </ol>
                <!--/.Indicators-->
                <!--Slides-->
                <div class="carousel-inner" role="listbox">
                </div>
                <!--/.Slides-->
                <!--Controls-->
                <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
                <!--/.Controls-->
            </div>
            <!--/.Carousel Wrapper-->
        </div>
    </section>
@endsection
@section('content-full')
    <section id="features">
        <div class="container">
            <div class="row">
                <div class="col-4 feature">
                    <div class="icon-container">
                        <img src="{{asset('images/icons/shipping.png')}}"/>
                    </div>
                    <div class="text-container">
                        <span class="desc">
                            提供宅配與店取服務
                        </span>
                    </div>
                </div>
                <div class="col-4 feature">
                    <div class="icon-container">
                        <img src="{{asset('images/icons/payment.png')}}"/>
                    </div>
                    <div class="text-container">
                        <span class="desc">
                            簡易又安全的付款方式
                        </span>
                    </div>
                </div>
                <div class="col-4 feature">
                    <div class="icon-container">
                        <img src="{{asset('images/icons/protect.png')}}"/>
                    </div>
                    <div class="text-container">
                            <span class="desc">
                                買家保障&amp;購物無憂
                            </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="category">
        <div class="container">
            <h2>
                分類
                <a href="{{action('CategoryController@getCategory')}}" style="font-size:initial">
                    所有分類 &gt;
                </a>
            </h2>
            <div id="category-wrap">
                <div class="row align-content-start flex-wrap">
                    <?php $i = 0;$colNum = 6;?>
                    @foreach($category as $cat)
                        <a class="category-item col"
                           href="{{action('ProductController@getProducts')}}?search[category]={{$cat->id}}">
                            <div class="icon-wrapper">
                                @if($cat->hasCustomIcon())
                                    <i data-index="{{$cat->image_index}}"
                                       class="category-icon"></i>
                                @else
                                    <i class="material-icons">attach_money</i>
                                @endif
                            </div>
                            <p>{{$cat->product_type}}</p>
                        </a>
                        @if((++$i)%$colNum===0)
                            <div class="w-100"></div>
                        @endif
                    @endforeach
                    @while(($i++)%$colNum!==0)
                        <div class="category-item col empty"></div>
                    @endwhile
                </div>
            </div>
        </div>
    </section>
    <section id="hotProducts">
        <div class="container">
            <h2>推薦商品</h2>
            <div class="row">
                <?php $i = 0;$colNum = 3;?>
                @foreach($products as $p)
                    <div class="product-wrap col-4">
                        <!--Card-->
                        <a class="card" href="{{action('ProductController@getItem', $p->id)}}">
                            <!--Card image-->
                            <div class="view overlay hm-white-slight">
                                <img src="{{action('ProductController@getImage', [
                                'pid'=>$p->id,
                                'id'    => 0
                            ])}}" class="img-fluid" alt="photo">
                                <div class="mask">
                                    <div class="buy-now">
                                        立即購買
                                        <i class="material-icons">keyboard_arrow_right</i>
                                    </div>
                                </div>
                            </div>

                            <!--Card content-->
                            <div class="card-body">
                                <div class="product-info">
                                    <h5 class="product-title">{{$p->product_name}}</h5>
                                    <div class="product-price">
                                        <i class="ntd">NT$</i>
                                        {{$p->price}}
                                    </div>
                                </div>
                            </div>

                        </a>
                        <!--/.Card-->
                    </div>
                    @if((++$i) %$colNum === 0)
                        <p class="w-100"></p>
                    @endif
                @endforeach
                @while(($i++)%$colNum!==0)
                    <div class="product-wrap empty"></div>
                @endwhile
            </div>
        </div>
    </section>
@endsection
@section('eofScript')
    <script type="text/javascript" nonce="{{$nonce}}">
        window.Pages.Index.init();
    </script>
@endsection