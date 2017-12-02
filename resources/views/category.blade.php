@extends('base')
@section('content')
    <section id="category">
            <div id="category-wrap">
                <div class="row align-content-start flex-wrap">
                    <?php $i = 0;$colNum = 6;?>
                    @foreach($category as $cat)
                        <a class="category-item col"
                           href="{{action('ProductController@getProducts')}}?category={{$cat->id}}">
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
    </section>
@endsection