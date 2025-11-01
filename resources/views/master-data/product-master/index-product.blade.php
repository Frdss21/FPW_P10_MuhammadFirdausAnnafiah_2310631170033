<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container p-4 mx-auto">
        <div class="overflow-x-auto">

            @if (session('success'))
            <div class="p-4 mb-4 text-green-800 bg-green-200 border border-green-300 rounded-lg">
                {{ session('success') }}
            </div>
            @elseif (session('error'))
            <div class="p-4 mb-4 text-red-800 bg-red-200 border border-red-300 rounded-lg">
                {{ session('error') }}
            </div>
            @endif

            <form method="GET" action="{{ route('product-index') }}" class="mb-4 flex items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="w-1/4 rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <button type="submit" class="ml-2 rounded-lg bg-blue-500 px-4 py-2 text-white shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Cari
                </button>
            </form>

            <a href="{{ route('product-create') }}">
                <button class="px-6 py-4 text-white bg-pink-500 border border-pink-500 rounded-lg shadow-lg hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500">
                    Add product data
                </button>
            </a>

            <a href="{{ route('product-export-excel') }}">
                <button class="px-6 py-4 text-white bg-green-500 border border-green-500 rounded-lg shadow-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Export to Excel
                </button>
            </a>

            <a href="{{ route('product-export-pdf') }}">
                <button class="px-6 py-4 text-white bg-red-500 border border-red-500 rounded-lg shadow-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Export to PDF
                </button>
            </a>

            <a href="{{ route('product-export-jpg') }}">
                <button class="px-6 py-4 text-white bg-yellow-500 border border-yellow-500 rounded-lg shadow-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    Export to JPG
                </button>
            </a>

            <table class="min-w-full border border-collapse border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left text-gray-600 border border-gray-200">ID</th>
                        <th class="px-4 py-2 text-left text-gray-600 border border-gray-200">Product Name</th>

                        @foreach (['unit', 'type', 'information', 'qty', 'producer'] as $column)
                        <th class="px-4 py-2 border border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="capitalize">{{ $column }}</span>
                                <a href="{{ route('product-index', [
                                        'sort' => $column,
                                        'direction' => (request('sort') === $column && request('direction') === 'asc') ? 'desc' : 'asc']) }}"
                                    class="text-gray-500 hover:text-blue-600 text-sm font-semibold">
                                    {{ (request('sort') === $column && request('direction') === 'asc') ? '˅' : '^' }}
                                </a>
                            </div>
                        </th>
                        @endforeach

                        <th class="px-4 py-2 text-left text-gray-600 border border-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $item)
                    <tr class="bg-white">
                        <td class="px-4 py-2 border border-gray-200">{{ $item->id }}</td>
                        <td class="px-4 py-2 border border-gray-200 hover:text-blue-500 hover:underline">
                            <a href="{{ route('product-detail', $item->id) }}">
                                {{ $item->product_name }}
                            </a>
                        </td>
                        <td class="px-4 py-2 border border-gray-200">{{ $item->unit }}</td>
                        <td class="px-4 py-2 border border-gray-200">{{ $item->type }}</td>
                        <td class="px-4 py-2 border border-gray-200">{{ $item->information }}</td>
                        <td class="px-4 py-2 border border-gray-200">{{ $item->qty }}</td>
                        <td class="px-4 py-2 border border-gray-200">{{ $item->producer }}</td>
                        <td class="px-4 py-2 border border-gray-200">
                            <a href="{{ route('product-edit', $item->id) }}" class="px-2 text-blue-600 hover:text-blue-800">Edit</a>
                            <button class="px-2 text-red-600 hover:text-red-800" onclick="confirmDelete('{{ route('product-delete', $item->id) }}')">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-red-600 font-semibold py-4">No products found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- Pagination --}}
            <div class="mt-4">
                {{-- {{ $products->links() }} --}}
                {{ $products->appends(['search' => request('search')])->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('
            success ') }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('
            error ') }}',
            showConfirmButton: true
        });
    </script>
    @endif

    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        function exportToJpg() {
            const table = document.querySelector('.overflow-x-auto');
            const fileName = 'Laporan_Data_Produk.jpg';

            table.prepend(header);

            html2canvas(table, {
                scale: 2
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = fileName;
                link.href = canvas.toDataURL('image/jpeg', 1.0);
                link.click();
                header.remove();
            });
        }
    </script>
    <script>
        function confirmDelete(deleteUrl) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;

                    let csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    let methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>