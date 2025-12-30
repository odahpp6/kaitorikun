<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    public function mail()
    {
        $customers = Customer::query()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('customer.mail', compact('customers'));
    }

    public function send_mail(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'customers' => ['array'],
            'customers.*' => ['integer'],
        ]);

        $customerIds = $validated['customers'] ?? [];
        if (empty($customerIds)) {
            return back()->withErrors(['customers' => '送信先を選択してください。'])->withInput();
        }

        $emails = Customer::query()
            ->whereIn('id', $customerIds)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->pluck('email')
            ->filter()
            ->unique()
            ->values();

        if ($emails->isEmpty()) {
            return back()->withErrors(['customers' => '送信可能なメールアドレスが見つかりませんでした。'])->withInput();
        }

        foreach ($emails as $email) {
            Mail::raw($validated['body'], function ($message) use ($email, $validated) {
                $message->to($email)
                    ->subject($validated['subject']);
            });
        }

        return back()->with('status', 'メールを送信しました。');
    }
}
