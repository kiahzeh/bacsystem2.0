<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bid and Award System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen text-white bg-cover bg-center bg-no-repeat relative" style="background-image: url('/images/buksubg.jpg');">
    <!-- Overlay for readability -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/55 to-black/70"></div>

    <!-- Top Bar -->
    <div class="relative z-10 flex items-center justify-between px-6 py-4">
        <!-- Brand -->
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" alt="Bid and Award System Logo" class="h-10 w-auto">
            <div class="hidden sm:block">
                <h1 class="text-lg font-bold">Bid and Award System</h1>
                <p class="text-xs text-white/70">BukSU Procurement Monitoring</p>
            </div>
        </div>

        <!-- Auth Buttons -->
        <div class="flex space-x-3">
            <a href="/login" class="px-4 py-2 bg-white text-blue-700 rounded-md font-semibold hover:bg-gray-200 transition">Login</a>
            <a href="/register" class="px-4 py-2 bg-blue-600 rounded-md font-semibold hover:bg-blue-700 transition">Create account</a>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="relative z-10 px-6">
        <div class="max-w-6xl mx-auto mt-10 grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div>
                <h2 class="text-4xl md:text-5xl font-extrabold leading-tight">Transparent, efficient procurement for BukSU</h2>
                <p class="mt-4 text-white/80 text-lg">Track purchase requests, manage documents, and streamline awarding — all in one place.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="/login" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 rounded-md font-semibold">Go to Dashboard</a>
                    <a href="/register" class="px-5 py-3 bg-white text-blue-700 hover:bg-gray-200 rounded-md font-semibold">Get Started</a>
                </div>
                <div class="mt-6 flex items-center space-x-4 text-white/70">
                    <img src="{{ asset('images/buksu.png') }}" alt="BukSU" class="h-10 w-auto">
                    <span class="text-sm">Powered by Bukidnon State University</span>
                </div>
            </div>

            <!-- Visual Card -->
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-lg bg-white/10">
                        <p class="text-sm text-white/80">Purchase Requests</p>
                        <p class="text-2xl font-bold">Streamlined</p>
                    </div>
                    <div class="p-4 rounded-lg bg-white/10">
                        <p class="text-sm text-white/80">Documents</p>
                        <p class="text-2xl font-bold">Organized</p>
                    </div>
                    <div class="p-4 rounded-lg bg-white/10">
                        <p class="text-sm text-white/80">Processes</p>
                        <p class="text-2xl font-bold">Tracked</p>
                    </div>
                    <div class="p-4 rounded-lg bg-white/10">
                        <p class="text-sm text-white/80">Notifications</p>
                        <p class="text-2xl font-bold">Instant</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="relative z-10 px-6 mt-16">
        <div class="max-w-6xl mx-auto">
            <h3 class="text-2xl font-bold mb-6">Why use this system?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl">
                    <h4 class="font-semibold">End-to-end Tracking</h4>
                    <p class="mt-2 text-white/80 text-sm">Follow requests from creation to awarding, with transparent status updates.</p>
                </div>
                <div class="p-6 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl">
                    <h4 class="font-semibold">Department & Process Management</h4>
                    <p class="mt-2 text-white/80 text-sm">Keep departments and workflow steps consistent and auditable.</p>
                </div>
                <div class="p-6 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl">
                    <h4 class="font-semibold">Secure Document Handling</h4>
                    <p class="mt-2 text-white/80 text-sm">Upload and review files with access controls and approval history.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative z-10 mt-16 px-6 py-8 text-center text-white/70">
        <p class="text-sm">© {{ date('Y') }} Bukidnon State University — Bid and Award System</p>
    </footer>
</body>
</html>
