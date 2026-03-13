@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manage User</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola akun Super Admin, Admin dan ISP</p>
        </div>
        <div class="flex flex-wrap gap-2 sm:ml-auto">
            <a href="{{ route('admin.users.template') }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                Download Template
            </a>
            <button type="button" onclick="document.getElementById('importUserModal').classList.remove('hidden')"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all">
                Import CSV
            </button>
            <a href="{{ route('admin.users.export') }}"
                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all">
                Export CSV
            </a>
            <button data-modal-target="addUserModal" data-modal-toggle="addUserModal"
                class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 shadow-sm transition-all">
                <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Tambah User
            </button>
            @if(auth()->user()->role === 'Super Admin')
                <button data-modal-target="clearUsersModal" data-modal-toggle="clearUsersModal"
                    class="px-4 py-2 text-sm font-medium text-red-600 bg-white border-2 border-red-600 rounded-lg hover:bg-red-600 hover:text-white shadow-sm transition-all">
                    Clear Data
                </button>
            @endif
        </div>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari username, ISP name, PIC ISP..."
                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full ps-10 p-2.5">
            </div>
            <select name="area"
                class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 p-2.5">
                <option value="">Semua Area</option>
                <option value="JABODETABEK" {{ request('area') == 'JABODETABEK' ? 'selected' : '' }}>JABODETABEK</option>
                <option value="JABAR" {{ request('area') == 'JABAR' ? 'selected' : '' }}>JABAR</option>
            </select>
            <select name="role"
                class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 p-2.5">
                <option value="">Semua Role</option>
                <option value="Super Admin" {{ request('role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                <option value="ISP" {{ request('role') == 'ISP' ? 'selected' : '' }}>ISP</option>
            </select>
            <button type="submit"
                class="px-4 py-2.5 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900 transition-all">Filter</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-center text-gray-600 dark:text-gray-300">
                <thead class="text-xs font-semibold text-gray-700 uppercase bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 dark:text-gray-300 border-b-2 border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-center">No</th>
                        <th class="px-4 py-3 text-center">Username</th>
                        <th class="px-4 py-3 text-center">Role</th>
                        <th class="px-4 py-3 text-center">PIC ISP</th>
                        <th class="px-4 py-3 text-center">ISP Brand</th>
                        <th class="px-4 py-3 text-center">ISP Name</th>
                        <th class="px-4 py-3 text-center">Area</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($users as $index => $user)
                        <tr
                            class="even:bg-gray-50/50 dark:even:bg-gray-800/50 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all duration-200">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $users->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white font-mono">{{ $user->username }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 rounded text-xs font-medium {{ $user->role === 'Super Admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : ($user->role === 'Admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300') }}">{{ $user->role }}</span>
                            </td>
                            <td class="px-4 py-3">{{ $user->pic_isp ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $user->isp_brand ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $user->isp_name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $user->area ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Edit Icon --}}
                                    <button data-modal-target="editUserModal-{{ $user->user_id }}"
                                        data-modal-toggle="editUserModal-{{ $user->user_id }}"
                                        class="p-1.5 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400 transition-colors"
                                        title="Edit User">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    @if($user->user_id !== auth()->id())
                                        {{-- Delete Icon --}}
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->user_id) }}"
                                            onsubmit="return confirm('Hapus user ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 transition-colors"
                                                title="Hapus User">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        <div id="editUserModal-{{ $user->user_id }}" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-2xl max-h-full">
                                <div
                                    class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit User</h3>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600"
                                            data-modal-hide="editUserModal-{{ $user->user_id }}">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.users.update', $user->user_id) }}">
                                        @csrf @method('PUT')
                                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                                                <input type="text" value="{{ $user->username }}" disabled
                                                    class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed font-mono">
                                                <p class="text-xs text-gray-400 mt-1">Username tidak dapat diubah</p>
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Password
                                                    <span class="text-xs text-gray-400">(kosongkan jika tidak
                                                        ubah)</span></label><input type="password" name="password"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Role
                                                    *</label>
                                                <select name="role" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    <option value="Super Admin" {{ $user->role == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                                                    <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin
                                                    </option>
                                                    <option value="ISP" {{ $user->role == 'ISP' ? 'selected' : '' }}>ISP</option>
                                                </select>
                                            </div>
                                            <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">PIC
                                                    ISP</label><input type="text" name="pic_isp" value="{{ $user->pic_isp }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ISP
                                                    Brand</label><input type="text" name="isp_brand"
                                                    value="{{ $user->isp_brand }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ISP
                                                    Name</label><input type="text" name="isp_name" value="{{ $user->isp_name }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <div><label
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Area</label>
                                                <select name="area"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    <option value="">Pilih Area</option>
                                                    <option value="JABODETABEK" {{ $user->area == 'JABODETABEK' ? 'selected' : '' }}>JABODETABEK</option>
                                                    <option value="JABAR" {{ $user->area == 'JABAR' ? 'selected' : '' }}>JABAR
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="flex items-center p-4 border-t dark:border-gray-700 gap-2">
                                            <button type="submit"
                                                class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">Simpan</button>
                                            <button data-modal-hide="editUserModal-{{ $user->user_id }}" type="button"
                                                class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada data user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Add User Modal --}}
    <div id="addUserModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div
                class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah User Baru</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600"
                        data-modal-hide="addUserModal">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                            <div
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Auto-generated 6 digit</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Username akan otomatis dibuat saat submit</p>
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Password <span
                                    class="text-xs text-gray-400">(default: TIFConnect2026)</span></label><input
                                type="password" name="password"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Kosongkan untuk default"></div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Role *</label>
                            <select name="role" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="ISP">ISP</option>
                                <option value="Admin">Admin</option>
                                <option value="Super Admin">Super Admin</option>
                            </select>
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">PIC
                                ISP</label><input type="text" name="pic_isp"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ISP
                                Brand</label><input type="text" name="isp_brand"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">ISP
                                Name</label><input type="text" name="isp_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <div><label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Area</label>
                            <select name="area"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Pilih Area</option>
                                <option value="JABODETABEK">JABODETABEK</option>
                                <option value="JABAR">JABAR</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center p-4 border-t dark:border-gray-700 gap-2">
                        <button type="submit"
                            class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">Tambah
                            User</button>
                        <button data-modal-hide="addUserModal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Import User Modal --}}
    <div id="importUserModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="relative w-full max-w-md mx-4">
            <div class="bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Import Users</h3>
                    <button type="button" onclick="document.getElementById('importUserModal').classList.add('hidden')"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            for="user_file_input">Pilih File CSV</label>
                        <input name="file"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            id="user_file_input" type="file" accept=".csv" required>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Format file harus .csv sesuai template.</p>
                    </div>
                    <div class="flex items-center p-4 border-t dark:border-gray-700 gap-2">
                        <button type="submit"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-all">Upload
                            & Import</button>
                        <button type="button" onclick="document.getElementById('importUserModal').classList.add('hidden')"
                            class="text-gray-500 bg-white hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Clear Data Modal --}}
    @if(auth()->user()->role === 'Super Admin')
        <div id="clearUsersModal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div
                    class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus
                            SEMUA data ISP/Admin? Super Admin akan tetap dipertahankan. Tindakan ini tidak dapat dibatalkan.
                        </h3>
                        <form action="{{ route('admin.users.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                Ya, Hapus Semua
                            </button>
                            <button data-modal-hide="clearUsersModal" type="button"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection