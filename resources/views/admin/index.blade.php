@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
     <div class="main-content-wrap">
        <div class="flex justify-end mb-8 px-8 py-6 rounded-lg h-50">
            <style>
                /* Blended filter/download button styles */
                .admin-filter-bar { display:flex; gap:12px; align-items:center; }
                .admin-filter-select {
                    padding:10px 14px;
                    border:1px solid #d1d5db;
                    border-radius:999px;
                    background:#fff;
                    box-shadow:0 1px 2px rgba(16,24,40,0.03);
                    font-size:14px;
                }
                .admin-action-btn {
                    display:inline-flex; align-items:center; gap:8px;
                    padding:10px 16px; border-radius:999px; border:1px solid transparent;
                    text-decoration:none; cursor:pointer; font-weight:600; font-size:14px;
                }
                .admin-action-btn--primary { background:#2377FC; color:#fff; border-color:#2377FC; }
                .admin-action-btn--outline { background:#fff; color:#111827; border-color:#d1d5db; }
                .admin-action-btn:focus { outline:2px solid rgba(35,119,252,0.18); outline-offset:2px; }
                .admin-action-btn:hover { background:#fff; color:#586788; border-color:#d1d5db; }
            </style>

            <form method="GET" action="{{ route('admin.index') }}" class="admin-filter-bar">
                <select name="year" onchange="this.form.submit()" class="admin-filter-select">
                    @php
                        $currentYear = date('Y');
                        $years = range($currentYear, 2020);
                    @endphp
                    <option value="">Select Year</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>

                <select name="month" onchange="this.form.submit()" class="admin-filter-select">
                    <option value="">All Months</option>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endforeach
                </select>

                <a href="{{ route('admin.download', ['year' => request('year'), 'month' => request('month')]) }}" 
                   class="admin-action-btn admin-action-btn--primary"> 
                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path stroke="#586788" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v12m0 0 4-4m-4 4-4-4"/></svg>
                   Download
                </a>
            </form>
        </div>
         <div class="tf-section-2 mb-30">
             <div class="flex gap20 flex-wrap-mobile">
                 <div class="w-half">
                     <div class="wg-chart-default mb-20">
                         <div class="flex items-center justify-between">
                             <div class="flex items-center gap14">
                                 <div class="image ic-bg">
                                     <i class="icon-shopping-bag"></i>
                                 </div>
                                 <div>
                                     <div class="body-text mb-2">Total Orders</div>
                                     <h4>{{number_format($dashboardDatas[0]->Total)}}</h4>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="wg-chart-default mb-20">
                         <div class="flex items-center justify-between">
                             <div class="flex items-center gap14">
                                 <div class="image ic-bg">
                                     <i class="icon icon-won">원</i>
                                 </div>
                                 <div>
                                     <div class="body-text mb-2">Total Amount</div>
                                     <h4>{{number_format($dashboardDatas[0]->TotalAmount, 2)}}</h4>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="wg-chart-default mb-20">
                         <div class="flex items-center justify-between">
                             <div class="flex items-center gap14">
                                 <div class="image ic-bg">
                                     <i class="icon-shopping-bag"></i>
                                 </div>
                                 <div>
                                     <div class="body-text mb-2">Pending Orders</div>
                                     <h4>{{number_format($dashboardDatas[0]->TotalOrdered)}}</h4>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="wg-chart-default">
                         <div class="flex items-center justify-between">
                             <div class="flex items-center gap14">
                                 <div class="image ic-bg">
                                     <i class="icon icon-won">원</i>
                                 </div>
                                 <div>
                                     <div class="body-text mb-2">Pending Orders Amount</div>
                                     <h4>{{number_format($dashboardDatas[0]->TotalOrderedAmount, 2)}}</h4>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="w-half">
                     <div class="wg-chart-default mb-20">
                         <div class="flex items-center justify-between">
                             <div class="flex items-center gap14">
                                 <div class="image ic-bg">
                                     <i class="icon-shopping-bag"></i>
                                 </div>
                                 <div>
                                     <div class="body-text mb-2">Delivered Orders</div>
                                     <h4>{{number_format($dashboardDatas[0]->TotalDelivered)}}</h4>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="wg-chart-default mb-20">
                         <div class="flex items-center justify-between">
                             <div class="flex items-center gap14">
                                 <div class="image ic-bg">
                                     <i class="icon icon-won">원</i>
                                 </div>
                                 <div>
                                     <div class="body-text mb-2">Delivered Orders Amount</div>
                                     <h4>{{number_format($dashboardDatas[0]->TotalDeliveredAmount, 2)}}</h4>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="wg-chart-default mb-20">
                         <div class="flex items-center justify-between">
                             <div class="flex items-center gap14">
                                 <div class="image ic-bg">
                                     <i class="icon-shopping-bag"></i>
                                 </div>
                                 <div>
                                     <div class="body-text mb-2">Canceled Orders</div>
                                     <h4>{{number_format($dashboardDatas[0]->TotalCanceled)}}</h4>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="wg-chart-default">
                         <div class="flex items-center justify-between">
                             <div class="flex items-center gap14">
                                 <div class="image ic-bg">
                                     <i class="icon icon-won">원</i>
                                 </div>
                                 <div>
                                     <div class="body-text mb-2">Canceled Orders Amount</div>
                                     <h4>{{number_format($dashboardDatas[0]->TotalCanceledAmount, 2)}}</h4>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="wg-box">
                 <div class="flex items-center justify-between">
                     <h5>Monthly Revenue</h5>
                 </div>
                 <div class="flex flex-wrap gap40">
                     <div>
                         <div class="mb-2">
                             <div class="block-legend">
                                 <div class="dot t1"></div>
                                 <div class="text-tiny">Total</div>
                             </div>
                         </div>
                         <div class="flex items-center gap10">
                             <h4>{{number_format($TotalAmount)}}</h4>
                         </div>
                     </div>
                     <div>
                         <div class="mb-2">
                             <div class="block-legend">
                                 <div class="dot t2 bg-warning"></div>
                                 <div class="text-tiny">Pending</div>
                             </div>
                         </div>
                         <div class="flex items-center gap10">
                             <h4>{{number_format($TotalOrderedAmount)}}</h4>
                         </div>
                     </div>
                     <div>
                         <div class="mb-2">
                             <div class="block-legend">
                                 <div class="dot t2 bg-success"></div>
                                 <div class="text-tiny">Delivered</div>
                             </div>
                         </div>
                         <div class="flex items-center gap10">
                             <h4>{{number_format($TotalDeliveredAmount)}}</h4>
                         </div>
                     </div>
                     <div>
                         <div class="mb-2">
                             <div class="block-legend">
                                 <div class="dot t2 bg-danger"></div>
                                 <div class="text-tiny">Canceled</div>
                             </div>
                         </div>
                         <div class="flex items-center gap10">
                             <h4>{{number_format($TotalCanceledAmount)}}</h4>
                         </div>
                     </div>
                 </div>
                 <div id="line-chart-8"></div>
             </div>
         </div>
         <div class="tf-section mb-30">
             <div class="wg-box">
                 <div class="flex items-center justify-between">
                     <h5>Recent orders</h5>
                     <div class="dropdown default">
                         <a class="btn btn-secondary dropdown-toggle" href="{{route('admin.orders')}}">
                             <span class="view-all">View all</span>
                         </a>
                     </div>
                 </div>
                 <div class="wg-table table-all-user">
                     <div class="table-responsive">
                         <table class="table table-striped table-bordered">
                              <thead>
                                  <tr>
                                      <th style="width:70px">OrderNo</th>
                                      <th class="text-center">Name</th>
                                      <th class="text-center">Phone</th>
                                      <th class="text-center">Subtotal</th>
                                      <th class="text-center">Tax</th>
                                      <th class="text-center">Total</th>

                                      <th class="text-center">Status</th>
                                      <th class="text-center">Order Date</th>
                                      <th class="text-center">Total Items</th>
                                      <th class="text-center">Delivered On</th>
                                      <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach ($orders as $order)
                                  <tr>
                                      <td class="text-center">{{$order->id}}</td>
                                      <td class="text-center">{{$order->name}}</td>
                                      <td class="text-center">{{$order->phone}}</td>
                                      <td class="text-center">{{$order->subtotal}}</td>
                                      <td class="text-center">{{$order->tax}}</td>
                                      <td class="text-center">{{$order->total}}</td>
                                      <td class="text-center">
                                          @if($order->status == 'delivered')
                                              <span class="badge bg-success">Delivered</span>
                                          @elseif($order->status == 'canceled')
                                              <span class="badge bg-danger">Canceled</span>
                                          @else
                                              <span class="badge bg-warning">Ordered</span>
                                          @endif
                                      </td>
                                      <td class="text-center">{{$order->created_at}}</td>
                                      <td class="text-center">{{$order->orderItems->count()}}</td>
                                      <td class="text-center">{{$order->delivered_date}}</td>
                                      <td class="text-center">
                                          <a href="{{route('admin.order.details',['order_id'=>$order->id])}}">
                                              <div class="list-icon-function view-icon">
                                                  <div class="item eye">
                                                      <i class="icon-eye"></i>
                                                  </div>
                                              </div>
                                          </a>
                                      </td>
                                  </tr>
                                  @endforeach
                              </tbody>
                          </table>
                     </div>
                 </div>
             </div>
         </div>
     </div>
</div>
@endsection
@push('scripts')
    <script>
        (function ($) {

            var tfLineChart = (function () {

                var chartBar = function () {

                    var options = {
                        series: [{
                            name: 'Total',
                            data: [{{$AmountM}}]
                        }, {
                            name: 'Pending',
                            data: [{{$orderedAmountM}}]
                        },
                        {
                            name: 'Delivered',
                            data: [{{$DeliveredAmountM}}]
                        }, {
                            name: 'Canceled',
                            data: [{{$CanceledAmountM}}]
                        }],
                        chart: {
                            type: 'bar',
                            height: 325,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '10px',
                                endingShape: 'rounded'
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        legend: {
                            show: false,
                        },
                        colors: ['#2377FC', '#FFA500', '#078407', '#FF0000'],
                        stroke: {
                            show: false,
                        },
                        xaxis: {
                            labels: {
                                style: {
                                    colors: '#212529',
                                },
                            },
                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        },
                        yaxis: {
                            show: false,
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return "원 " + val + ""
                                }
                            }
                        }
                    };

                    chart = new ApexCharts(
                        document.querySelector("#line-chart-8"),
                        options
                    );
                    if ($("#line-chart-8").length > 0) {
                        chart.render();
                    }
                };

                /* Function ============ */
                return {
                    init: function () { },

                    load: function () {
                        chartBar();
                    },
                    resize: function () { },
                };
            })();

            jQuery(document).ready(function () { });

            jQuery(window).on("load", function () {
                tfLineChart.load();
            });

            jQuery(window).on("resize", function () { });
        })(jQuery);
    </script>
@endpush