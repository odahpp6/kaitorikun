<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deal;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function customer_search(Request $request)
    {
        $query = Customer::query()
            ->leftJoin('deals', 'deals.customer_id', '=', 'customers.id')
            ->selectRaw('
                customers.name,
                customers.phone_number,
                MIN(customers.prefecture) as prefecture,
                MIN(customers.city) as city,
                MIN(customers.address_detail) as address_detail,
                MIN(customers.address_building) as address_building,
                COUNT(deals.id) as deals_count,
                MAX(deals.created_at) as last_visit_at,
                GROUP_CONCAT(deals.id ORDER BY deals.created_at DESC) as deal_ids,
                GROUP_CONCAT(deals.slip_number ORDER BY deals.created_at DESC) as slip_numbers
            ')
            ->groupBy('customers.name', 'customers.phone_number');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('phone_number')) {
            $query->where('phone_number', 'like', '%' . $request->input('phone_number') . '%');
        }

        $customers = $query->orderByDesc('last_visit_at')->paginate(15);

        return view('customer.search', compact('customers'));
    }
}
