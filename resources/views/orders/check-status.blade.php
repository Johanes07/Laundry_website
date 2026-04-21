<x-layout>
    <div class="max-w-xl mx-auto px-4 py-8 md:py-12 relative">
        {{-- Dekorasi Latar Belakang --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-64 bg-gradient-to-b from-blue-50/50 to-transparent -z-10 rounded-full blur-3xl"></div>

        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-3 tracking-tight">Lacak Pesanan</h1>
            <p class="text-sm md:text-base text-slate-500 font-medium max-w-xs mx-auto leading-relaxed">
                Pantau progres laundry Anda secara <span class="text-blue-600 font-bold">real-time</span> dari mana saja.
            </p>
        </div>

        {{-- Form Cek - Desain Lebih Modern & Floating --}}
        <form method="GET" action="{{ route('order.check') }}" class="relative mb-12">
            <div class="flex flex-col md:flex-row items-stretch bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.08)] overflow-hidden border border-slate-100 p-2 group transition-all focus-within:ring-4 focus-within:ring-blue-100">
                
                <div class="flex items-center flex-1 px-4">
                    <div class="text-slate-400 group-focus-within:text-blue-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="code"
                        value="{{ request('code') }}"
                        placeholder="Masukkan Kode Order Anda..."
                        class="w-full px-4 py-4 md:py-5 text-base md:text-lg font-bold text-slate-700 focus:outline-none placeholder:text-slate-300 placeholder:font-normal">
                </div>

                <button type="submit"
                    class="bg-slate-900 text-white px-10 py-4 md:py-5 font-bold hover:bg-blue-600 transition-all active:scale-95 rounded-2xl shadow-lg shadow-slate-200">
                    Cek Status
                </button>
            </div>
        </form>

        @error('code')
            <div class="bg-red-50 border border-red-100 text-red-600 p-4 rounded-2xl mb-8 flex items-center animate-bounce-short">
                <svg class="h-5 w-5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-bold text-sm">{{ $message }}</span>
            </div>
        @enderror

        {{-- Hasil - Desain Card Cinematic --}}
        @if ($order)
            <div class="bg-white rounded-[2.5rem] shadow-[0_30px_60px_rgba(0,0,0,0.1)] overflow-hidden border border-slate-50 animate-in fade-in zoom-in-95 duration-500">

                {{-- Header Card dengan Gradien Mesh --}}
                <div class="bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-950 p-7 sm:p-10 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative z-10">
                        <div>
                            <p class="text-blue-300 text-[10px] uppercase tracking-[0.3em] font-black mb-1">Rincian Pesanan</p>
                            <p class="text-2xl sm:text-3xl font-mono font-black tracking-tighter">{{ $order->order_code }}</p>
                        </div>
                        
                        @php
                            $statusStyles = match($order->status) {
                                'pending'    => ['color' => 'bg-amber-400 text-amber-950', 'label' => 'Menunggu'],
                                'confirmed'  => ['color' => 'bg-blue-500 text-white',      'label' => 'Diterima'],
                                'processing' => ['color' => 'bg-indigo-500 text-white',    'label' => 'Dicuci'],
                                'ready'      => ['color' => 'bg-emerald-500 text-white',   'label' => 'Siap Antar'],
                                'completed'  => ['color' => 'bg-slate-700 text-slate-100', 'label' => 'Selesai'],
                                default      => ['color' => 'bg-slate-100 text-slate-700', 'label' => $order->status],
                            };
                        @endphp
                        <span id="status-badge" class="px-5 py-2.5 rounded-full text-[11px] font-black uppercase tracking-widest {{ $statusStyles['color'] }} shadow-2xl transition-all duration-500">
                            {{ $statusStyles['label'] }}
                        </span>
                    </div>
                </div>

                <div class="p-6 sm:p-10 space-y-10">

                    {{-- Info Grid dengan Ikon Halus --}}
                    <div class="grid grid-cols-2 gap-8">
                        <div class="group">
                            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-2">Pelanggan</p>
                            <p class="font-extrabold text-slate-800 text-base sm:text-lg group-hover:text-blue-600 transition-colors">{{ $order->customer_name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-2">Total Tagihan</p>
                            <p class="font-black text-2xl text-slate-900 tracking-tighter">Rp{{ number_format($order->total_price) }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-2">Layanan</p>
                            <div class="inline-flex items-center px-3 py-1 bg-slate-100 rounded-lg">
                                <p class="font-bold text-slate-700 text-xs uppercase">{{ $order->service->name }}</p>
                            </div>
                        </div>
                        @if ($order->estimated_done)
                        <div class="text-right">
                            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-2">Estimasi Selesai</p>
                            <p class="font-bold text-slate-800 text-sm sm:text-base italic underline decoration-blue-200 underline-offset-4">
                                {{ $order->estimated_done->format('d M Y') }}
                            </p>
                        </div>
                        @endif
                    </div>

                    {{-- Progress Tracker - Dibuat Lebih "Clean" --}}
                    <div class="py-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8 text-center">Tracking Log</p>

                        @php
                            $allSteps   = ['pending', 'confirmed', 'processing', 'ready', 'completed'];
                            $stepLabels = ['Menunggu', 'Diterima', 'Dicuci', 'Siap Antar', 'Selesai'];
                            $currentIdx = array_search($order->status, $allSteps);
                        @endphp

                        {{-- Desktop Tracker --}}
                        <div class="hidden sm:flex items-start justify-between relative px-4" id="progress-tracker-desktop">
                            @foreach ($allSteps as $i => $step)
                                <div class="flex flex-col items-center relative z-10 group">
                                    <div data-step-circle
                                         class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-700
                                            {{ $i < $currentIdx ? 'bg-emerald-500 border-emerald-500 shadow-lg shadow-emerald-100' : ($i === $currentIdx ? 'bg-white border-blue-600 ring-8 ring-blue-50 shadow-xl' : 'bg-white border-slate-200') }}">
                                        @if ($i < $currentIdx)
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        @else
                                            <div class="w-2.5 h-2.5 rounded-full {{ $i === $currentIdx ? 'bg-blue-600 animate-pulse' : 'bg-slate-200' }}"></div>
                                        @endif
                                    </div>
                                    <p data-step-label
                                       class="text-[10px] font-black mt-4 text-center absolute -bottom-8 w-20 uppercase tracking-tighter
                                            {{ $i < $currentIdx ? 'text-emerald-600' : ($i === $currentIdx ? 'text-blue-600' : 'text-slate-400') }}">
                                        {{ $stepLabels[$i] }}
                                    </p>
                                </div>
                                @if ($i < count($allSteps) - 1)
                                    <div data-step-connector
                                         class="flex-1 h-[2px] mt-5 -mx-2 transition-all duration-1000
                                            {{ $i < $currentIdx ? 'bg-emerald-400' : 'bg-slate-100' }}">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        {{-- Spacing for desktop labels --}}
                        <div class="hidden sm:block h-8"></div>

                        {{-- Mobile Tracker --}}
                        <div class="flex sm:hidden flex-col gap-1" id="progress-tracker-mobile">
                            @foreach ($allSteps as $i => $step)
                                <div class="flex items-start gap-5">
                                    <div class="flex flex-col items-center">
                                        <div data-step-circle-mobile
                                             class="w-9 h-9 rounded-full flex items-center justify-center border-2 transition-all shrink-0
                                                {{ $i < $currentIdx ? 'bg-emerald-500 border-emerald-500 shadow-md' : ($i === $currentIdx ? 'bg-white border-blue-600 ring-4 ring-blue-50' : 'bg-white border-slate-200') }}">
                                            @if ($i < $currentIdx)
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            @else
                                                <div class="w-2 h-2 rounded-full {{ $i === $currentIdx ? 'bg-blue-600' : 'bg-slate-200' }}"></div>
                                            @endif
                                        </div>
                                        @if ($i < count($allSteps) - 1)
                                            <div data-step-connector-mobile
                                                 class="w-[2px] flex-1 min-h-[35px] my-1 transition-all duration-700
                                                    {{ $i < $currentIdx ? 'bg-emerald-400' : 'bg-slate-100' }}">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="pt-1.5">
                                        <p data-step-label-mobile
                                           class="text-xs font-black uppercase tracking-widest
                                                {{ $i < $currentIdx ? 'text-emerald-600' : ($i === $currentIdx ? 'text-blue-600' : 'text-slate-400') }}">
                                            {{ $stepLabels[$i] }}
                                        </p>
                                        @if ($i === $currentIdx)
                                            <p class="text-[10px] text-slate-400 font-bold mt-0.5">Sedang Berlangsung</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Info Pembayaran - Card Style --}}
                    <div class="bg-slate-50 rounded-3xl p-6 border border-slate-100">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-8 h-8 rounded-xl bg-white shadow-sm flex items-center justify-center text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <p class="text-xs font-black text-slate-800 uppercase tracking-widest">Informasi Pembayaran</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500 font-medium">Metode</span>
                                    <span id="payment-method" class="font-bold text-slate-800">{{ $order->payment_method_label }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500 font-medium">Status</span>
                                @php
                                    $payBadge = match($order->payment_status) {
                                        'paid'   => 'bg-emerald-100 text-emerald-700',
                                        'unpaid' => 'bg-red-100 text-red-700',
                                        'cod'    => 'bg-amber-100 text-amber-700',
                                        default  => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <span id="payment-status" class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $payBadge }}">
                                    {{ $order->payment_status_label }}
                                </span>
                            </div>
                        </div>

                        {{-- Rekening (hanya jika belum bayar) --}}
                        @if ($order->payment_status === 'unpaid' && $order->storeAccount)
                            <div class="mt-6 pt-6 border-t border-slate-200/60">
                                <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tujuan Transfer</span>
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded italic">{{ $order->storeAccount->bank_name }}</span>
                                    </div>
                                    <p class="text-lg font-mono font-black text-slate-800 tracking-widest mb-1">{{ $order->storeAccount->account_number }}</p>
                                    <p class="text-[11px] text-slate-500 font-medium">a.n. {{ $order->storeAccount->account_holder }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Footer Bantuan --}}
                    <div class="text-center pt-4">
                        <a href="https://wa.me/{{ config('app.whatsapp_number') }}?text=Halo, saya ingin menanyakan pesanan {{ $order->order_code }}"
                            target="_blank"
                            class="inline-flex items-center justify-center gap-3 w-full bg-emerald-50 text-emerald-700 font-bold py-4 rounded-2xl hover:bg-emerald-100 transition-all text-sm group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
                            Hubungi Admin via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Logika JavaScript Tetap (Tidak Ada Perubahan Logika) --}}
        @if ($order)
            @push('scripts')
                <script>
                    // ... (Seluruh script Anda tetap di sini, kelas-kelas di dalam fungsi updateTracker perlu disesuaikan dengan class baru di atas jika ingin animasi real-time mengikuti desain baru)
                    document.addEventListener('DOMContentLoaded', function () {
                        const orderCode = "{{ $order->order_code }}";
                        const statusLabels = {
                            pending: 'Menunggu',
                            confirmed: 'Diterima',
                            processing: 'Dicuci',
                            ready: 'Siap Antar',
                            completed: 'Selesai',
                        };

                        const statusBadgeColors = {
                            pending: 'bg-amber-400 text-amber-950',
                            confirmed: 'bg-blue-500 text-white',
                            processing: 'bg-indigo-500 text-white',
                            ready: 'bg-emerald-500 text-white',
                            completed: 'bg-slate-700 text-slate-100',
                        };

                        const allSteps = ['pending', 'confirmed', 'processing', 'ready', 'completed'];

                        function updateTracker(status) {
                            const currentIdx = allSteps.indexOf(status);

                            // Update Desktop
                            const circles = document.querySelectorAll('[data-step-circle]');
                            const connectors = document.querySelectorAll('[data-step-connector]');
                            const labels = document.querySelectorAll('[data-step-label]');

                            circles.forEach((circle, i) => {
                                if (i < currentIdx) {
                                    circle.className = 'w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-700 bg-emerald-500 border-emerald-500 shadow-lg shadow-emerald-100';
                                    circle.innerHTML = `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`;
                                } else if (i === currentIdx) {
                                    circle.className = 'w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-700 bg-white border-blue-600 ring-8 ring-blue-50 shadow-xl';
                                    circle.innerHTML = `<div class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></div>`;
                                } else {
                                    circle.className = 'w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-700 bg-white border-slate-200';
                                    circle.innerHTML = `<div class="w-2.5 h-2.5 rounded-full bg-slate-200"></div>`;
                                }
                            });

                            connectors.forEach((line, i) => {
                                line.className = 'flex-1 h-[2px] mt-5 -mx-2 transition-all duration-1000 ' + (i < currentIdx ? 'bg-emerald-400' : 'bg-slate-100');
                            });

                            labels.forEach((label, i) => {
                                label.className = 'text-[10px] font-black mt-4 text-center absolute -bottom-8 w-20 uppercase tracking-tighter ' + (i < currentIdx ? 'text-emerald-600' : (i === currentIdx ? 'text-blue-600' : 'text-slate-400'));
                            });

                            // Mobile (Sama logikanya, tinggal sesuaikan classnya)
                            const mCircles = document.querySelectorAll('[data-step-circle-mobile]');
                            const mConnectors = document.querySelectorAll('[data-step-connector-mobile]');
                            const mLabels = document.querySelectorAll('[data-step-label-mobile]');

                            mCircles.forEach((circle, i) => {
                                if (i < currentIdx) {
                                    circle.className = 'w-9 h-9 rounded-full flex items-center justify-center border-2 transition-all shrink-0 bg-emerald-500 border-emerald-500 shadow-md';
                                    circle.innerHTML = `<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`;
                                } else if (i === currentIdx) {
                                    circle.className = 'w-9 h-9 rounded-full flex items-center justify-center border-2 transition-all shrink-0 bg-white border-blue-600 ring-4 ring-blue-50';
                                    circle.innerHTML = `<div class="w-2 h-2 rounded-full bg-blue-600"></div>`;
                                } else {
                                    circle.className = 'w-9 h-9 rounded-full flex items-center justify-center border-2 transition-all shrink-0 bg-white border-slate-200';
                                    circle.innerHTML = `<div class="w-2 h-2 rounded-full bg-slate-200"></div>`;
                                }
                            });
                            
                            mConnectors.forEach((line, i) => {
                                line.className = 'w-[2px] flex-1 min-h-[35px] my-1 transition-all duration-700 ' + (i < currentIdx ? 'bg-emerald-400' : 'bg-slate-100');
                            });
                        }

                        window.Echo.channel(`order.${orderCode}`)
                            .listen('.status.updated', (data) => {
                                const badge = document.getElementById('status-badge');
                                if (badge) {
                                    badge.textContent = statusLabels[data.status] ?? data.status;
                                    badge.className = `px-5 py-2.5 rounded-full text-[11px] font-black uppercase tracking-widest shadow-2xl transition-all duration-500 ${statusBadgeColors[data.status] ?? 'bg-slate-100 text-slate-700'}`;
                                }
                                updateTracker(data.status);
                                 // 🔥 TAMBAHAN: PAYMENT UPDATE
                                const paymentStatus = document.getElementById('payment-status');
                                const paymentMethod = document.getElementById('payment-method');

                                const paymentColors = {
                                    paid: 'bg-emerald-100 text-emerald-700',
                                    unpaid: 'bg-red-100 text-red-700',
                                    cod: 'bg-amber-100 text-amber-700',
                                };

                                if (paymentStatus) {
                                    paymentStatus.textContent = data.payment_label;
                                    paymentStatus.className = `px-3 py-1 rounded-full text-[10px] font-black uppercase ${paymentColors[data.payment_status]}`;
                                }

                                if (paymentMethod) {
                                    paymentMethod.textContent = data.payment_method;
                                }

                                showToast(`Update: ${statusLabels[data.status]} / ${data.payment_label}`);
                            });
                        
                        function showToast(message) {
                            const toast = document.createElement('div');
                            toast.className = 'fixed bottom-6 right-4 left-4 sm:left-auto sm:right-6 sm:w-auto bg-slate-900 text-white px-8 py-4 rounded-2xl shadow-2xl font-bold text-sm z-50 animate-in slide-in-from-right-10';
                            toast.textContent = message;
                            document.body.appendChild(toast);
                            setTimeout(() => toast.remove(), 4000);
                        }
                    });
                </script>
            @endpush
        @endif
    </div>
</x-layout>