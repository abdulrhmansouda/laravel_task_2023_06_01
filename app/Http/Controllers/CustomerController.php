<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerOrderReportResource;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Carbon\Carbon;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CustomerController extends Controller
{
    public function store(CustomerRequest $request)
    {
        $customer = Customer::create($request->validated());
        return response()->success([
            'customer'  => CustomerResource::make($customer),
        ]);
    }

    public function getCustomerOrderReports()
    {
        $customer_reports = QueryBuilder::for(
            Customer::query()
                ->selectRaw('customers.id AS customer_id, customers.name AS customer_name, cities.id AS city_id, cities.name AS city_name, SUM(order_details.purchase_price * order_details.amount) AS total_price_of_orders, COUNT(DISTINCT orders.id) AS number_of_orders')
                ->join('orders', function ($on) {
                    $on->whereColumn('customers.id', 'orders.customer_id');
                })
                ->join('order_details', function ($on) {
                    $on->whereColumn('orders.id', 'order_details.order_id');
                })
                ->join('cities', function ($on) {
                    $on->whereColumn('orders.city_id', 'cities.id');
                })->groupBy(['customers.id', 'customers.name', 'cities.id', 'cities.name'])
        )->allowedFilters([
            AllowedFilter::callback('month', function ($query, $month) {
                $query->whereMonth('orders.created_at', $month);
            }),
            AllowedFilter::callback('year', function ($query, $year) {
                $query->whereYear('orders.created_at', $year);
            })->default(Carbon::now()->year),
        ])
            ->orderByDESC('orders.created_at');

        $paginated_customer_reports = (request()->input('per_page')) ? $customer_reports->paginate(request()->input('per_page')) : $customer_reports->get();
        return response()->success([
            'meta'       => [
                'total'     => $paginated_customer_reports->total(),
                'lastPage'  => $paginated_customer_reports->lastPage(),
            ],
            'customer_reports'  => CustomerOrderReportResource::collection($paginated_customer_reports),
        ]);
    }
}
