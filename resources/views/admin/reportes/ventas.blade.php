<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reporte de Ventas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('admin.reportes.ventas') }}" class="flex items-center space-x-4">
                        <label for="filtro" class="font-medium">Filtrar por:</label>
                        <select name="filtro" id="filtro" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onchange="this.form.submit()">
                            <option value="hoy" {{ $filtro === 'hoy' ? 'selected' : '' }}>Hoy</option>
                            <option value="semana" {{ $filtro === 'semana' ? 'selected' : '' }}>Esta Semana</option>
                            <option value="mes" {{ $filtro === 'mes' ? 'selected' : '' }}>Este Mes</option>
                            <option value="todo" {{ $filtro === 'todo' ? 'selected' : '' }}>Todo el tiempo</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="text-xl font-bold">Total de ventas en el periodo: ${{ number_format($totalVentas, 2) }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comprobante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($ventas as $venta)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $venta->tipo_comprobante }} {{ $venta->serie_comprobante }}-{{ $venta->numero_comprobante }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $venta->cliente->razon_social ?? 'General' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${{ number_format($venta->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $ventas->appends(['filtro' => $filtro])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
