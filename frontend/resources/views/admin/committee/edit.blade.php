@extends('components.comp_one.layout')

@section('title')
    Admin
@endsection

@section('content')
    <main class="h-full pb-16 overflow-y-auto">
        <div class="container px-6 mx-auto grid">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Edit Data Panitia
            </h2>

            <form action="{{ route('admin.committee.update', ['id' => $user['id']]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Nama Lengkap</span>
                        <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                            <input type="text" name="name"
                                class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                placeholder="Masukkan nama lengkap" value="{{ old('name', $user['name']) }}" />
                            <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </label>

                    <label class="mt-4 block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Email</span>
                        <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                            <input type="email" name="email"
                                class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                placeholder="Masukkan email" value="{{ old('email', $user['email']) }}" />
                            <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </label>

                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Status Akun</span>
                        <div class="flex items-center mt-1">
                            <svg class="w-5 h-5 ml-1 text-gray-500 dark:text-gray-400 flex-shrink-0" aria-hidden="true"
                                fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z">
                                </path>
                            </svg>

                            <select name="acc_status"
                                class="flex-1 block w-full ml-3 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                <option value="1"
                                    {{ old('acc_status', $user['acc_status'] ?? '') == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="2"
                                    {{ old('acc_status', $user['acc_status'] ?? '') == 2 ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                        </div>
                    </label>


                    {{-- <label class="mt-4 block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Password (kosongkan jika tidak ingin ubah)</span>
                    <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                        <input type="password" name="password"
                            class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                            placeholder="Masukkan password baru jika ingin mengubah" />
                        <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                            <svg class="w-5 h-5">...</svg>
                        </div>
                    </div>
                </label> --}}

                    <div class="flex mt-4">
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('admin.committee.index') }}"
                            class="ml-2 px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto hover:border-gray-500">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection
