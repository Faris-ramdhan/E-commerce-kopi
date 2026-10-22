<div class="min-h-screen w-full lg:grid lg:grid-cols-12 bg-[#FAF9F5] dark:bg-[#1f2321]">
    <!-- Kolom Kiri: Form Otentikasi -->
    <div class="flex flex-col justify-between p-6 sm:p-12 lg:col-span-5 xl:col-span-4 min-h-screen">
        <div>
            <!-- Brand Logo -->
            <div class="mb-8">
                @include('filament.admin.logo')
            </div>

            <div class="max-w-md mx-auto lg:mx-0">
                <div class="mb-6">
                    <h1 class="font-serif text-2xl font-bold tracking-tight text-[#2C302E] dark:text-[#FAF9F5]">
                        {{ $this->getHeading() }}
                    </h1>
                    @if ($subheading = $this->getSubheading())
                        <p class="mt-1.5 text-sm text-[#525754] dark:text-[#9CB4A6]">
                            {{ $subheading }}
                        </p>
                    @endif
                </div>

                <!-- Form Login Filament -->
                <div class="bg-white dark:bg-[#2C302E] p-6 sm:p-8 rounded-2xl border border-[#E3D5CA] dark:border-[#3d423f] shadow-sm">
                    {{ $this->content }}
                </div>
            </div>
        </div>

        <div class="mt-8 text-xs text-[#525754] dark:text-[#9CB4A6] text-center lg:text-left">
            &copy; {{ date('Y') }} Kopi Nusantara Roastery. Sistem Operasional Internal.
        </div>
    </div>

    <!-- Kolom Kanan: Panel Visual Branding Kopi (Desktop Only) -->
    <div class="hidden lg:flex lg:col-span-7 xl:col-span-8 relative bg-gradient-to-br from-[#2C302E] via-[#242725] to-[#1a1d1b] items-center justify-center p-12 overflow-hidden border-l border-[#3d423f]">
        <!-- Ornamen Visual Background -->
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#9CB4A6_1px,transparent_1px)] [background-size:24px_24px]"></div>
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-[#9CB4A6]/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-[#E3D5CA]/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-xl text-center text-[#FAF9F5]">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#9CB4A6]/20 border border-[#9CB4A6]/40 text-[#9CB4A6] text-xs font-semibold tracking-wider uppercase mb-6">
                <span>✦</span> Specialty Coffee Platform
            </div>
            
            <h2 class="font-serif text-4xl xl:text-5xl font-bold tracking-tight leading-tight text-[#FAF9F5] mb-6">
                Presisi Sangrai, Kualitas Tanpa Kompromi.
            </h2>
            
            <p class="text-base text-[#E3D5CA] leading-relaxed mb-8">
                Kelola katalog single origin, pantau pergerakan batch sangrai secara akurat, proses pesanan ritel & kemitraan wholesale dalam satu panel kerja terpadu.
            </p>

            <div class="grid grid-cols-3 gap-4 pt-6 border-t border-white/10 text-left">
                <div class="p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                    <div class="text-xs text-[#9CB4A6] uppercase">Origin Terdaftar</div>
                    <div class="text-xl font-bold text-[#E3D5CA] font-serif mt-1">4 Wilayah</div>
                </div>
                <div class="p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                    <div class="text-xs text-[#a89f91] uppercase">Ledger Stok</div>
                    <div class="text-xl font-bold text-[#e89d75] font-serif mt-1">Real-time</div>
                </div>
                <div class="p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                    <div class="text-xs text-[#a89f91] uppercase">Akses Panel</div>
                    <div class="text-xl font-bold text-[#e89d75] font-serif mt-1">Role Based</div>
                </div>
            </div>
        </div>
    </div>
</div>
