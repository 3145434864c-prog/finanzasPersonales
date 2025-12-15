<x-filament-widgets::widget>
    @php
        $statusColor = $this->getStatusColor();
        $bgGradient = match($statusColor) {
            'danger' => 'bg-gradient-to-br from-red-600 via-red-700 to-red-900',
            'warning' => 'bg-gradient-to-br from-amber-500 via-orange-600 to-red-800',
            'success' => 'bg-gradient-to-br from-emerald-500 via-green-600 to-teal-800',
            default => 'bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900'
        };
        $accentColor = match($statusColor) {
            'danger' => 'border-red-400/50',
            'warning' => 'border-amber-400/50',
            'success' => 'border-emerald-400/50',
            default => 'border-purple-500/30'
        };
        $iconBg = match($statusColor) {
            'danger' => 'bg-red-400/30',
            'warning' => 'bg-amber-400/30',
            'success' => 'bg-emerald-400/30',
            default => 'bg-purple-500/20'
        };
        $iconColor = match($statusColor) {
            'danger' => 'text-red-200',
            'warning' => 'text-amber-200',
            'success' => 'text-emerald-200',
            default => 'text-purple-300'
        };
        $titleGradient = match($statusColor) {
            'danger' => 'from-red-200 to-pink-300',
            'warning' => 'from-amber-200 to-orange-300',
            'success' => 'from-emerald-200 to-teal-300',
            default => 'from-purple-300 to-pink-300'
        };
        $progressBg = match($statusColor) {
            'danger' => 'from-red-400 to-red-500',
            'warning' => 'from-amber-400 to-orange-500',
            'success' => 'from-emerald-400 to-teal-500',
            default => 'from-purple-500 to-pink-500'
        };
        $glowEffect = match($statusColor) {
            'danger' => 'shadow-red-500/25',
            'warning' => 'shadow-amber-500/25',
            'success' => 'shadow-emerald-500/25',
            default => 'shadow-purple-500/25'
        };
    @endphp

    <div class="relative overflow-hidden {{ $bgGradient }} text-white p-8 rounded-2xl shadow-2xl {{ $accentColor }} ring-4 ring-white/20 ring-offset-4 ring-offset-gray-900 transform hover:scale-105 transition-all duration-300">
        <!-- Background pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>

        <!-- Animated glow effect -->
        <div class="absolute inset-0 rounded-2xl {{ $glowEffect }} animate-pulse opacity-50"></div>

        <div class="relative z-10">
            <!-- Header with icon -->
            <div class="flex items-center justify-center mb-6">
                <div class="p-4 {{ $iconBg }} rounded-full mr-4">
                    <svg class="w-12 h-12 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($statusColor === 'danger')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        @elseif($statusColor === 'warning')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @endif
                    </svg>
                </div>
                <h2 class="text-2xl font-bold bg-gradient-to-r {{ $titleGradient }} bg-clip-text text-transparent">
                    Estado Financiero
                </h2>
            </div>

            <!-- Status message -->
            <div class="text-center mb-8">
                <p class="text-lg font-medium leading-relaxed {{ $statusColor === 'danger' ? 'text-red-200' : ($statusColor === 'warning' ? 'text-yellow-200' : 'text-green-200') }}">
                    {{ $this->getStatusMessage() }}
                </p>
            </div>

            <!-- Stats grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 hover:bg-white/15 transition-all duration-300">
                    <div class="text-center">
                        <div class="text-sm font-medium text-white/80 mb-2">Total Asignado</div>
                        <div class="text-2xl font-bold text-white">
                            S/ {{ number_format($this->getTotalAsignado(), 2) }}
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 hover:bg-white/15 transition-all duration-300">
                    <div class="text-center">
                        <div class="text-sm font-medium text-white/80 mb-2">Total Gastado</div>
                        <div class="text-2xl font-bold text-white">
                            S/ {{ number_format($this->getTotalGastado(), 2) }}
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 hover:bg-white/15 transition-all duration-300">
                    <div class="text-center">
                        <div class="text-sm font-medium text-white/80 mb-2">Diferencia</div>
                        <div class="text-2xl font-bold {{ $statusColor === 'danger' ? 'text-red-300' : ($statusColor === 'warning' ? 'text-yellow-300' : 'text-green-300') }}">
                            S/ {{ number_format($this->getTotalAsignado() - $this->getTotalGastado(), 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress bar -->
            <div class="bg-white/10 rounded-full p-1">
                <div class="bg-gradient-to-r {{ $progressBg }} h-3 rounded-full transition-all duration-500"
                     style="width: {{ $this->getTotalAsignado() > 0 ? min(100, ($this->getTotalGastado() / $this->getTotalAsignado()) * 100) : 0 }}%">
                </div>
            </div>
            <div class="text-center mt-2 text-sm text-white/70">
                Progreso del presupuesto: {{ $this->getTotalAsignado() > 0 ? round(($this->getTotalGastado() / $this->getTotalAsignado()) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
