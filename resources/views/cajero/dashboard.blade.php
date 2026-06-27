<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" id="dashboard-title">
            {{ __('Punto de Venta - Buscando...') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col space-y-6">
                    <!-- Datos del Cliente -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Datos del Cliente</h3>

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="tipo_documento_radio" value="SIN_DNI" class="form-radio" checked>
                                <span class="ml-2">Sin DNI</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="radio" name="tipo_documento_radio" value="DNI" class="form-radio">
                                <span class="ml-2">Con DNI</span>
                            </label>
                            <label class="inline-flex items-center ml-4">
                                <input type="radio" name="tipo_documento_radio" value="RUC" class="form-radio">
                                <span class="ml-2">Con RUC</span>
                            </label>
                        </div>

                        <div id="client-search-container" class="hidden">
                            <label for="num_documento" class="block text-sm font-medium text-gray-700 mb-1" id="label-num-doc">Número de Documento</label>
                            <div class="flex rounded-md shadow-sm mb-2">
                                <input type="text" id="num_documento" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300">
                                <button type="button" id="btn-search-client" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                                    Buscar
                                </button>
                            </div>
                        </div>

                        <div id="client-info" class="text-sm font-medium text-green-700 bg-green-50 p-2 rounded hidden mt-2">
                            <!-- Nombre/Razón social del cliente encontrado -->
                        </div>
                    </div>

                    <!-- Escáner y Búsqueda -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Lector de Código de Barras</h3>

                    <div id="reader" width="100%" class="mb-4 bg-gray-100 rounded"></div>

                    <div class="flex flex-col space-y-4">
                        <div>
                            <label for="manual-barcode" class="block text-sm font-medium text-gray-700">Ingresar código manualmente</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" id="manual-barcode" name="manual-barcode" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300" placeholder="Ej: 123456789">
                                <button type="button" id="btn-search" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <!-- Carrito de Compras -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col h-full">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Productos en Venta</h3>

                    <div class="flex-grow overflow-auto mb-4 border rounded" style="min-height: 200px;">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cant</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody id="cart-items" class="bg-white divide-y divide-gray-200">
                                <!-- Items will be inserted here -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-auto">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xl font-bold">Total:</span>
                            <span class="text-2xl font-bold text-green-600">S/ <span id="cart-total">0.00</span></span>
                        </div>
                        <button type="button" id="btn-checkout" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50" disabled>
                            Cobrar e Imprimir Comprobante
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- html5-qrcode CDN -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let cart = [];
            let currentClienteId = null;
            const titleEl = document.getElementById('dashboard-title');
            const cartItemsEl = document.getElementById('cart-items');
            const cartTotalEl = document.getElementById('cart-total');
            const btnCheckout = document.getElementById('btn-checkout');
            const manualBarcodeInput = document.getElementById('manual-barcode');
            const btnSearch = document.getElementById('btn-search');

            const radioBtns = document.querySelectorAll('input[name="tipo_documento_radio"]');
            const searchContainer = document.getElementById('client-search-container');
            const numDocumentoInput = document.getElementById('num_documento');
            const btnSearchClient = document.getElementById('btn-search-client');
            const clientInfoDiv = document.getElementById('client-info');
            const labelNumDoc = document.getElementById('label-num-doc');

            // --- Lógica del Cliente ---
            radioBtns.forEach(radio => {
                radio.addEventListener('change', (e) => {
                    const val = e.target.value;
                    currentClienteId = null;
                    clientInfoDiv.classList.add('hidden');
                    numDocumentoInput.value = '';

                    if (val === 'SIN_DNI') {
                        searchContainer.classList.add('hidden');
                    } else {
                        searchContainer.classList.remove('hidden');
                        labelNumDoc.textContent = val === 'DNI' ? 'Número de DNI' : 'Número de RUC';
                    }
                });
            });

            btnSearchClient.addEventListener('click', async () => {
                const tipo_documento = document.querySelector('input[name="tipo_documento_radio"]:checked').value;
                const num_documento = numDocumentoInput.value.trim();

                if (!num_documento) {
                    alert('Por favor ingrese el número de documento');
                    return;
                }

                btnSearchClient.disabled = true;
                btnSearchClient.textContent = '...';

                try {
                    const response = await axios.get(`/cajero/api/search-client?tipo_documento=${tipo_documento}&num_documento=${num_documento}`);
                    if (response.data.success) {
                        currentClienteId = response.data.cliente.id;
                        clientInfoDiv.textContent = `Cliente: ${response.data.cliente.razon_social}`;
                        clientInfoDiv.classList.remove('hidden');
                    }
                } catch (error) {
                    currentClienteId = null;
                    clientInfoDiv.classList.add('hidden');
                    alert(error.response?.data?.message || 'Error al buscar el cliente');
                } finally {
                    btnSearchClient.disabled = false;
                    btnSearchClient.textContent = 'Buscar';
                }
            });

            // --- Escáner de Código de Barras ---
            function onScanSuccess(decodedText, decodedResult) {
                // Detener escaneos múltiples rápidos
                html5QrcodeScanner.pause(true);
                searchProduct(decodedText).finally(() => {
                    setTimeout(() => html5QrcodeScanner.resume(), 1500); // Reanudar después de 1.5s
                });
            }

            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: {width: 250, height: 150} },
                /* verbose= */ false);
            html5QrcodeScanner.render(onScanSuccess);

            // --- Búsqueda Manual ---
            btnSearch.addEventListener('click', () => {
                const barcode = manualBarcodeInput.value.trim();
                if(barcode) {
                    searchProduct(barcode);
                    manualBarcodeInput.value = '';
                }
            });

            manualBarcodeInput.addEventListener('keypress', (e) => {
                if(e.key === 'Enter') {
                    e.preventDefault();
                    btnSearch.click();
                }
            });

            // --- Lógica del POS ---
            async function searchProduct(barcode) {
                titleEl.textContent = `Punto de Venta - Buscando ${barcode}...`;
                try {
                    const response = await axios.get(`/cajero/api/search-product?barcode=${barcode}`);
                    const producto = response.data.producto;
                    titleEl.textContent = `Punto de Venta - Último: ${producto.nombre} (Stock: ${producto.stock})`;
                    addProductToCart(producto);
                } catch (error) {
                    titleEl.textContent = `Punto de Venta - Producto no encontrado`;
                    alert(error.response?.data?.message || 'Error al buscar el producto');
                }
            }

            function addProductToCart(producto) {
                const existingItem = cart.find(item => item.id === producto.id);

                if (existingItem) {
                    if (existingItem.cantidad + 1 > producto.stock) {
                        alert(`Stock insuficiente. Solo quedan ${producto.stock} unidades de ${producto.nombre}.`);
                        return;
                    }
                    existingItem.cantidad++;
                } else {
                    if (producto.stock < 1) {
                        alert(`Stock agotado para ${producto.nombre}.`);
                        return;
                    }
                    cart.push({
                        ...producto,
                        cantidad: 1
                    });
                }

                renderCart();
            }

            function removeFromCart(id) {
                cart = cart.filter(item => item.id !== id);
                renderCart();
            }

            function renderCart() {
                cartItemsEl.innerHTML = '';
                let total = 0;

                cart.forEach(item => {
                    const subtotal = item.precio * item.cantidad;
                    total += subtotal;

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.nombre}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                ${item.cantidad}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">S/ ${parseFloat(item.precio).toFixed(2)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">S/ ${subtotal.toFixed(2)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="window.removeFromCart(${item.id})" class="text-red-600 hover:text-red-900">Quitar</button>
                        </td>
                    `;
                    cartItemsEl.appendChild(tr);
                });

                cartTotalEl.textContent = total.toFixed(2);
                btnCheckout.disabled = cart.length === 0;
            }

            // Exponer al scope global para el onclick del boton Quitar
            window.removeFromCart = removeFromCart;

            // --- Cobrar ---
            btnCheckout.addEventListener('click', async () => {
                if(cart.length === 0) return;

                const tipoDocSeleccionado = document.querySelector('input[name="tipo_documento_radio"]:checked').value;
                if (tipoDocSeleccionado !== 'SIN_DNI' && !currentClienteId) {
                    alert('Debe buscar y seleccionar un cliente si ha marcado "Con DNI" o "Con RUC"');
                    return;
                }

                btnCheckout.disabled = true;
                btnCheckout.textContent = 'Procesando...';

                try {
                    const payload = {
                        productos: cart.map(item => ({ id: item.id, cantidad: item.cantidad }))
                    };

                    if (currentClienteId) {
                        payload.cliente_id = currentClienteId;
                    }

                    const response = await axios.post('/cajero/api/process-sale', payload);

                    if(response.data.success) {
                        alert('Venta registrada correctamente. Descargando comprobante...');

                        // Descargar recibo
                        window.location.href = `/cajero/receipt/${response.data.venta_id}`;

                        // Limpiar carrito
                        cart = [];
                        renderCart();
                        titleEl.textContent = 'Punto de Venta - Listo para nueva venta';
                    }
                } catch (error) {
                    alert(error.response?.data?.message || 'Error al procesar la venta');
                } finally {
                    btnCheckout.disabled = cart.length === 0;
                    btnCheckout.textContent = 'Cobrar e Imprimir Comprobante';
                }
            });
        });
    </script>
</x-app-layout>
