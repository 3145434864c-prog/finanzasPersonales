<x-filament-widgets::widget class="dashboard-header-widget">
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-xl shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl font-bold mb-2">
                    ¡Hola, {{ $this->getUserName() }}!
                </h1>
                <p class="text-blue-100">
                    Bienvenido a tu panel de finanzas personales - {{ $this->getCurrentMonth() }} {{ now()->year }}
                </p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3">
                    <div class="text-2xl font-bold">{{ $this->getTotalMovements() }}</div>
                    <div class="text-sm text-blue-100">Movimientos</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3">
                    <div class="text-2xl font-bold">S/ {{ number_format($this->getTotalAssigned(), 0) }}</div>
                    <div class="text-sm text-blue-100">Presupuestado</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3">
                    <div class="text-2xl font-bold">S/ {{ number_format($this->getTotalSpent(), 0) }}</div>
                    <div class="text-sm text-blue-100">Gastado</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3">
                    <div class="text-2xl font-bold {{ $this->getSavingsRate() >= 0 ? 'text-green-300' : 'text-red-300' }}">
                        {{ $this->getSavingsRate() }}%
                    </div>
                    <div class="text-sm text-blue-100">Ahorro</div>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
