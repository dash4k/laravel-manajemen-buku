<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <section class="mt-25 lg:mt-20 lg:w-7/9 h-auto w-5/6 mx-auto rounded-sm transition-all duration-300 flex flex-col gap-2" enctype="multipart/form-data">
        <h1 class="font-poppins text-lg lg:text-xl font-bold">Manajemen Buku</h1>
        
        {{-- Input Nilai Container --}}
        <div class="w-full flex flex-row justify-end">
            <button id="addButton" class="w-auto px-4 py-2 bg-blue-200 rounded-md hover:cursor-pointer hover:bg-blue-100" title="Tambah Buku"><i class="fa-solid fa-plus"></i></button>
        </div>

        <div class="rounded-2xl p-4 bg-white w-full h-fit border-x-2 border-y-3 py-6 border-lightGray flex flex-col gap-2 relative overflow-x-auto sm:rounded-lg">
            <table class="table-auto w-full text-center border-collapse border border-blue-300 text-sm break-words">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="border px-4 py-2">Judul</th>
                        <th class="border px-4 py-2">Penulis</th>
                        <th class="border px-4 py-2">Penerbit</th>
                        <th class="border px-4 py-2">Cover</th>
                        <th class="border px-4 py-2">Genre</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Harga</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bukus as $b)
                        <tr class="bukuRow"
                            data-id="{{ $b->id_buku }}"
                            data-judul="{{ $b->judul }}"
                            data-penulis="{{ $b->penulis }}"
                            data-penerbit="{{ $b->penerbit }}"
                            data-genre="{{ $b->genre }}"
                            data-status="{{ $b->status }}"
                            data-harga="{{ $b->harga }}"
                        >
                            <td class="border px-4 py-2 break-words max-w-[200px] capitalize">{{ $b->judul }}</td>
                            <td class="border px-4 py-2 break-words max-w-[200px]">{{ $b->penulis }}</td>
                            <td class="border px-4 py-2 break-words max-w-[250px] capitalize">{{ $b->penerbit }}</td>
                            <td class="border px-4 py-2">
                                <a href="{{ asset('storage/' . $b->cover) }}" target="_blank" class="text-blue-600 hover:cursor-pointer" title="Cover Buku"><i class="fa-solid fa-link"></i></a>
                            </td>
                            @switch($b->genre)
                                @case('fiksi')
                                    <td class="border px-4 py-2"><span>Fiksi</span></i></td>
                                    @break
                                @default
                                    <td class="border px-4 py-2"><span>Non Fiksi</span></td>
                            @endswitch
                            @switch($b->status)
                                @case('tersedia')
                                    <td class="border px-4 py-2"><i class="fa-solid fa-circle-check text-lg" title="Tersedia"></i></td>
                                    @break
                                @default
                                    <td class="border px-4 py-2"><i class="fa-solid fa-circle-xmark text-lg" title="Tidak Tersedia"></i></td>
                            @endswitch
                            <td class="border px-4 py-2">Rp. {{ number_format($b->harga, 2, ',', '.') }}</td>
                            <td class="border px-4 py-2">
                                <button type="button" class="editButton bg-yellow-300 hover:bg-yellow-200 px-2 py-1 rounded hover:cursor-pointer" title="Edit Buku"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button type="button" class="deleteButton bg-red-300 hover:bg-red-200 px-2 py-1 rounded hover:cursor-pointer" title="Hapus Buku"><i class="fa-solid fa-eraser"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Belum ada buku</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div> 
 
        <div id="formContainer" class="fixed inset-0 bg-blue-200/30 backdrop-blur-sm z-100 flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 ease-out w-full h-full pl-5 pr-5 lg:pl-0 lg:pr-0">
            <div class="bg-white p-6 rounded max-w-lg w-full transform transition-all duration-300 ease-in-out shadow-lg shadow-blue-300/50">
                <form id="bukuForm" class="flex flex-col gap-2" action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data" data-default-action="{{ route('buku.store') }}">
                    @csrf
                    <div class="mb-2 flex flex-row justify-center items-start gap-1">
                        <div class="w-1/2">
                            <label for="judul" class="block mb-2 text-sm font-light text-gray-900">Judul Buku</label>
                            <input type="text" id="judul" name="judul" class="border p-2 w-full rounded-md">
                            <p id="judulErrorMessage" class="text-red-500 mt-1 text-xs"></p>
                        </div>
                        <div class="w-1/2">
                            <label for="genre" class="block mb-2 text-sm font-light text-gray-900">Genre</label>
                            <select id="genre" name="genre" class="border p-2 py-3 w-full rounded-md bg-white h-auto">
                            <option value="fiksi">Non-Fiksi</option>
                            <option value="non_fiksi">Fiksi</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2 flex flex-row justify-center items-start gap-1">
                        <div class="w-1/2 grow-0">
                            <label for="penulis" class="block mb-2 text-sm font-light text-gray-900">Penulis</label>
                            <input type="text" id="penulis" name="penulis" class="border p-2 w-full rounded-md">
                            <p id="penulisErrorMessage" class="text-red-500 mt-1 text-xs"></p>
                        </div>
                        <div class="w-1/2 grow-0">
                            <label for="penerbit" class="block mb-2 text-sm font-light text-gray-900">Penerbit</label>
                            <input type="text" id="penerbit" name="penerbit" class="border p-2 w-full rounded-md">
                            <p id="penerbitErrorMessage" class="text-red-500 mt-1 text-xs"></p>
                        </div>
                    </div>
                    <div class="mb-2 flex flex-row justify-center items-start gap-1">
                        <div class="w-1/2">
                            <label for="harga" class="block mb-2 text-sm font-light text-gray-900">Harga</label>
                            <div class="relative w-full">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-money-bill text-gray-400"></i>
                                </span>
                                <input type="number" id="harga" name="harga" class="pl-10 pr-4 py-2 border w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" max="999999999999.99" step="0.01"/>
                            </div>
                        </div>
                        <div class="w-1/2">
                            <label for="status" class="block mb-2 text-sm font-light text-gray-900">Status</label>
                            <select id="status" name="status" class="border p-2 py-3 w-full rounded-md bg-white h-auto">
                            <option value="tersedia">Tersedia</option>
                            <option value="tidak_tersedia">Tidak Tersedia</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="cover" class="block mb-2 text-sm font-light text-gray-900">Cover Buku</label>
                        <input type="file" id="cover" name="cover" class="border p-2 w-full rounded-md">
                        <p class="mt-2 text-xs text-gray-500">JPEG, JPG, atau PNG (MAX. 16 MB).</p>
                        <p id="coverErrorMessage" class="text-red-500 mt-1 text-xs"></p>
                    </div>
                    <div class="flex gap-2 justify-end">
                        <button id="cancelButton" type="button" onclick="hideForm()" class="px-4 py-2 bg-gray-300 hover:bg-gray-200 rounded">Cancel</button>
                        <button id="submitButton" type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-300 text-white rounded">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @vite('resources/js/buku.js')
    @vite('resources/js/alert.js')
    @if ($errors->any() || session('error'))
        <script>
            window.laravelErrors = [];

            @if ($errors->any())
                window.laravelErrors = @json($errors->all());
            @endif

            @if (session('error'))
                window.laravelErrors.push(@json(session('error')));
            @endif
        </script>
    @endif
    @if (session('success'))
        <script>
            window.laravelSuccess = @json(session('success'));
        </script>
    @endif
</body>
</html>