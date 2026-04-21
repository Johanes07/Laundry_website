<x-layout>
    {{-- 1. HERO SECTION --}}
    <section class="relative bg-[#E0F7FA] text-slate-900 overflow-hidden border-b-4 border-[#00ACC1]">
        <div class="container-custom py-12 lg:py-24 flex flex-col md:flex-row items-center justify-between gap-8 relative z-10">
            
            {{-- KIRI: Teks & Tombol --}}
            <div class="max-w-xl text-center md:text-left w-full">
                <span class="inline-block bg-[#00ACC1] text-white text-xs font-bold px-4 py-1.5 rounded-full mb-4 shadow-sm">
                    LAYANAN LAUNDRY PREMIUM
                </span>
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight text-slate-950 mb-5 tracking-tight">
                    Cuci Bersih,<br>
                    <span class="text-[#00838F]">Tanpa Perlu Ribet</span>
                </h1>
                <p class="mb-8 text-base sm:text-lg text-slate-700 leading-relaxed max-w-lg mx-auto md:mx-0">
                    Solusi praktis untuk pakaian harianmu. Kami jemput, cuci profesional, setrika rapi, dan antar kembali ke depan pintumu.
                </p>
                <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-3 sm:gap-5">
                    <a href="{{ route('order.create') }}"
                       class="bg-[#00ACC1] hover:bg-[#00838F] text-white px-8 py-4 rounded-xl font-bold shadow-lg shadow-cyan-500/30 transition-all hover:-translate-y-1 text-center">
                       Pesan Sekarang
                    </a>
                    <a href="{{ route('order.check') }}"
                       class="bg-white border-2 border-slate-200 text-slate-800 px-8 py-4 rounded-xl font-semibold hover:border-[#00ACC1] hover:text-[#00ACC1] transition active:scale-95 shadow-sm text-center">
                       Cek Status
                    </a>
                </div>
            </div>

            {{-- KANAN: Gambar --}}
            <div class="relative w-full md:w-1/2 flex justify-center md:justify-end mt-4 md:mt-0">
                <div class="w-full max-w-sm md:max-w-none md:w-[480px] p-2 bg-white rounded-3xl shadow-2xl shadow-cyan-900/10 border-4 border-white">
                    <img src="{{ asset('images/6.jpg') }}" 
                         alt="Laundry Koin Modern" 
                         class="rounded-2xl object-cover w-full aspect-[4/3]">
                </div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 border-8 border-[#B2EBF2] rounded-full opacity-60 hidden sm:block"></div>
            </div>
        </div>
    </section>

    {{-- 2. STATS SECTION --}}
    <section class="bg-[#00ACC1] py-10 text-white shadow-inner">
        <div class="container-custom">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach([
                    ['100%', 'Garansi Kepuasan'],
                    ['5.000+', 'Pelanggan Setia'],
                    ['< 24 Jam', 'Layanan Ekspres'],
                    ['Gratis', 'Antar Jemput*']
                ] as $stat)
                <div class="bg-white/10 p-4 sm:p-6 rounded-2xl border border-white/10 text-center hover:bg-white/20 transition">
                    <div class="text-2xl sm:text-4xl md:text-5xl font-extrabold text-white mb-1 sm:mb-2">{{ $stat[0] }}</div>
                    <div class="text-xs sm:text-sm font-medium text-cyan-50 tracking-wide uppercase">{{ $stat[1] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 3. KUALITAS & PROSES --}}
    <section class="py-16 sm:py-24 bg-white">
        <div class="container-custom flex flex-col md:flex-row items-center gap-10 sm:gap-16">
            
            <div class="w-full md:w-1/2">
                <span class="text-[#00838F] font-bold text-sm uppercase mb-3 block">Mengapa FreshLaundry?</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold mb-6 text-slate-950 tracking-tight leading-tight">Kepercayaan Anda,<br> Prioritas Utama Kami</h2>
                <p class="text-slate-700 mb-6 leading-relaxed text-base sm:text-lg">Kami menggunakan standar operasional tinggi dengan mesin modern dan detergen ramah lingkungan untuk memastikan serat kain tetap terjaga, bersih sempurna, dan wangi tahan lama.</p>
                
                <ul class="space-y-4 border-t border-slate-100 pt-6">
                    @foreach(['Mesin Cuci Higienis Standar Industri', 'Detergen & Pelembut Premium', 'Proses Setrika Uap Rapi'] as $feature)
                    <li class="flex items-center gap-4 text-slate-800 font-semibold text-base hover:text-[#00ACC1] transition">
                        <span class="flex-shrink-0 bg-[#E0F7FA] text-[#00ACC1] p-2.5 rounded-full shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="w-full md:w-1/2">
                <div class="p-3 bg-white rounded-3xl shadow-2xl shadow-slate-900/10 border-8 border-slate-50 overflow-hidden">
                    <img src="{{ asset('images/5.jpg') }}" 
                         alt="Proses Pencucian Profesional" 
                         class="w-full rounded-2xl object-cover aspect-[5/4] hover:scale-105 transition-transform duration-500">
                </div>
            </div>
        </div>
    </section>

    {{-- 4. CARA PESAN --}}
    <section class="py-16 sm:py-24 bg-[#01579B] text-white">
        <div class="container-custom text-center">
            <span class="text-cyan-200 font-bold text-sm uppercase mb-3 block tracking-widest">ALUR LAYANAN</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold mb-10 sm:mb-16 text-white tracking-tight leading-tight">
                Proses Cepat & <span class="text-cyan-200">Anti Ribet</span>
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['Isi Data', 'Pesan layanan via web/app kami.', 'isidata.jpg'],
                    ['Penjemputan', 'Kurir kami mengambil pakaian Anda.', '7.jpg'],
                    ['Proses Cuci', 'Pakaian dicuci profesional.', '9.jpg'],
                    ['Pengantaran', 'Pakaian bersih diantar kembali.', '8.jpg'],
                ] as $i => $step)
                <div class="relative group h-[320px] sm:h-[380px] rounded-3xl overflow-visible transition-all duration-500 hover:-translate-y-3">
                    <div class="relative h-full w-full rounded-3xl overflow-hidden shadow-2xl border border-white/20">
                        <img src="{{ asset('images/' . $step[2]) }}" 
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#012d52] via-[#01579B]/40 to-transparent"></div>
                        <div class="absolute top-5 left-5 z-30">
                            <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-cyan-400 text-[#01579B] text-xl sm:text-2xl font-black rounded-2xl shadow-lg transform -rotate-12 group-hover:rotate-0 transition-transform">
                                {{ $i+1 }}
                            </div>
                        </div>
                        <div class="absolute inset-0 p-6 sm:p-8 flex flex-col justify-end text-left z-20">
                            <h3 class="font-bold text-xl sm:text-2xl text-white mb-1 sm:mb-2 tracking-tight group-hover:text-cyan-300 transition-colors">
                                {{ $step[0] }}
                            </h3>
                            <p class="text-white text-sm leading-relaxed font-medium">{{ $step[1] }}</p>
                            <div class="w-12 h-1.5 bg-cyan-400 mt-3 sm:mt-4 rounded-full transform origin-left transition-all duration-500 group-hover:w-full"></div>
                        </div>
                    </div>

                    @if($i < 3)
                    <div class="hidden lg:flex absolute top-1/2 -right-7 z-40 transform -translate-y-1/2 items-center justify-center">
                        <div class="bg-white p-3 rounded-full shadow-xl border-4 border-[#01579B] text-[#01579B] animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M13 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="mt-14 sm:mt-20">
                <a href="{{ route('order.create') }}"
                class="inline-block bg-white text-[#01579B] px-10 sm:px-12 py-4 sm:py-5 rounded-2xl font-extrabold text-base sm:text-lg shadow-2xl hover:bg-cyan-100 transition-all active:scale-95">
                    Mulai Pesan Sekarang
                </a>
            </div>
        </div>
    </section>
</x-layout>