@extends('base')
@section('content')
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
            <a class="carousel-control-prev" href="javascript:void(0)" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="javascript:void(0)" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            <!--/.Controls-->
        </div>
        <!--/.Carousel Wrapper-->

    </div>
</section>
@endsection
@section('eofScript')
<script>
window.Pages.Index.init();
</script>
@endsection