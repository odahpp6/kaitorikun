<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function customer_search(Request $request)
    {
        $storeId = Auth::id();
        $query = Customer::query()
            ->where('customers.store_id', $storeId)
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
            $query->where('customers.name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('phone_number')) {
            $query->where('customers.phone_number', 'like', '%' . $request->input('phone_number') . '%');
        }

        $customers = $query->orderByDesc('last_visit_at')->paginate(15);

        return view('customer.search', compact('customers'));
    }

    public function customer_search_json(Request $request)
    {
        $storeId = Auth::id();
        $query = Customer::query()
            ->where('store_id', $storeId);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('phone_number')) {
            $query->where('phone_number', 'like', '%' . $request->input('phone_number') . '%');
        }

        if (!$request->filled('name') && !$request->filled('phone_number')) {
            return response()->json([]);
        }

        $customers = $query
            ->latest('updated_at')
            ->limit(20)
            ->get([
                'id',
                'name',
                'furigana',
                'birth_y',
                'birth_m',
                'birth_d',
                'gender',
                'occupation',
                'postal_code',
                'prefecture',
                'city',
                'address_detail',
                'address_building',
                'phone_number',
                'email',
                'proof_type',
                'proof_num',
                'updated_at',
            ]);

        return response()->json($customers);
    }

    public function mail()
    {
        $storeId = Auth::id();
        $customers = Customer::query()
            ->where('store_id', $storeId)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('customer.mail', compact('customers'));
    }

    public function send_mail(Request $request)
    {
        $storeId = Auth::id();
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
            ->where('store_id', $storeId)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->pluck('email')
            ->filter()
            ->unique()
            ->values();

        if ($emails->isEmpty()) {
            return back()->withErrors(['customers' => '送信可能なメールアドレスが見つかりませんでした。'])->withInput();
        }

        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        foreach ($emails as $email) {
            Mail::raw($validated['body'], function ($message) use ($email, $validated, $fromAddress, $fromName) {
                if ($fromAddress) {
                    $message->from($fromAddress, $fromName);
                }

                $message->to($email)
                    ->subject($validated['subject']);
            });
        }

        return back()->with('status', 'メールを送信しました。');
    }
}
