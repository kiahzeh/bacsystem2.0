<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bid and Award System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-cover bg-center bg-no-repeat min-h-screen flex flex-col items-center text-white"
      style="background-image: url('{{ asset('images/buksubg.jpg') }}');">

    <!-- Centered Logo -->
    <header class="w-full flex flex-col items-center mt-10">
    <img src="{{ asset('images/logo.png') }}" alt="System Logo" class="h-80 w-auto stroke-cyan 500 ">

    </header>
    <header class="w-full flex flex-col items-center mt-10">
    <img src="{{ asset('images/buksu.png') }}" alt="System Logo" class="h-80 w-auto stroke-cyan 500 ">

    </header>

    <!-- Auth Buttons -->
    <div class="absolute top-4 right-6 flex space-x-4">
        <a href="/login" class="px-4 py-2 bg-white text-blue-600 rounded-md font-semibold hover:bg-gray-200 transition">Login</a>
        <a href="/register" class="px-4 py-2 bg-white text-blue-600 rounded-md font-semibold hover:bg-gray-200 transition">Register</a>
    </div>

    <!-- Main Content -->
    <main class="flex flex-col justify-center items-center text-center px-6 mt-10">
        <h1 class="text-4xl font-bold mb-4">Welcome to the Bid and Award System</h1>
        <p class="text-lg max-w-2xl">
            This platform facilitates transparent bidding and awarding processes for our school, ensuring fairness and efficiency in procurement and contracting.
        </p>
    </main>

</body>
</html>
