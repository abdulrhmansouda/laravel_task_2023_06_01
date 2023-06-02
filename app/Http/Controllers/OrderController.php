<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderReportResource;
use App\Models\Order;
use Carbon\Carbon;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{
    public function getOrderReports()
    {
        $order_reports = QueryBuilder::for(
            Order::query()
                ->selectRaw('cities.id AS city_id, cities.name AS city_name, SUM(order_details.purchase_price * order_details.amount) AS total_price_of_orders, COUNT(DISTINCT orders.id) AS number_of_orders,  COUNT(order_details.amount) AS number_of_items')
                ->join('cities', function ($on) {
                    $on->whereColumn('orders.city_id', 'cities.id');
                })->groupBy(['cities.id', 'cities.name'])
                ->join('order_details', function ($on) {
                    $on->whereColumn('orders.id', 'order_details.order_id');
                })
        )->allowedFilters([
            AllowedFilter::callback('month', function ($query, $month) {
                $query->whereMonth('orders.created_at', $month);
            }),
            AllowedFilter::callback('year', function ($query, $year) {
                $query->whereYear('orders.created_at', $year);
            })->default(Carbon::now()->year),
        ])->orderByDESC('orders.created_at');

        $paginated_order_reports = (request()->input('per_page')) ? $order_reports->paginate(request()->input('per_page')) : $order_reports->get();
        return response()->success([
            'meta'       => [
                'total'     => $paginated_order_reports->total(),
                'lastPage'  => $paginated_order_reports->lastPage(),
            ],
            'order_reports'  => OrderReportResource::collection($paginated_order_reports),
        ]);
    }
}
