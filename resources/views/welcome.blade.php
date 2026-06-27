<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gv-retail - Seleccionar Rol</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-slate-900 flex min-h-screen items-center justify-center p-4 antialiased selection:bg-indigo-500 selection:text-white">

    <div class="w-full max-w-2xl text-center">
        <header class="mb-12">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                GV - <span class="text-indigo-500">Retail</span>
            </h1>
            <p class="mt-3 text-base text-slate-400">
                Selecciona tu perfil para ingresar al sistema
            </p>
        </header>

        <main class="grid gap-6 sm:grid-cols-2">

            <a href="/cajero/dashboard"
                class="group relative flex flex-col items-center justify-center rounded-2xl border border-slate-800 bg-slate-800/50 p-8 text-center transition-all duration-300 hover:-translate-y-1 hover:border-indigo-500/50 hover:bg-slate-800 hover:shadow-2xl hover:shadow-indigo-500/10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900">

                <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-400 transition-colors group-hover:bg-indigo-500 group-hover:text-white">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </div>

                <h2 class="text-xl font-bold text-white">Cajero</h2>
                <p class="mt-2 text-sm text-slate-400">Caja y ventas.</p>

                <span class="mt-6 flex items-center text-xs font-semibold text-indigo-400 group-hover:text-indigo-300">
                    Acceder al punto de venta
                    <i data-lucide="arrow-right" class="w-4 h-4  transition-transform group-hover:translate-x-1"></i>
                </span>
            </a>

            <a href="/admin/dashboard"
                class="group relative flex flex-col items-center justify-center rounded-2xl border border-slate-800 bg-slate-800/50 p-8 text-center transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500/50 hover:bg-slate-800 hover:shadow-2xl hover:shadow-emerald-500/10 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-900">

                <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-400 transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>

                <h2 class="text-xl font-bold text-white">Administrador</h2>
                <p class="mt-2 text-sm text-slate-400">Inventarios, reportes globales.</p>

                <span
                    class="mt-6 flex items-center text-xs font-semibold text-emerald-400 group-hover:text-emerald-300">
                    Panel de control
                    <i data-lucide="arrow-right" class="w-4 h-4  transition-transform group-hover:translate-x-1"></i>
                </span>
            </a>

        </main>

        <footer class="mt-16 text-xs text-slate-600">
            Grupo 3 Sistemas de Información
        </footer>
    </div>

</body>

</html>