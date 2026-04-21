<x-layout>
    <div class="max-w-2xl mx-auto py-8 sm:py-12 px-4">
        <div class="flex items-center gap-3 mb-8 sm:mb-10">
            <div class="bg-blue-600 text-white p-2.5 sm:p-3 rounded-2xl shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Order Laundry Baru</h1>
        </div>

        <form action="{{ route('order.store') }}" method="POST" class="space-y-6 sm:space-y-8" id="order-form">
            @csrf

            {{-- Card 1: Informasi Kontak --}}
            <div class="bg-white rounded-3xl shadow-xl p-5 sm:p-8 border border-gray-100">
                <div class="flex items-center gap-2 mb-5 text-blue-600 font-bold uppercase tracking-widest text-xs">
                    <span class="w-8 h-[2px] bg-blue-600"></span>
                    Informasi Kontak
                </div>
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-sm"
                            placeholder="Contoh: Budi Santoso" required>
                        @error('customer_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">WhatsApp</label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 shadow-sm"
                                placeholder="0812..." required>
                            @error('customer_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Email <small class="text-gray-400 font-normal">(opsional)</small>
                            </label>
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 shadow-sm"
                                placeholder="budi@email.com">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Penjemputan</label>
                        <textarea name="customer_address" rows="3"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 shadow-sm"
                            placeholder="Jl. Mawar No. 123, RT/RW, Kelurahan, Kota..." required>{{ old('customer_address') }}</textarea>
                        @error('customer_address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Card 2: Detail Pesanan --}}
            <div class="bg-white rounded-3xl shadow-xl p-5 sm:p-8 border border-gray-100">
                <div class="flex items-center gap-2 mb-5 text-cyan-500 font-bold uppercase tracking-widest text-xs">
                    <span class="w-8 h-[2px] bg-cyan-500"></span>
                    Detail Pesanan
                </div>
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Layanan</label>
                        <select name="service_id"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 font-medium" required>
                            <option value="">-- Pilih Paket Laundry --</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} (Rp{{ number_format($service->price_per_unit) }}/{{ $service->unit }}, {{ $service->estimated_days }} hari)
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Tambah setelah field "Pilih Layanan" --}}
                                {{-- Metode Pengambilan --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Metode Pengambilan</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="delivery_type" value="pickup" class="sr-only peer"
                                                {{ old('delivery_type', 'pickup') === 'pickup' ? 'checked' : '' }}
                                                onchange="toggleLocationSection(this.value)">
                                            <div class="flex flex-col items-center p-4 rounded-2xl border-2 border-gray-200
                                                        peer-checked:border-cyan-500 peer-checked:bg-cyan-50 transition-all">
                                                <span class="text-2xl mb-1">🏪</span>
                                                <span class="font-bold text-sm text-gray-800">Ambil Sendiri</span>
                                                <span class="text-xs text-gray-400">Antar ke toko</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="delivery_type" value="delivery" class="sr-only peer"
                                                {{ old('delivery_type') === 'delivery' ? 'checked' : '' }}
                                                onchange="toggleLocationSection(this.value)">
                                            <div class="flex flex-col items-center p-4 rounded-2xl border-2 border-gray-200
                                                        peer-checked:border-cyan-500 peer-checked:bg-cyan-50 transition-all">
                                                <span class="text-2xl mb-1">🛵</span>
                                                <span class="font-bold text-sm text-gray-800">Diantar Kurir</span>
                                                <span class="text-xs text-gray-400">Ke alamat Anda</span>
                                            </div>
                                        </label>
                                    </div>
                                    @error('delivery_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                {{-- Section Koordinat — muncul saat pilih "Diantar Kurir" --}}
                                <div id="location-section" class="hidden">
                                    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
                                        <p class="text-sm font-bold text-blue-700 mb-3">📍 Titik Penjemputan & Antar</p>

                                        {{-- Hidden inputs koordinat --}}
                                        <input type="hidden" name="customer_lat" id="customer_lat" value="{{ old('customer_lat') }}">
                                        <input type="hidden" name="customer_lng" id="customer_lng" value="{{ old('customer_lng') }}">

                                        {{-- Tombol ambil lokasi otomatis --}}
                                        <button type="button" id="btn-get-location"
                                            class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl mb-3 flex items-center justify-center gap-2 hover:bg-blue-700 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            Gunakan Lokasi Saya Sekarang
                                        </button>

                                        {{-- Status lokasi --}}
                                        <div id="location-status" class="hidden text-xs text-center mb-3 text-blue-600 font-medium"></div>

                                        {{-- Mini map preview --}}
                                        <div id="map-preview" class="hidden rounded-xl overflow-hidden" style="height: 200px;"></div>

                                        {{-- Atau input manual --}}
                                        <p class="text-xs text-blue-500 text-center mt-2">
                                            atau isi koordinat manual (opsional)
                                        </p>
                                        <div class="grid grid-cols-2 gap-2 mt-2">
                                            <div>
                                                <label class="text-xs text-gray-500 mb-1 block">Latitude</label>
                                                <input type="text" id="input-lat" placeholder="-6.200000"
                                                    class="w-full bg-white border border-blue-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400"
                                                    oninput="syncManualInput()">
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-500 mb-1 block">Longitude</label>
                                                <input type="text" id="input-lng" placeholder="106.800000"
                                                    class="w-full bg-white border border-blue-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400"
                                                    oninput="syncManualInput()">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Estimasi Berat (kg)</label>
                        <div class="relative">
                            <input type="number" name="quantity" min="1" step="0.5" value="{{ old('quantity') }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-4 pr-12 py-3 focus:ring-2 focus:ring-cyan-500 shadow-sm" required>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">KG</span>
                        </div>
                        @error('quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Instruksi Tambahan</label>
                        <textarea name="notes" rows="2"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 shadow-sm"
                            placeholder="Contoh: Pisahkan pakaian luntur, cuci dengan air dingin...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Card 3: Metode Pembayaran --}}
            <div class="bg-white rounded-3xl shadow-xl p-5 sm:p-8 border border-gray-100">
                <div class="flex items-center gap-2 mb-5 text-emerald-500 font-bold uppercase tracking-widest text-xs">
                    <span class="w-8 h-[2px] bg-emerald-500"></span>
                    Metode Pembayaran
                </div>

                <div class="grid grid-cols-3 gap-2 sm:gap-3 mb-6" id="payment-method-group">
                    @php
                        $methods = [
                            ['value' => 'transfer', 'label' => 'Transfer', 'desc' => 'BCA, Mandiri, dll', 'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>', 'color' => 'text-indigo-600 bg-indigo-50'],
                            ['value' => 'e_wallet', 'label' => 'E-Wallet', 'desc' => 'GoPay, OVO, dll', 'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/><circle cx="12" cy="17" r="1" fill="currentColor"/><line x1="9" y1="6" x2="15" y2="6"/></svg>', 'color' => 'text-emerald-600 bg-emerald-50'],
                            ['value' => 'cod', 'label' => 'COD', 'desc' => 'Saat kurir tiba', 'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><circle cx="12" cy="14" r="2"/></svg>', 'color' => 'text-amber-600 bg-amber-50'],
                        ];
                    @endphp
                    @foreach ($methods as $method)
                        <label class="payment-card relative cursor-pointer group">
                            <input type="radio" name="payment_method" value="{{ $method['value'] }}"
                                class="sr-only peer"
                                {{ old('payment_method') === $method['value'] ? 'checked' : '' }}
                                onchange="handlePaymentMethodChange(this.value)">
                            <div class="flex flex-col items-center text-center p-3 sm:p-5 rounded-2xl border-2 border-gray-200
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-50
                                        hover:border-gray-300 transition-all h-full">
                                <div class="{{ $method['color'] }} w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center mb-2 sm:mb-3">
                                    {!! $method['icon'] !!}
                                </div>
                                <span class="font-bold text-gray-800 text-xs sm:text-sm">{{ $method['label'] }}</span>
                                <span class="text-gray-400 text-[10px] sm:text-xs mt-0.5 hidden sm:block">{{ $method['desc'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('payment_method')<p class="text-red-500 text-xs -mt-4 mb-4">{{ $message }}</p>@enderror

                <div id="account-section" class="hidden">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Pilih Rekening Tujuan</label>
                    @if ($storeAccounts->isEmpty())
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-700">
                            ⚠️ Belum ada rekening toko yang aktif. Hubungi kami via WhatsApp.
                        </div>
                    @else
                        <div class="space-y-3" id="account-list">
                            @foreach ($storeAccounts as $account)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="store_account_id" value="{{ $account->id }}"
                                        class="sr-only peer"
                                        {{ old('store_account_id') == $account->id ? 'checked' : '' }}>
                                    <div class="flex items-center gap-3 sm:gap-4 p-3 sm:p-4 rounded-xl border-2 border-gray-200
                                                peer-checked:border-emerald-500 peer-checked:bg-emerald-50
                                                hover:border-gray-300 transition-all">
                                        <div class="w-10 h-10 rounded-full shrink-0 flex items-center justify-center overflow-hidden bg-gray-100">
                                            @if ($account->logo)
                                                <img src="{{ asset('storage/' . $account->logo) }}" alt="{{ $account->bank_name }}" class="w-full h-full object-contain p-1">
                                            @else
                                                <span class="bg-gradient-to-br from-blue-500 to-blue-700 w-full h-full flex items-center justify-center text-white font-black text-xs">
                                                    {{ strtoupper(substr($account->bank_name, 0, 3)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-800 text-sm">{{ $account->bank_name }}</p>
                                            <p class="text-blue-600 font-mono font-bold tracking-widest text-sm truncate">{{ $account->account_number }}</p>
                                            <p class="text-gray-400 text-xs">a.n. {{ $account->account_holder }}</p>
                                        </div>
                                        <svg class="w-5 h-5 text-emerald-500 opacity-0 peer-checked:opacity-100 shrink-0 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                    @error('store_account_id')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                    <div class="mt-4 bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-blue-700">
                        <div class="flex items-center gap-2 font-bold mb-2">
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><circle cx="12" cy="16" r=".5" fill="currentColor"/></svg>
                            Cara pembayaran
                        </div>
                        <ol class="list-decimal list-inside space-y-1 text-blue-600 text-xs sm:text-sm">
                            <li>Transfer ke rekening di atas sesuai total tagihan</li>
                            <li>Simpan bukti transfer</li>
                            <li>Kirim bukti ke WhatsApp kami setelah pesanan dikonfirmasi</li>
                        </ol>
                    </div>
                </div>

                <div id="cod-section" class="hidden">
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
                        <div class="flex items-center gap-2 font-bold mb-2">
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><circle cx="12" cy="16" r=".5" fill="currentColor"/></svg>
                            Tentang COD
                        </div>
                        <ul class="space-y-1 text-amber-700 list-disc list-inside text-xs sm:text-sm">
                            <li>Pembayaran dilakukan saat kurir mengantarkan cucian</li>
                            <li>Siapkan uang pas untuk mempermudah transaksi</li>
                            <li>Total tagihan final dihitung setelah timbang di laundry</li>
                        </ul>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-black py-4 sm:py-5 rounded-2xl shadow-xl shadow-blue-200 hover:scale-[1.02] active:scale-95 transition-all text-base sm:text-lg">
                Kirim Pesanan Laundry
            </button>
        </form>
    </div>

    <script>
        function handlePaymentMethodChange(value) {
            const accountSection = document.getElementById('account-section');
            const codSection = document.getElementById('cod-section');
            const accountInputs = document.querySelectorAll('input[name="store_account_id"]');
            if (value === 'cod') {
                accountSection.classList.add('hidden');
                codSection.classList.remove('hidden');
                accountInputs.forEach(i => i.required = false);
            } else {
                accountSection.classList.remove('hidden');
                codSection.classList.add('hidden');
                if (accountInputs.length > 0) accountInputs.forEach(i => i.required = true);
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            const checked = document.querySelector('input[name="payment_method"]:checked');
            if (checked) handlePaymentMethodChange(checked.value);
        });
    </script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let previewMap = null;
    let previewMarker = null;

    function toggleLocationSection(value) {
        const section = document.getElementById('location-section');
        if (value === 'delivery') {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
        }
    }

    // Jalankan saat page load (kalau old value = delivery)
    document.addEventListener('DOMContentLoaded', function () {
        const checked = document.querySelector('input[name="delivery_type"]:checked');
        if (checked) toggleLocationSection(checked.value);

        // Kalau ada old value koordinat, tampilkan langsung
        const oldLat = document.getElementById('customer_lat').value;
        const oldLng = document.getElementById('customer_lng').value;
        if (oldLat && oldLng) {
            document.getElementById('input-lat').value = oldLat;
            document.getElementById('input-lng').value = oldLng;
            showMapPreview(parseFloat(oldLat), parseFloat(oldLng));
            showLocationStatus('✅ Lokasi tersimpan', 'text-green-600');
        }
    });

    document.getElementById('btn-get-location').addEventListener('click', function () {
        if (!navigator.geolocation) {
            showLocationStatus('❌ GPS tidak tersedia di browser ini', 'text-red-500');
            return;
        }

        showLocationStatus('⏳ Mengambil lokasi...', 'text-blue-600');
        this.disabled = true;
        this.textContent = 'Mengambil lokasi...';

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;

                document.getElementById('customer_lat').value = lat;
                document.getElementById('customer_lng').value = lng;
                document.getElementById('input-lat').value = lat.toFixed(6);
                document.getElementById('input-lng').value = lng.toFixed(6);

                showMapPreview(lat, lng);
                showLocationStatus('✅ Lokasi berhasil diambil', 'text-green-600');

                const btn = document.getElementById('btn-get-location');
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7"/></svg> Lokasi Didapat`;
                btn.classList.replace('bg-blue-600', 'bg-green-600');
                btn.classList.replace('hover:bg-blue-700', 'hover:bg-green-700');
            },
            (err) => {
                showLocationStatus('❌ Gagal: izinkan akses GPS di browser', 'text-red-500');
                const btn = document.getElementById('btn-get-location');
                btn.disabled = false;
                btn.textContent = 'Coba Lagi';
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    });

    function syncManualInput() {
        const lat = parseFloat(document.getElementById('input-lat').value);
        const lng = parseFloat(document.getElementById('input-lng').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            document.getElementById('customer_lat').value = lat;
            document.getElementById('customer_lng').value = lng;
            showMapPreview(lat, lng);
        }
    }

    function showMapPreview(lat, lng) {
        const container = document.getElementById('map-preview');
        container.classList.remove('hidden');

        if (!previewMap) {
            previewMap = L.map('map-preview', { zoomControl: true, dragging: true })
                          .setView([lat, lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(previewMap);

            const icon = L.divIcon({
                html: `<div style="background:#2563eb;width:36px;height:36px;border-radius:50%;
                       display:flex;align-items:center;justify-content:center;font-size:18px;
                       box-shadow:0 2px 8px rgba(0,0,0,.3)">🏠</div>`,
                className: '',
                iconSize: [36, 36],
                iconAnchor: [18, 36],
            });

            previewMarker = L.marker([lat, lng], { icon, draggable: true }).addTo(previewMap);

            // Drag marker untuk adjust lokasi
            previewMarker.on('dragend', function (e) {
                const pos = e.target.getLatLng();
                document.getElementById('customer_lat').value = pos.lat;
                document.getElementById('customer_lng').value = pos.lng;
                document.getElementById('input-lat').value = pos.lat.toFixed(6);
                document.getElementById('input-lng').value = pos.lng.toFixed(6);
                showLocationStatus('📍 Lokasi diperbarui (drag)', 'text-blue-600');
            });
        } else {
            previewMap.setView([lat, lng], 16);
            previewMarker.setLatLng([lat, lng]);
        }
    }

    function showLocationStatus(msg, colorClass) {
        const el = document.getElementById('location-status');
        el.className = `text-xs text-center mb-3 font-medium ${colorClass}`;
        el.textContent = msg;
        el.classList.remove('hidden');
    }
</script>
</x-layout>