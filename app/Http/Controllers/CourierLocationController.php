<?php

namespace App\Http\Controllers;

use App\Events\CourierLocationUpdated;
use App\Models\CourierLocation;
use App\Models\Order;
use Illuminate\Http\Request;

class CourierLocationController extends Controller
{
    // HP kurir kirim koordinat tiap 5 detik
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        CourierLocation::updateOrCreate(
            ['order_id' => $order->id],
            ['lat' => $data['lat'], 'lng' => $data['lng']]
        );

        broadcast(new CourierLocationUpdated($order, $data['lat'], $data['lng']));

        return response()->json(['ok' => true]);
    }

    // Halaman tracking untuk customer
    public function show(string $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
                      ->with('courierLocation', 'service')
                      ->firstOrFail();

        return view('tracking.show', compact('order'));
    }

    // Customer share lokasi jemput
    public function customerLocation(Request $request, Order $order)
    {
        $data = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $order->update([
            'customer_lat' => $data['lat'],
            'customer_lng' => $data['lng'],
        ]);

        return response()->json(['ok' => true]);
    }

    // Halaman dashboard kurir saat antar
    public function courierPage(Order $order)
    {
        $order->load('courierLocation', 'service');

        return view('courier.active', compact('order'));
    }
}