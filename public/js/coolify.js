/**
 * Handles the "Fetch" button click, calls the backend API,
 */
document.addEventListener('DOMContentLoaded', () => {
  const fetchBtn = document.getElementById('fetch-btn');
  const resultsContainer = document.getElementById('results-container');

  /**
   * Creates an HTML string for a colored status indicator dot.
   */
  const getStatusIndicator = (status) => {
    const statusLower = status.toLowerCase();
    let colorClass = 'bg-gray-400'; // Default color
    if (statusLower.includes('running')) {
      colorClass = 'bg-green-500';
    } else if (statusLower.includes('exited') || statusLower.includes('stopped')) {
      colorClass = 'bg-red-500';
    } else if (statusLower.includes('restarting')) {
      colorClass = 'bg-yellow-500';
    }
    return `<span class="h-3 w-3 rounded-full ${colorClass} inline-block mr-2"></span>`;
  };

  /**
   * Renders the fetched data (or an error message) into the results container.
   */
  const renderResults = (data) => {
    // Handle error case
    if (data.error) {
      resultsContainer.innerHTML = `
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
          <p class="font-bold">Error</p>
          <p>${data.error}</p>
        </div>`;
      return;
    }

    // Handle no resources found
    if (data.length === 0) {
      resultsContainer.innerHTML = `
        <p class="text-center text-gray-500 dark:text-gray-400 mt-4">
          ✅ No resources found in your Coolify instance.
        </p>`;
      return;
    }

    // Build the table rows from the data
    const tableRows = data
      .map(
        (resource) => `
          <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
            <td class="py-3 px-4 text-gray-900 dark:text-gray-100">${resource.name}</td>
            <td class="py-3 px-4">
              <a href="${resource.url}" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:underline">
                ${resource.url}
              </a>
            </td>
            <td class="py-3 px-4 capitalize flex items-center">
              ${getStatusIndicator(resource.status)}
              ${resource.status}
            </td>
          </tr>
        `,
      )
      .join('');

    // Construct the final table and inject it into the container
    resultsContainer.innerHTML = `
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
          <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase">
            <tr>
              <th class="py-3 px-4 font-semibold">Name</th>
              <th class="py-3 px-4 font-semibold">URL</th>
              <th class="py-3 px-4 font-semibold">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            ${tableRows}
          </tbody>
        </table>
      </div>
    `;
  };

  /**
   * Event listener for the "Fetch Resources" button.
   */
  fetchBtn.addEventListener('click', async () => {
    fetchBtn.disabled = true;
    fetchBtn.textContent = 'Fetching...';
    resultsContainer.innerHTML = `<p class="text-center text-gray-500">Loading...</p>`;

    try {
      const response = await fetch('/api/resources');
      const data = await response.json();
      renderResults(data);
    } catch (err) {
      renderResults({ error: 'Could not connect to the server. Is it running?' });
    } finally {
      fetchBtn.disabled = false;
      fetchBtn.textContent = 'Fetch Resources';
    }
  });
});
