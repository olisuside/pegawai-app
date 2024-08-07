<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')

</head>

<body>
    <div class="m-4 p-4">

        <div class="w-full mx-auto max-w-screen-xl p-4 border border-gray-100 rounded-lg shadow-lg m-4 ">
            <div class="mx-3  mt-0 mb-4 flex md:flex-row flex-col justify-between">

                <h1 class="text-3xl font-bold ">Data Pegawai</h1>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Tambah
                    Pegawai</button>
            </div>
            <div class="">
                <div class="flex items-center md:flex-row flex-col justify-center md:justify-between pb-4">
                    <div class="flex items-center p-2">
                        <label for="entriesPerPage" class="text-sm font-medium text-gray-700">Show</label>
                        <select id="entriesPerPage" class="ml-2 p-1 border border-gray-300 rounded-md">

                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                        </select>
                        <label for="entriesPerPage" class="ml-2 text-sm font-medium text-gray-700">entries</label>
                    </div>
                    <div>
                        <input type="text" id="searchInput" class="ml-2 p-2 border border-gray-300 rounded-md"
                            placeholder="Search...">
                    </div>
                </div>
                <div class="relative overflow-x-auto">

                    <table id="pegawaiTable" class="w-full text-sm text-left text-gray-500 ">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Nama</th>
                                <th class="px-6 py-3">Posisi</th>
                                <th class="px-6 py-3">Tanggal Masuk</th>
                                <th class="px-6 py-3">Foto</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center md:flex-row flex-col justify-center md:justify-between pb-4">
                    <div class="p-2">
                        Showing <span id="currentEntries">0</span> to <span id="totalEntries">0</span> of <span
                            id="totalItems">0</span> entries
                    </div>
                    <div class="flex items-center space-x-2">
                        <button id="prevPage" class="p-2 border border-gray-300 rounded-md">Previous</button>
                        <div id="paginationButtons" class="flex space-x-2">
                        </div>
                        <button id="nextPage" class="p-2 border border-gray-300 rounded-md">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pegawaiData = @json($pegawais); //fetch ddata
            const entriesPerPageSelect = document.getElementById('entriesPerPage');
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('tableBody');
            const currentEntries = document.getElementById('currentEntries');
            const totalEntries = document.getElementById('totalEntries');
            const totalItems = document.getElementById('totalItems');
            const prevPageButton = document.getElementById('prevPage');
            const nextPageButton = document.getElementById('nextPage');
            const paginationButtons = document.getElementById('paginationButtons');

            let currentPage = 1;
            let entriesPerPage = parseInt(entriesPerPageSelect.value);
            let filteredData = [...pegawaiData];

            function formatDate(dateString) {
                const date = new Date(dateString);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day} - ${month} - ${year}`;
            }

            function renderTable(data) {
                tableBody.innerHTML = '';
                const startIndex = (currentPage - 1) * entriesPerPage;
                const endIndex = Math.min(startIndex + entriesPerPage, data.length);

                for (let i = startIndex; i < endIndex; i++) {
                    const row = document.createElement('tr');
                    row.classList.add(i % 2 === 0 ? 'bg-white' : 'bg-gray-50');
                    const formattedDate = formatDate(data[i].tanggal_masuk);
                    row.innerHTML = `
                        <td class="px-6 py-4">${data[i].nama}</td>
                        <td class="px-6 py-4">${data[i].posisi}</td>
                        <td class="px-6 py-4">${formattedDate}</td>
                        <td class="px-6 py-4">${data[i].foto ? `<img src="${data[i].foto}" alt="${data[i].nama}" width="50">` : 'Tidak ada foto'}</td>
                    `;
                    tableBody.appendChild(row);
                }

                currentEntries.textContent = startIndex + 1;
                totalEntries.textContent = endIndex;
                totalItems.textContent = data.length;
                renderPagination(data.length);
            }

            function updatePagination() {
                prevPageButton.disabled = currentPage === 1;
                nextPageButton.disabled = currentPage * entriesPerPage >= filteredData.length;
            }

            function filterData() {
                const searchTerm = searchInput.value.toLowerCase();
                filteredData = pegawaiData.filter(item =>
                    item.nama.toLowerCase().includes(searchTerm) ||
                    item.posisi.toLowerCase().includes(searchTerm) ||
                    item.tanggal_masuk.toLowerCase().includes(searchTerm)
                );
                currentPage = 1;
                renderTable(filteredData);
                updatePagination();
            }

            function renderPagination(totalItems) {
                paginationButtons.innerHTML = '';
                const totalPages = Math.ceil(totalItems / entriesPerPage);
                const maxButtons = 3;
                let startPage = Math.max(currentPage - Math.floor(maxButtons / 2), 1);
                let endPage = Math.min(startPage + maxButtons - 1, totalPages);

                if (endPage - startPage < maxButtons - 1) {
                    startPage = Math.max(endPage - maxButtons + 1, 1);
                }

                for (let i = startPage; i <= endPage; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.textContent = i;
                    pageButton.classList.add('py-2', 'px-4', 'border', 'border-gray-300', 'rounded-md');
                    if (i === currentPage) {
                        pageButton.classList.add('bg-blue-500', 'text-white');
                    } else {
                        pageButton.classList.add('bg-white', 'hover:bg-gray-200');
                    }
                    pageButton.addEventListener('click', () => {
                        currentPage = i;
                        renderTable(filteredData);
                        updatePagination();
                    });
                    paginationButtons.appendChild(pageButton);
                }
            }

            entriesPerPageSelect.addEventListener('change', function() {
                entriesPerPage = parseInt(this.value);
                currentPage = 1;
                renderTable(filteredData);
                updatePagination();
            });

            searchInput.addEventListener('input', function() {
                filterData();
            });

            prevPageButton.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable(filteredData);
                    updatePagination();
                }
            });

            nextPageButton.addEventListener('click', function() {
                if (currentPage * entriesPerPage < filteredData.length) {
                    currentPage++;
                    renderTable(filteredData);
                    updatePagination();
                }
            });

            // Initial render
            renderTable(filteredData);
            updatePagination();
        });
    </script>
</body>

</html>
