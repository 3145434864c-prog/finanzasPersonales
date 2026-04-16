<x-filament-widgets::widget class="dashboard-header-widget">
    <div class="bg-white/80 dark:bg-gradient-to-r dark:from-slate-900/95 dark:to-purple-900 backdrop-blur-sm dark:backdrop-blur-none text-gray-900 dark:text-white p-6 rounded-xl shadow-lg shadow-black/10 dark:shadow-2xl dark:shadow-slate-950/40 transition-all duration-500 border border-gray-200/50 dark:border-slate-800/50 hover:shadow-xl hover:shadow-black/20 dark:hover:shadow-2xl dark:hover:shadow-black">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl font-bold mb-2">
                    ¡Hola, {{ $this->getUserName() }}!
                </h1>
                <p class="text-blue-600/80 dark:text-blue-300">
                    Bienvenido de nuevo - {{ now()->format('d') }} de {{ $this->getCurrentMonth() }} {{ now()->year }}
                </p>
            </div>
            
        </div>
    </div>
</x-filament-widgets::widget>
