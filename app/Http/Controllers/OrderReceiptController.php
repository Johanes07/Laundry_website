<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Response;

class OrderReceiptController extends Controller
{
    public function show(Order $order): Response
    {
        $order->load(['service', 'storeAccount']);

        $storeName    = strtoupper(config('app.store_name', 'LAUNDRY BERSIH'));
        $storeAddress = config('app.store_address', 'Jl. Merdeka No. 12, Jakarta');
        $storePhone   = config('app.store_phone', '0812-3456-7890');

        $total    = 'Rp ' . number_format($order->total_price, 0, ',', '.');
        $pickedUp = optional($order->picked_up_at)->format('d/m/Y H:i') ?? '-';
        $estDone  = optional($order->estimated_done)->format('d/m/Y') ?? '-';
        $qrUrl    = urlencode(route('orders.receipt', $order));

        $notes = $order->notes
            ? "<div class='row'><span class='label'>Catatan</span><span class='val'>{$order->notes}</span></div>"
            : '';

        $rekening = '';
        if ($order->storeAccount && in_array($order->payment_method, ['transfer', 'e_wallet'])) {
            $rekening = "
                <div class='row'><span class='label'>Rekening</span><span class='val'>{$order->storeAccount->bank_name}</span></div>
                <div class='row'><span class='label'>No. Rek</span><span class='val'>{$order->storeAccount->account_number}</span></div>
            ";
        }

        $html = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Struk #{$order->order_code}</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&display=swap');

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    background: #f5f5f5;
    display: flex;
    justify-content: center;
    padding: 30px;
    font-family: 'IBM Plex Mono', monospace;
  }

  .receipt {
    width: 220px;
    background: #fff;
    font-size: 10px;
    line-height: 1.6;
    color: #111;
    padding: 14px 12px;
    border: 1px solid #ddd;
  }

  .store-name { font-size: 13px; font-weight: 600; text-align: center; letter-spacing: 0.5px; }
  .store-sub  { font-size: 9px; text-align: center; color: #555; }

  hr { border: none; border-top: 1px dashed #bbb; margin: 7px 0; }

  .order-code { font-size: 13px; font-weight: 600; text-align: center; letter-spacing: 2px; margin: 4px 0; }

  .status-wrap { text-align: center; margin: 3px 0; }
  .status-badge {
    display: inline-block; font-size: 8px; font-weight: 600;
    padding: 2px 8px; border: 1px solid #111;
    letter-spacing: 1px; text-transform: uppercase;
  }

  .section-title { font-size: 8px; color: #888; text-transform: uppercase; letter-spacing: 1px; margin: 5px 0 2px; }

  .row   { display: flex; justify-content: space-between; gap: 4px; }
  .label { color: #555; }
  .val   { text-align: right; font-weight: 600; }

  .total-row {
    display: flex; justify-content: space-between;
    font-size: 12px; font-weight: 600;
    border-top: 1px solid #111;
    padding-top: 5px; margin-top: 4px;
  }

  .qr-wrap  { text-align: center; margin: 8px 0 2px; }
  .qr-label { font-size: 8px; color: #888; text-align: center; }
  .footer   { font-size: 8px; text-align: center; color: #888; margin-top: 6px; }

  @page { size: A4; margin: 10mm; }

  @media print {
    body {
      background: #fff;
      padding: 0;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }
    .receipt {
      border: none;
      width: 220px;
    }
  }
</style>
</head>
<body>

<div class="receipt">
  <div class="store-name">{$storeName}</div>
  <div class="store-sub">{$storeAddress}</div>
  <div class="store-sub">WA: {$storePhone}</div>

  <hr>

  <div class="order-code">{$order->order_code}</div>
  <div class="status-wrap"><span class="status-badge">{$order->status_label}</span></div>

  <hr>

  <div class="section-title">Pelanggan</div>
  <div class="row"><span class="label">Nama</span><span class="val">{$order->customer_name}</span></div>
  <div class="row"><span class="label">WA</span><span class="val">{$order->customer_phone}</span></div>

  <hr>

  <div class="section-title">Detail Order</div>
  <div class="row"><span class="label">Layanan</span><span class="val">{$order->service->name}</span></div>
  <div class="row"><span class="label">Berat</span><span class="val">{$order->quantity} kg</span></div>
  <div class="row"><span class="label">Masuk</span><span class="val">{$pickedUp}</span></div>
  <div class="row"><span class="label">Est. Selesai</span><span class="val">{$estDone}</span></div>
  {$notes}

  <hr>

  <div class="section-title">Pembayaran</div>
  <div class="row"><span class="label">Metode</span><span class="val">{$order->payment_method_label}</span></div>
  <div class="row"><span class="label">Status</span><span class="val">{$order->payment_status_label}</span></div>
  {$rekening}
  <div class="total-row"><span>TOTAL</span><span>{$total}</span></div>

  <hr>

  <div class="qr-wrap">
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={$qrUrl}" width="80" height="80" alt="QR">
  </div>
  <div class="qr-label">Scan untuk cek status pesanan</div>

  <hr>

  <div class="footer">Terima kasih sudah mempercayakan<br>laundry Anda kepada kami!</div>
</div>

<script>
  window.onload = function () {
    window.print();
    window.onafterprint = function () { window.close(); };
  };
</script>

</body>
</html>
HTML;

        return response($html, 200)->header('Content-Type', 'text/html');
    }
}