<div>
    <section class="bg-bg min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            {{-- Breadcrumb --}}
            <nav class="mb-6 text-sm">
                <a href="/" class="text-gray-500 hover:text-primary transition">Início</a>
                <span class="text-gray-300 mx-2">/</span>
                <a href="/servicos" class="text-gray-500 hover:text-primary transition">Serviços</a>
                @if($service->category)
                    <span class="text-gray-300 mx-2">/</span>
                    <a href="/servicos/{{ $service->category->slug }}" class="text-gray-500 hover:text-primary transition">{{ $service->category->name }}</a>
                @endif
                <span class="text-gray-300 mx-2">/</span>
                <span class="text-text font-medium">{{ $service->name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2">
                    {{-- Image --}}
                    <div class="aspect-[16/9] bg-gradient-to-br from-secondary/10 to-secondary/5 rounded-card overflow-hidden mb-6">
                        @php
                            $primaryImage = $service->primary_image;
                            $imgSrc = $primaryImage
                                ? (str_starts_with($primaryImage, 'http') ? $primaryImage : \Illuminate\Support\Facades\Storage::disk('minio')->url($primaryImage))
                                : null;
                        @endphp
                        @if($imgSrc)
                            <img src="{{ $imgSrc }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-7xl">{{ $service->category->icon ?? '📦' }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Gallery --}}
                    @if(!empty($service->images) && count($service->images) > 1)
                        <div class="grid grid-cols-4 gap-2 mb-8">
                            @foreach(array_slice($service->images, 1, 4) as $img)
                                @php
                                    $gSrc = str_starts_with($img, 'http') ? $img : \Illuminate\Support\Facades\Storage::disk('minio')->url($img);
                                @endphp
                                <div class="aspect-square rounded-btn overflow-hidden bg-gray-100">
                                    <img src="{{ $gSrc }}" alt="Galeria" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Description --}}
                    <div class="bg-white rounded-card p-6 shadow-sm mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            @if($service->category)
                                <span class="text-xs font-medium text-secondary bg-secondary/10 px-2 py-0.5 rounded-full">{{ $service->category->name }}</span>
                            @endif
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm font-semibold text-text">{{ number_format($service->rating, 1) }}</span>
                            </div>
                        </div>
                        <h1 class="text-2xl font-bold text-text mb-4">{{ $service->name }}</h1>
                        <p class="text-text leading-relaxed">{{ $service->description }}</p>
                    </div>

                    {{-- Supplier Info --}}
                    @if($service->supplierProfile)
                        <div class="bg-white rounded-card p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-text mb-4">Sobre o Fornecedor</h2>
                            <div class="flex items-center gap-4">
                                @if($service->supplierProfile->logo)
                                    @php
                                        $logoSrc = str_starts_with($service->supplierProfile->logo, 'http')
                                            ? $service->supplierProfile->logo
                                            : \Illuminate\Support\Facades\Storage::disk('minio')->url($service->supplierProfile->logo);
                                    @endphp
                                    <img src="{{ $logoSrc }}" alt="{{ $service->supplierProfile->company_name }}" class="w-16 h-16 rounded-full object-cover shrink-0">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-secondary/10 flex items-center justify-center shrink-0">
                                        <span class="text-2xl">🏪</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-text">{{ $service->supplierProfile->company_name }}</p>
                                    @if($service->supplierProfile->city)
                                        <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $service->supplierProfile->city }}@if($service->supplierProfile->state), {{ $service->supplierProfile->state }}@endif
                                        </p>
                                    @endif
                                    @if($service->supplierProfile->description)
                                        <p class="text-sm text-gray-500 mt-2">{{ \Illuminate\Support\Str::limit($service->supplierProfile->description, 120) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div>
                    <div class="bg-white rounded-card p-6 shadow-sm sticky top-24">
                        <div class="text-center mb-6">
                            <p class="text-xs text-gray-400 mb-0.5">a partir de</p>
                            <p class="text-3xl font-bold text-primary">R$ {{ number_format($service->price, 0, ',', '.') }}</p>
                        </div>

                        <div class="space-y-3">
                            <a href="/planejar" class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-btn transition text-center block">
                                Solicitar orçamento
                            </a>
                            <a href="/planejar" class="w-full bg-secondary/10 text-secondary hover:bg-secondary/20 font-semibold py-3 rounded-btn transition text-center block">
                                Planejar com a Celi
                            </a>
                        </div>

                        <div class="border-t border-gray-100 mt-6 pt-6 space-y-2">
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Resposta rápida
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Pagamento seguro
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
