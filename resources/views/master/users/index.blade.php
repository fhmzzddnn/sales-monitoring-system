<x-app-layout>
    <div x-data="userManagement()" x-init="init()">
        <x-m3.page-header title="Pengguna" 
                          :buttonText="auth()->user()->can('user-create') ? 'Tambah Pengguna' : null" 
                          buttonAction="openModal()" />

        <!-- M3 Surface Container -->
        <x-m3.table-card>
            <table id="users-table" class="display responsive nowrap w-full">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran</th>
                        @if(auth()->user()->can('user-edit') || auth()->user()->can('user-delete'))
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
            </table>
        </x-m3.table-card>

        <!-- M3 Modal / Dialog -->
        <x-m3.modal show="isModalOpen" close="closeModal()" title="modalTitle">
            <form @submit.prevent="submitForm" class="space-y-6">
                <x-m3.input label="Nama Lengkap" model="formData.name" placeholder="Masukkan nama" error="errors.name" />
                <x-m3.input label="Alamat Email" type="email" model="formData.email" placeholder="nama@contoh.com" error="errors.email" />
                <x-m3.input label="Kata Sandi" type="password" model="formData.password" placeholder="••••••••" error="errors.password" />

                <x-m3.select label="Peran Pengguna" model="formData.role" placeholder="Pilih peran" error="errors.role">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </x-m3.select>

                <x-m3.modal-actions cancelAction="closeModal()" />
            </form>
        </x-m3.modal>
    </div>

    @push('scripts')
    <script>
        function userManagement() {
            return genericCrudManager({
                name: 'Pengguna',
                tableId: '#users-table',
                dataTableUrl: "{{ route('api.users.index') }}",
                apiUrl: "{{ url('api/users') }}",
                defaultFormData: { name: '', email: '', password: '', role: '' },
                mapFetchData: (data) => ({
                    name: data.user.name,
                    email: data.user.email,
                    password: '',
                    role: data.role
                }),
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { 
                        data: 'roles_name', 
                        name: 'roles_name',
                        render: (data) => `<span class="bg-[#EADDFF] text-[#21005D] px-3 py-1 rounded-full text-xs font-semibold">${data}</span>`
                    },
                    @if(auth()->user()->can('user-edit') || auth()->user()->can('user-delete'))
                    { 
                        data: 'action', name: 'action', orderable: false, searchable: false,
                        render: (data, type, row) => `
                            <div class="flex gap-2">
                                @can('user-edit')
                                <button onclick="editUser(${row.id})" class="p-2 text-[#6750A4] hover:bg-[#ECE6F0] rounded-full transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                @endcan
                                @can('user-delete')
                                <button onclick="deleteUser(${row.id})" class="p-2 text-[#B3261E] hover:bg-[#F9DEDC] rounded-full transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                @endcan
                            </div>`
                    }
                    @endif
                ]
            });
        }
        function editUser(id) { window.Alpine.evaluate(document.querySelector('[x-data="userManagement()"]'), `openModal(${id})`); }
        function deleteUser(id) { window.Alpine.evaluate(document.querySelector('[x-data="userManagement()"]'), `deleteData(${id})`); }
    </script>
    @endpush
</x-app-layout>
