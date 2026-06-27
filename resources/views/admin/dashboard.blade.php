<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Tarjetas de resumen -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-lg font-medium text-gray-500">Total Productos</div>
                        <div class="text-3xl font-bold">{{ $totalProductos }}</div>
                        <div class="mt-4">
                            <a href="{{ route('admin.productos.index') }}" class="text-blue-500 hover:underline">Gestionar Productos →</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-lg font-medium text-gray-500">Total Ventas</div>
                        <div class="text-3xl font-bold">{{ $totalVentas }}</div>
                        <div class="mt-4">
                            <a href="{{ route('admin.reportes.ventas') }}" class="text-blue-500 hover:underline">Ver Reportes de Ventas →</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-lg font-medium text-gray-500">Ingresos Totales</div>
                        <div class="text-3xl font-bold">${{ number_format($ingresosTotales, 2) }}</div>
                        <div class="mt-4">
                            <a href="{{ route('admin.reportes.clientes') }}" class="text-blue-500 hover:underline">Ver Top Clientes →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
