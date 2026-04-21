<x-layout>
    <div class="max-w-xl mx-auto py-12 sm:py-20 px-4">
        {{-- Card Utama --}}
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] overflow-hidden border border-slate-50 text-center relative">
            
            {{-- Elemen Dekoratif --}}
            <div class="absolute -top-16 -right-16 w-40 h-40 bg-indigo-50 rounded-full opacity-60 blur-3xl"></div>
            <div class="absolute -bottom-16 -left-16 w-40 h-40 bg-blue-50 rounded-full opacity-60 blur-3xl"></div>

            <div class="relative pt-12 sm:pt-16 pb-8">
                {{-- Icon dengan Ring Berdenyut --}}
                <div class="relative w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-8">
                    <div class="absolute inset-0 bg-green-100 rounded-full animate-ping opacity-25"></div>
                    <div class="relative bg-gradient-to-br from-green-400 to-emerald-600 text-white w-full h-full rounded-full flex items-center justify-center shadow-xl shadow-green-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">Pesanan Diterima!</h1>
                <p class="text-slate-500 font-medium px-8 max-w-sm mx-auto leading-relaxed">
                    Terima kasih, <span class="text-slate-900 font-bold">{{ explode(' ', $order->customer_name)[0] }}</span>! Kurir kami akan segera menjemput pakaian Anda.
                </p>
            </div>

            <div class="px-6 sm:px-12 pb-10 relative">
                {{-- Tiket Kode Order --}}
                <div class="bg-slate-50 rounded-[2rem] p-6 border border-slate-100 mb-8 relative group overflow-hidden">
                    <div class="absolute top-0 right-0 p-3">
                        <svg class="w-5 h-5 text-slate-300 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.25em] mb-3">Order ID</p>
                    <p class="text-3xl sm:text-4xl font-black text-indigo-600 tracking-wider font-mono">
                        {{ $order->order_code }}
                    </p>
                </div>

                {{-- Detail Transaksi --}}
                <div class="space-y-4 text-left mb-10">
                    <div class="flex justify-between items-center py-1">
                        <span class="text-slate-400 text-sm font-medium">Layanan</span>
                        <span class="font-bold text-slate-800 bg-slate-100 px-3 py-1 rounded-full text-xs uppercase">{{ $order->service->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-slate-400 text-sm font-medium">Metode Bayar</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $order->payment_method_label }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                        <span class="text-slate-900 font-bold">Total Pembayaran</span>
                        <span class="text-2xl font-black text-indigo-600">Rp{{ number_format($order->total_price) }}</span>
                    </div>
                </div>

                {{-- Card Instruksi Pembayaran --}}
                @if (in_array($order->payment_method, ['transfer', 'e_wallet']) && $order->storeAccount)
                    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-3xl p-6 text-left mb-10 shadow-xl shadow-indigo-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 opacity-10 translate-x-4 -translate-y-4">
                            <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                        </div>
                        <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-4 flex items-center gap-2">
                             Informasi Transfer
                        </p>
                        <div class="relative z-10">
                            <p class="text-white/80 text-xs mb-1">{{ $order->storeAccount->bank_name }}</p>
                            <p class="text-white text-xl font-mono font-bold tracking-widest mb-1">{{ $order->storeAccount->account_number }}</p>
                            <p class="text-indigo-100 text-xs">a.n. {{ $order->storeAccount->account_holder }}</p>
                        </div>
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="flex flex-col gap-4">
                    <a href="{{ route('order.check', ['code' => $order->order_code]) }}"
                        class="bg-slate-900 text-white font-bold py-5 rounded-2xl hover:bg-indigo-600 transition-all duration-300 shadow-xl shadow-slate-200 hover:shadow-indigo-200 uppercase tracking-widest text-xs flex items-center justify-center gap-2 group">
                        Lacak Status Pesanan
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="{{ route('home') }}"
                        class="text-slate-400 font-bold py-2 hover:text-indigo-600 transition-colors text-[10px] uppercase tracking-[0.3em]">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>

            {{-- Decorative Bottom Notch --}}
            <div class="flex justify-center gap-1 mb-6">
                <div class="w-2 h-2 bg-slate-100 rounded-full"></div>
                <div class="w-12 h-2 bg-slate-100 rounded-full"></div>
                <div class="w-2 h-2 bg-slate-100 rounded-full"></div>
            </div>
        </div>
    </div>
</x-layout>