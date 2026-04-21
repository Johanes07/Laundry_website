<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\StoreAccount;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create()
    {
        return view('orders.create', [
            'services'      => Service::all(),
            'storeAccounts' => StoreAccount::active()->get(),
        ]);
    }

    public function store(Request $request, OrderService $orderService)
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_email'   => 'nullable|email|max:255',
            'customer_address' => 'required|string',
            'service_id'       => 'required|exists:services,id',
            'quantity'         => 'required|numeric|min:0.5',
            'notes'            => 'nullable|string',
            'payment_method'   => 'required|in:transfer,e_wallet,cod',
            'store_account_id' => 'required_unless:payment_method,cod|nullable|exists:store_accounts,id',
            'delivery_type'    => 'required|in:pickup,delivery',
            'customer_lat'     => 'nullable|numeric|between:-90,90',
            'customer_lng'     => 'nullable|numeric|between:-180,180',
        ], [
            'store_account_id.required_unless' => 'Pilih rekening tujuan pembayaran.',
            'delivery_type.required'           => 'Pilih metode pengambilan.',
        ]);

        // Hapus koordinat kalau bukan delivery
        if ($validated['delivery_type'] !== 'delivery') {
            $validated['customer_lat'] = null;
            $validated['customer_lng'] = null;
        }

        $order = $orderService->createOrder($validated);

        return redirect()->route('order.success', $order);
    }

    public function success(Order $order)
    {
        return view('orders.success', compact('order'));
    }

    public function check(Request $request)
    {
        $order = null;

        if ($request->filled('code')) {
            $request->validate(['code' => 'required|string']);

            $order = Order::with(['service', 'statusHistories', 'storeAccount'])
                ->where('order_code', strtoupper(trim($request->code)))
                ->first();

            if (! $order) {
                return back()->withErrors(['code' => 'Kode order tidak ditemukan.'])->withInput();
            }
        }

        return view('orders.check-status', compact('order'));
    }
}