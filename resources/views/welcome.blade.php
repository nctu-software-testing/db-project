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
            <h2>分類</h2>
            <div id="category-wrap">
                <div class="row align-content-start flex-wrap">
                    <?php $i = 0;$colNum = 6;?>
                    @foreach($category as $cat)
                        <a class="category-item col"
                           href="{{action('ProductController@getProducts')}}?category={{$cat->id}}">
                            <i class="material-icons">attach_money</i>
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
                <?php $i=0;?>
                @foreach($products as $p)
                    <div class="col-3">
                        <!--Card-->
                        <div class="card">
                            <!--Card image-->
                            <div class="view overlay hm-white-slight">
                                <img src="{{action('ProductController@getImage', [
                                'pid'=>$p->id,
                                'id'    => 0
                            ])}}" class="img-fluid" alt="photo">
                                <a href="#">
                                    <div class="mask"></div>
                                </a>
                            </div>

                            <!--Card content-->
                            <div class="card-body">
                                <!--Title-->
                                <h4 class="card-title">{{$p->product_name}}</h4>
                                <!--Text-->
                                <p class="card-text"></p>
                                <!--<a href="#" class="btn btn-primary">Button</a>-->
                            </div>

                        </div>
                        <!--/.Card-->
                    </div>
                    @if((++$i) %4===0)
                        <p class="w-100">&nbsp;</p>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endsection
@section('eofScript')
    <script>
        window.Pages.Index.init();
    </script>
@endsection