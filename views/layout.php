<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coolify Resource Lister</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <div class="container mx-auto p-4 sm:p-8 max-w-4xl">
      <header class="text-center mb-8">
        <h1 class="text-4xl font-bold text-blue-600 dark:text-blue-400">
          Coolify Resource Lister
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">
          Fetch and display all resources from your Coolify instance.
        </p>
      </header>

      <div class="text-center mb-8">
        <button
          id="fetch-btn"
          class="bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 transition-transform transform hover:scale-105 disabled:bg-gray-400 disabled:cursor-not-allowed"
        >
          Fetch Resources
        </button>
      </div>

      <div id="results-container">
        <p class="text-center text-gray-500">
          Click the button to fetch your resources.
        </p>
      </div>
    </div>

    <script src="/js/coolify.js"></script>
  </body>
</html>
