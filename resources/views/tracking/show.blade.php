<x-layout>
    <div class="max-w-2xl mx-auto py-6 px-4">

        {{-- Header --}}
        <div class="bg-white rounded-3xl shadow-xl p-5 mb-4 border border-gray-100">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-3 h-3 rounded-full bg-green-400 animate-pulse"></div>
                <span class="font-black text-gray-800 text-lg">{{ $order->order_code }}</span>
                <span class="ml-auto text-xs px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-bold uppercase">
                    {{ $order->status_label }}
                </span>
            </div>
            <p class="text-sm text-gray-500 pl-6">{{ $order->service->name }} · {{ $order->quantity }} kg</p>
        </div>

        @if ($order->delivery_type === 'delivery' && in_array($order->status, ['ready', 'processing']))

            {{-- Map --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 mb-4">
                <div class="bg-blue-600 px-5 py-3 flex items-center gap-2">
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    <span class="text-white font-bold text-sm">Live Tracking Kurir</span>
                    <span id="last-update" class="ml-auto text-blue-200 text-xs"></span>
                </div>
                <div id="map" style="height: 380px;"></div>
            </div>

            {{-- Tombol share lokasi --}}
            <button id="btn-share-location"
                class="w-full bg-emerald-600 text-white font-black py-4 rounded-2xl shadow-lg mb-4 flex items-center justify-center gap-2 hover:bg-emerald-700 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                📍 Bagikan Lokasi Saya ke Kurir
            </button>

        @else
            <div class="bg-gray-50 rounded-3xl border border-gray-200 p-10 text-center text-gray-400 mb-4">
                <p class="text-5xl mb-3">🧺</p>
                <p class="font-black text-gray-600 text-lg mb-1">Pesanan sedang diproses</p>
                <p class="text-sm">Peta live muncul saat kurir dalam perjalanan</p>
            </div>
        @endif

        {{-- Info order --}}
        <div class="bg-white rounded-3xl shadow-xl p-5 border border-gray-100 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Layanan</span>
                <span class="font-bold">{{ $order->service->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Estimasi selesai</span>
                <span class="font-bold">{{ $order->estimated_done?->format('d M Y') ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Pembayaran</span>
                <span class="font-bold">{{ $order->payment_status_label }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Metode ambil</span>
                <span class="font-bold">{{ $order->delivery_type === 'delivery' ? '🛵 Diantar Kurir' : '🏪 Ambil Sendiri' }}</span>
            </div>
        </div>
    </div>

    @if ($order->delivery_type === 'delivery' && in_array($order->status, ['ready', 'processing']))
    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const orderId  = @json($order->id);
        const initLat  = @json($order->courierLocation?->lat ?? -6.2);
        const initLng  = @json($order->courierLocation?->lng ?? 106.8);
        const custLat  = @json($order->customer_lat);
        const custLng  = @json($order->customer_lng);

        const map = L.map('map').setView([initLat, initLng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

        const courierIcon = L.divIcon({
            html: `<div style="background:#2563eb;width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px;box-shadow:0 3px 10px rgba(0,0,0,.3)">🛵</div>`,
            className: '', iconSize: [44,44], iconAnchor: [22,22],
        });
        const customerIcon = L.divIcon({
            html: `<div style="background:#16a34a;width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px;box-shadow:0 3px 10px rgba(0,0,0,.3)">🏠</div>`,
            className: '', iconSize: [44,44], iconAnchor: [22,44],
        });

        let courierMarker = L.marker([initLat, initLng], { icon: courierIcon })
            .addTo(map).bindPopup('<b>🛵 Kurir dalam perjalanan</b>');

        let customerMarker = null;
        if (custLat && custLng) {
            customerMarker = L.marker([custLat, custLng], { icon: customerIcon })
                .addTo(map).bindPopup('<b>🏠 Lokasi Anda</b>');
            map.fitBounds([[initLat, initLng], [custLat, custLng]], { padding: [50, 50] });
        }

        function updateCourierMarker(lat, lng) {
            courierMarker.setLatLng([lat, lng]);
            map.panTo([lat, lng]);
            const now = new Date();
            document.getElementById('last-update').textContent =
                `Update: ${now.getHours().toString().padStart(2,'0')}:${now.getMinutes().toString().padStart(2,'0')}:${now.getSeconds().toString().padStart(2,'0')}`;
        }

        // Coba pakai Echo (WebSocket) kalau tersedia
        if (window.Echo) {
            window.Echo.channel(`order.${orderId}`)
                .listen('.CourierLocationUpdated', (e) => {
                    updateCourierMarker(e.lat, e.lng);
                });
        }

        // Polling sebagai fallback (jalan selalu, tiap 5 detik)
        setInterval(() => {
            fetch(`/orders/${orderId}/courier-location-get`)
                .then(r => r.json())
                .then(data => {
                    if (data.lat && data.lng) {
                        updateCourierMarker(data.lat, data.lng);
                    }
                })
                .catch(() => {});
        }, 5000);

        // Share lokasi customer
        document.getElementById('btn-share-location').addEventListener('click', function() {
            if (!navigator.geolocation) return alert('GPS tidak tersedia');
            this.textContent = '⏳ Mengambil lokasi...';
            this.disabled = true;
            navigator.geolocation.getCurrentPosition((pos) => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                fetch(`/orders/${orderId}/customer-location`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ lat, lng }),
                }).then(() => {
                    if (customerMarker) {
                        customerMarker.setLatLng([lat, lng]);
                    } else {
                        customerMarker = L.marker([lat, lng], { icon: customerIcon })
                            .addTo(map).bindPopup('<b>🏠 Lokasi Anda</b>').openPopup();
                    }
                    map.fitBounds([courierMarker.getLatLng(), [lat, lng]], { padding: [50, 50] });
                    this.innerHTML = '✅ Lokasi Dibagikan ke Kurir';
                    this.classList.replace('bg-emerald-600', 'bg-gray-400');
                }).catch(() => {
                    this.textContent = '📍 Bagikan Lokasi Saya ke Kurir';
                    this.disabled = false;
                    alert('Gagal mengirim lokasi, coba lagi.');
                });
            }, () => {
                alert('Gagal mendapatkan lokasi. Izinkan GPS di browser.');
                this.textContent = '📍 Bagikan Lokasi Saya ke Kurir';
                this.disabled = false;
            }, { enableHighAccuracy: true, timeout: 10000 });
        });
    </script>
    @endpush
    @endif
</x-layout>