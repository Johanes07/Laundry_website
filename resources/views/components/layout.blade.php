    <!DOCTYPE html>
    <html lang="id" class="scroll-smooth">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>FreshLaundry - Kilau Bersih, Waktu Tak Terbuang</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .glass-nav {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }
            .nav-link-hover {
                position: relative;
            }
            .nav-link-hover::after {
                content: '';
                position: absolute;
                width: 0;
                height: 2px;
                bottom: -4px;
                left: 0;
                background-color: #4f46e5;
                transition: width 0.3s ease;
            }
            .nav-link-hover:hover::after {
                width: 100%;
            }
            .footer-link:hover {
                transform: translateX(5px);
                color: white;
            }
        </style>
    </head>
    <body class="bg-[#f8fafc] text-slate-900 antialiased min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="sticky top-0 z-50 glass-nav border-b border-slate-200/50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="relative w-11 h-11 bg-gradient-to-tr from-indigo-600 to-violet-500 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-200 group-hover:rotate-[10deg] transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-400 rounded-full border-2 border-white animate-pulse"></div>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-slate-800">Fresh<span class="text-indigo-600">Laundry</span></span>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-10">
                <a href="{{ route('home') }}" class="nav-link-hover font-semibold text-slate-500 hover:text-indigo-600 transition">Beranda</a>
                <a href="{{ route('order.check') }}" class="nav-link-hover font-semibold text-slate-500 hover:text-indigo-600 transition">Cek Status</a>
                <a href="{{ route('order.create') }}"
                class="bg-slate-900 text-white px-8 py-3 rounded-2xl font-bold shadow-xl shadow-slate-200 hover:bg-indigo-600 hover:shadow-indigo-100 transition-all duration-300 active:scale-95">
                Pesan Sekarang
                </a>
            </div>

            {{-- Hamburger Button --}}
            <button id="mobile-menu-btn" class="md:hidden p-2.5 text-slate-600 bg-slate-100 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                <svg id="icon-open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                </svg>
                <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Mobile Menu Dropdown --}}
            <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100 bg-white/95 backdrop-blur-md shadow-2xl overflow-hidden transition-all duration-300">
                <div class="px-6 py-8 flex flex-col gap-3">
                    {{-- Link Beranda --}}
                    <a href="{{ route('home') }}" class="group flex items-center gap-4 p-4 rounded-2xl font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 group-hover:bg-white group-hover:shadow-md transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="text-base">Beranda</span>
                    </a>

                    {{-- Link Cek Status --}}
                    <a href="{{ route('order.check') }}" class="group flex items-center gap-4 p-4 rounded-2xl font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 group-hover:bg-white group-hover:shadow-md transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <span class="text-base">Cek Status Pesanan</span>
                    </a>

                    {{-- Divider --}}
                    <div class="my-2 border-t border-slate-100"></div>

                    {{-- Button Pesan --}}
                    <a href="{{ route('order.create') }}" class="flex items-center justify-center gap-3 w-full bg-indigo-600 text-white p-4 rounded-2xl font-bold shadow-xl shadow-indigo-100 active:scale-95 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Mulai Cuci Sekarang
                    </a>
                </div>
            </div>
    </nav>

    <main class="flex-grow">
        {{ $slot }}
    </main>

    {{-- FOOTER --}}
    <footer class="bg-[#0f172a] text-slate-400 pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-12 mb-20">
                <div class="md:col-span-1">
                    <p class="text-white font-extrabold text-2xl mb-6 italic">Fresh<span class="text-indigo-400">Laundry.</span></p>
                    <p class="text-sm leading-relaxed mb-6 opacity-80 text-balance">
                        Standar baru kebersihan pakaian. Kami memadukan teknologi dan ketelitian tangan untuk hasil yang sempurna.
                    </p>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center hover:bg-indigo-600 transition cursor-pointer">
                            <img src="{{ asset('icons/instagram.png') }}" class="w-5 h-5" alt="Instagram">
                        </div>
                        <div class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center hover:bg-indigo-600 transition cursor-pointer">
                            <img src="{{ asset('icons/gmail.png') }}" class="w-5 h-5" alt="email">
                        </div>
                    </div>
                </div>
                
                <div>
                    <p class="text-white font-bold tracking-widest text-xs uppercase mb-6">Navigasi</p>
                    <ul class="space-y-4 text-sm font-medium">
                        <li><a href="#" class="footer-link inline-block transition-all duration-300">Tentang Kami</a></li>
                        <li><a href="#layanan" class="footer-link inline-block transition-all duration-300">Layanan Premium</a></li>
                        <li><a href="#" class="footer-link inline-block transition-all duration-300">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="footer-link inline-block transition-all duration-300">Pusat Bantuan</a></li>
                    </ul>
                </div>

                <div>
                    <p class="text-white font-bold tracking-widest text-xs uppercase mb-6">Hubungi Kami</p>
                    <ul class="space-y-5 text-sm font-medium">
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center hover:bg-indigo-600 transition cursor-pointer">
                                <img src="{{ asset('icons/map.png') }}" class="w-5 h-5" alt="location">
                            </div>
                            <span class="opacity-80">Gading Serpong, Kav 12<br>Tangerang, Banten</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center hover:bg-indigo-600 transition cursor-pointer">
                                <img src="{{ asset('icons/wa.png') }}" class="w-5 h-5" alt="whatsapp">
                            </div>
                            <span class="opacity-80">0812 8888 9999</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-slate-800/50 p-6 rounded-3xl border border-slate-700">
                    <p class="text-white font-bold mb-2">Buka Setiap Hari</p>
                    <p class="text-xs text-indigo-300 mb-4 font-bold uppercase tracking-wider">07:00 - 21:00 WIB</p>
                    <p class="text-xs opacity-60 leading-relaxed">Pesan sebelum jam 10 pagi untuk layanan Same Day Service.</p>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-10 flex flex-col md:flex-row justify-between items-center gap-6 text-[11px] font-bold uppercase tracking-[2px] opacity-50">
                <p>&copy; 2026 FreshLaundry Digital. All Rights Reserved.</p>
                <div class="flex gap-8">
                    <a href="#" class="hover:text-indigo-400 transition">Privacy Policy</a>
                    <a href="#" class="hover:text-indigo-400 transition">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('icon-open');
        const iconClose = document.getElementById('icon-close');

        btn.addEventListener('click', () => {
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
            
            if (isHidden) {
                menu.style.maxHeight = '0px';
                setTimeout(() => menu.style.maxHeight = '500px', 10);
            }
        });
    </script>

    </body>
    </html>