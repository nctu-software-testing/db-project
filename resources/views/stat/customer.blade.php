@extends('management.base')
@section('extraScript')
    <script type="text/javascript" src="{{asset('js/Chart.min.js')}}"></script>
@endsection
@section('content')
    <div class="card card-body">
        <h4 class="card-title">統計資料</h4>
        <div class="card-text">
            <section>
                <h5>在每個分類的購買情況</h5>
                <div id="chart1">
                    <canvas></canvas>
                </div>
            </section>
            <hr/>

            <section>
                <h5>購買最多商品的前五名商家</h5>
                <div id="chart2">
                    <canvas></canvas>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('eofScript')
    <script type="text/javascript" nonce="{{$nonce}}">
        (function () {
            let chart1, chart2;
            let getContext = (chartId) => document.getElementById(chartId).querySelector('canvas').getContext('2d');
            ajax('POST', '{{action('StatController@postCustomStat')}}', {type: 1})
                .then(function (res) {
                    let data = res.result;
                    let barChartData = {
                        labels: data.map(x => x.product_type),
                        datasets: [
                            {
                                label: '購買數量',
                                borderWidth: 1,
                                backgroundColor: palette('sequential', data.length).map(c => '#' + c),
                                data: data.map(x => x.total_count)
                            }
                        ]

                    };
                    chart1 = new Chart(getContext('chart1'), {
                        type: 'bar',
                        data: barChartData,
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: '在每個分類的購買情況'
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            },
                        }
                    });

                });
            ajax('POST', '{{action('StatController@postCustomStat')}}', {type: 2})
                .then(function (res) {
                    let data = res.result;
                    let barChartData = {
                        labels: data.map(x => x.name),
                        datasets: [
                            {
                                label: '商品數量',
                                borderWidth: 1,
                                backgroundColor: palette('sequential', data.length).map(c => '#' + c),
                                data: data.map(x => x.total_count)
                            }
                        ]

                    };
                    console.log(barChartData);
                    chart2 = new Chart(getContext('chart2'), {
                        type: 'horizontalBar',
                        data: barChartData,
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                            },
                            scales: {
                                xAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            },
                            title: {
                                display: true,
                                text: '購買最多商品的前五名商家'
                            }
                        }
                    });

                });
        })();

    </script>
@endsection