@extends('management.base')
@section('extraScript')
    <script type="text/javascript" src="{{asset('js/Chart.min.js')}}"></script>
@endsection
@section('content')
    <div class="card card-body">
        <h4 class="card-title">統計資料</h4>
        <div class="card-text">
            <section>
                <h5>在每個分類的販售情況</h5>
                <div id="chart1">
                    <canvas></canvas>
                </div>
            </section>
            <hr/>

            <section>
                <h5>配送區域</h5>
                <form id="stat2" class="form-inline">
                    <div class="form-group" hidden>
                        <div data-role="zipcode" data-value="110" data-size="4"></div>
                        <div data-role="district" data-value="信義區"></div>
                    </div>
                    <div class="form-group">
                        <div data-role="county" data-value="臺北市" data-id="county"></div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-amber btn-sm">查詢</button>
                    </div>
                </form>
                <div id="chart2">
                    <canvas></canvas>
                </div>
            </section>
            <hr/>

            <section>
                <h5>前10項最多人購買的商品</h5>
                <div id="chart3">
                    <table class="table">
                        <thead class="blue-grey lighten-4">
                        <tr>
                            <th width="110">商品編號</th>
                            <th>商品名稱</th>
                            <th>購買人數</th>
                        </tr>
                        </thead>
                        <tbody id="chart3Body"></tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('eofScript')
    <script>
        (function () {
            const COUNTY = ["基隆市", "臺北市", "新北市", "宜蘭縣", "新竹市", "新竹縣", "桃園市", "苗栗縣", "臺中市", "彰化縣", "南投縣", "嘉義市", "嘉義縣", "雲林縣", "臺南市", "高雄市", "屏東縣", "臺東縣", "花蓮縣", "金門縣", "連江縣", "澎湖縣"];
            let stat2 = $('#stat2');
            let chart1, chart2, chart3;
            let getContext = (chartId) => document.getElementById(chartId).querySelector('canvas').getContext('2d');
            Object.freeze(COUNTY);
            ajax('POST', '{{action('StatController@postBusinessStat')}}', {type: 1})
                .then(function (res) {
                    let data = res.result;
                    let barChartData = {
                        labels: data.map(x => x.product_type),
                        datasets: [
                            {
                                label: '販售數量',
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
                                text: '在每個分類的販售情況'
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

            stat2.twzipcode();
            stat2.on('submit', function (e) {
                e.preventDefault();
                let county = $("#county").val();
                let createChart = function (data) {
                    let barChartData = {
                        labels: data.map(x => x.district),
                        datasets: [
                            {
                                label: '販售數量',
                                borderWidth: 1,
                                backgroundColor: palette('sequential', data.length).map(c => '#' + c),
                                data: data.map(x => x.total_count)
                            }
                        ]

                    };
                    if (chart2) {
                        chart2.data = barChartData;
                        chart2.update();
                    } else {
                        chart2 = new Chart(getContext('chart2'), {
                            type: 'bar',
                            data: barChartData,
                            options: {
                                responsive: true,
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: '在每個區域的販售情況'
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
                    }
                };
                if (county) {
                    ajax('POST', '{{action('StatController@postBusinessStat')}}', {type: 2, county: county})
                        .then(function (res) {
                            let data = res.result;
                            createChart(data);
                        });
                } else {
                    Promise.all(
                        COUNTY.map(c => ajax('POST', '{{action('StatController@postBusinessStat')}}', {
                            type: 2,
                            county: c
                        }))
                    )
                        .then((dArr) => {
                            let result = COUNTY.map((c, i) => {
                                return {
                                    district: c,
                                    total_count: dArr[i].result.reduce((sum, cur) => sum + cur.total_count, 0)
                                };
                            });

                            return result;
                        })
                        .then((d) => d.filter(x => x.total_count > 0))
                        .then(x => createChart(x));
                }
            });

            ajax('POST', '{{action('StatController@postBusinessStat')}}', {type: 3})
                .then(function (res) {
                    let data = res.result;
                    chart3 = chart3 || $("#chart3Body");
                    chart3.empty();
                    data.forEach(d => {
                        let row = $(`<tr><td></td><td></td><td></td></tr>`);
                        let cells = row.children();
                        cells.eq(0).text(d.id);
                        cells.eq(1).text(d.product_name);
                        cells.eq(2).text(d.diff_buy);

                        chart3.append(row);
                    })
                });
        })();

    </script>
@endsection