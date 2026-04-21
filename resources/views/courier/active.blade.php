<x-layout>
    <div class="max-w-lg mx-auto px-4 py-6">

        {{-- Header --}}
        <div class="bg-slate-900 text-white rounded-3xl p-6 mb-4">
            <p class="text-slate-400 text-xs uppercase tracking-widest mb-1">Mode Kurir Aktif</p>
            <p class="font-black text-2xl">{{ $order->order_code }}</p>
            <p class="text-slate-300 text-sm mt-1">{{ $order->customer_name }} · {{ $order->customer_address }}</p>
        </div>

        {{-- Status GPS --}}
        <div id="gps-status" class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 mb-4 text-center">
            <p class="text-yellow-700 font-bold text-sm">⏳ GPS belum aktif</p>
        </div>

        {{-- Peta kurir (lihat tujuan customer) --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 mb-4">
            <div class="bg-slate-900 px-5 py-3">
                <span class="text-white font-bold text-sm">🗺️ Peta Pengantaran</span>
            </div>
            <div id="courier-map" style="height: 350px;"></div>
        </div>

        @if ($order->customer_lat && $order->customer_lng)
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-4 flex items-center gap-3">
            <span class="text-2xl">🏠</span>
            <div>
                <p class="font-bold text-emerald-800 text-sm">Lokasi customer tersimpan</p>
                <p class="text-emerald-600 text-xs">{{ $order->customer_lat }}, {{ $order->customer_lng }}</p>
            </div>
        </div>
        @endif

        {{-- Tombol mulai/stop --}}
        <button id="btn-start-tracking"
            class="w-full bg-blue-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-200 text-lg mb-3 hover:bg-blue-700 transition-all active:scale-95">
            🛵 Mulai Kirim Lokasi
        </button>
        <button id="btn-stop-tracking" class="w-full bg-red-100 text-red-600 font-bold py-4 rounded-2xl hidden hover:bg-red-200 transition-all">
            ⏹ Stop Tracking
        </button>

        <p class="text-center text-gray-400 text-xs mt-3">Lokasi dikirim otomatis setiap 5 detik saat aktif</p>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    // ✅ Tambah DOMContentLoaded — ini yang bikin peta kosong sebelumnya
    document.addEventListener('DOMContentLoaded', function () {
        const orderId   = @json($order->id);
        const custLat   = @json($order->customer_lat);
        const custLng   = @json($order->customer_lng);
        const csrfToken = document.querySelector('meta[name=csrf-token]').content;

        const defaultLat = custLat ?? -6.2;
        const defaultLng = custLng ?? 106.8;
        const map = L.map('courier-map').setView([defaultLat, defaultLng], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

        // ✅ Invalidate size setelah map diinit — mencegah tile tidak render
        setTimeout(() => map.invalidateSize(), 100);

        const courierIcon = L.divIcon({
            html: `<div style="background:#2563eb;width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px;box-shadow:0 3px 10px rgba(0,0,0,.3)">🛵</div>`,
            className: '', iconSize: [44,44], iconAnchor: [22,22],
        });
        const destIcon = L.divIcon({
            html: `<div style="background:#16a34a;width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px;box-shadow:0 3px 10px rgba(0,0,0,.3)">🏠</div>`,
            className: '', iconSize: [44,44], iconAnchor: [22,44],
        });

        let courierMarker = null;
        let intervalId = null;

        if (custLat && custLng) {
            L.marker([custLat, custLng], { icon: destIcon })
                .addTo(map)
                .bindPopup('<b>🏠 Tujuan: ' + @json($order->customer_name) + '</b><br>' + @json($order->customer_address))
                .openPopup();
        }

        function showToast(msg, type = 'success') {
            const colors = {
                success: 'background:#16a34a',
                error:   'background:#dc2626',
                info:    'background:#2563eb',
            };
            const t = document.createElement('div');
            t.style.cssText = `position:fixed;bottom:24px;left:50%;transform:translateX(-50%);${colors[type]};color:#fff;padding:12px 24px;border-radius:16px;font-weight:700;font-size:14px;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,.2);transition:opacity .3s`;
            t.textContent = msg;
            document.body.appendChild(t);
            setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3000);
        }

            function sendLocation(lat, lng) {
                fetch(`/orders/${orderId}/courier-location`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ lat, lng }),
                })
                .then(res => {
                    if (!res.ok) throw new Error('Gagal kirim lokasi');
                })
                .catch(() => showToast('❌ Gagal mengirim lokasi ke server', 'error'));

            if (!courierMarker) {
                courierMarker = L.marker([lat, lng], { icon: courierIcon }).addTo(map).bindPopup('📍 Posisi Anda');
                if (custLat && custLng) {
                    map.fitBounds([[lat, lng], [custLat, custLng]], { padding: [50, 50] });
                }
            } else {
                courierMarker.setLatLng([lat, lng]);
            }

            const status = document.getElementById('gps-status');
            const now = new Date();
            status.innerHTML = `<p class="text-green-700 font-bold text-sm">✅ GPS Aktif — dikirim ${now.getHours().toString().padStart(2,'0')}:${now.getMinutes().toString().padStart(2,'0')}:${now.getSeconds().toString().padStart(2,'0')}</p>`;
            status.className = 'bg-green-50 border border-green-200 rounded-2xl p-4 mb-4 text-center';
        }

        document.getElementById('btn-start-tracking').addEventListener('click', function() {
            if (!navigator.geolocation) {
                showToast('❌ GPS tidak tersedia di browser ini', 'error');
                return;
            }

            this.classList.add('hidden');
            document.getElementById('btn-stop-tracking').classList.remove('hidden');
            showToast('🛵 Tracking dimulai, lokasi dikirim tiap 5 detik', 'info');

            navigator.geolocation.getCurrentPosition((pos) => {
                sendLocation(pos.coords.latitude, pos.coords.longitude);
            }, () => showToast('❌ Izinkan akses GPS di browser', 'error'));

            intervalId = setInterval(() => {
                navigator.geolocation.getCurrentPosition((pos) => {
                    sendLocation(pos.coords.latitude, pos.coords.longitude);
                }, null, { enableHighAccuracy: true });
            }, 5000);
        });

        document.getElementById('btn-stop-tracking').addEventListener('click', function() {
            clearInterval(intervalId);
            intervalId = null;
            this.classList.add('hidden');
            document.getElementById('btn-start-tracking').classList.remove('hidden');
            const status = document.getElementById('gps-status');
            status.innerHTML = `<p class="text-yellow-700 font-bold text-sm">⏸ Tracking dihentikan</p>`;
            status.className = 'bg-yellow-50 border border-yellow-200 rounded-2xl p-4 mb-4 text-center';
            showToast('⏹ Tracking dihentikan', 'info');
        });
    });
    </script>
    @endpush
</x-layout>