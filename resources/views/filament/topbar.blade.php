<div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 shadow-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <h1 class="text-lg font-bold">
                ¡Hola, {{ $userName }}! Bienvenido a tu panel de finanzas personales
            </h1>
        </div>
        <div class="text-sm text-blue-100">
            {{ now()->locale('es')->monthName }} {{ now()->year }}
        </div>
    </div>
</div>
