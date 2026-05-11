<x-app-layout>
    <div x-data="{ activeTab: 'categories' }">
        <x-m3.page-header title="Pengaturan Sistem" />

        <!-- M3 Tonal Tabs -->
        <div class="flex gap-2 mb-6 bg-[#F3EDF7] p-1.5 rounded-full w-fit">
            <button @click="activeTab = 'categories'" 
                    :class="activeTab === 'categories' ? 'bg-[#EADDFF] text-[#21005D] shadow-sm' : 'text-[#49454F] hover:bg-[#ECE6F0]'"
                    class="px-8 py-2.5 rounded-full text-sm font-semibold transition-all duration-200">
                Kategori
            </button>
            <button @click="activeTab = 'roles'" 
                    :class="activeTab === 'roles' ? 'bg-[#EADDFF] text-[#21005D] shadow-sm' : 'text-[#49454F] hover:bg-[#ECE6F0]'"
                    class="px-8 py-2.5 rounded-full text-sm font-semibold transition-all duration-200">
                Peran
            </button>
        </div>

        <!-- Tab Content: Kategori -->
        <div x-show="activeTab === 'categories'" x-cloak x-data="categoryManagement()" x-init="init()">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-medium text-main">Kelola Kategori</h2>
                @can('setting-manage')
                <button @click="openModal()" class="bg-[#EADDFF] hover:bg-[#D0BCFF] text-[#21005D] font-semibold py-2 px-6 rounded-xl text-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Kategori</span>
                </button>
                @endcan
            </div>
            <x-m3.table-card>
                <table id="categories-table" class="display responsive nowrap w-full">
                    <thead><tr><th>Nama</th><th>Kode</th>@can('setting-manage')<th>Aksi</th>@endcan</tr></thead>
                </table>
            </x-m3.table-card>

            <!-- Modal Kategori -->
            <x-m3.modal show="isModalOpen" close="closeModal()" title="modalTitle" maxWidth="md">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <x-m3.input label="Nama Kategori" model="formData.name" pla ceholder="misal: Elektronik" error="errors.name" />
                    <x-m3.input label="Kode" model="formData.prefix" placeholder="misal: EL" error="errors.prefix" class="uppercase" />
                    
                    <x-m3.modal-actions cancelAction="closeModal()" />
                </form>
            </x-m3.modal>
        </div>

        <!-- Tab Content: Peran -->
        <div x-show="activeTab === 'roles'" x-cloak x-data="roleManagement()" x-init="init()">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-medium text-main">Kelola Peran</h2>
                @can('setting-manage')
                <button @click="openModal()" class="bg-[#EADDFF] hover:bg-[#D0BCFF] text-[#21005D] font-semibold py-2 px-6 rounded-xl text-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Peran</span>
                </button>
                @endcan
            </div>
            
            <x-m3.table-card>
                <table id="roles-table" class="display responsive nowrap w-full">
                    <thead><tr><th>Nama Peran</th><th>Izin</th>@can('setting-manage')<th>Aksi</th>@endcan</tr></thead>
                </table>
            </x-m3.table-card>

            <!-- Modal Peran -->
            <x-m3.modal show="isModalOpen" close="closeModal()" title="modalTitle" maxWidth="md">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <x-m3.input label="Nama Peran" model="formData.name" placeholder="misal: Supervisor" error="errors.name" />
                    
                    <div>
                        <label class="block text-sm font-medium text-[#49454F] mb-2 px-1">Izin</label>
                        <div wire:ignore>
                            <select id="role-permissions" multiple class="block w-full">
                                @foreach($permissions as $permission)
                                <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <template x-if="errors.permissions"><p class="text-[#B3261E] text-xs mt-1 font-medium" x-text="errors.permissions[0]"></p></template>
                    </div>
                    <x-m3.modal-actions cancelAction="closeModal()" />
                </form>
            </x-m3.modal>
        </div>
    </div>

    @push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        .ts-control { border-radius: 12px !important; padding: 10px 16px !important; border: 1px solid #79747E !important; background: transparent !important; }
        .ts-wrapper.multi .ts-control > div { background: #EADDFF !important; color: #21005D !important; border-radius: 8px !important; }
    </style>

    <script>
        function categoryManagement() {
            return genericCrudManager({
                name: 'Kategori',
                entityKey: 'category',
                tableId: '#categories-table',
                dataTableUrl: "{{ route('api.categories.index') }}",
                apiUrl: "{{ url('api/categories') }}",
                defaultFormData: { name: '', prefix: '' },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'prefix', name: 'prefix' },
                    @can('setting-manage')
                    { 
                        data: 'action', name: 'action', orderable: false, searchable: false,
                        render: (data, type, row) => `
                            <div class="flex gap-2">
                                <button onclick="editCategory(${row.id})" class="p-2 text-[#6750A4] hover:bg-[#ECE6F0] rounded-full transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                <button onclick="deleteCategory(${row.id})" class="p-2 text-[#B3261E] hover:bg-[#F9DEDC] rounded-full transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </div>`
                    }
                    @endcan
                ]
            });
        }

        function roleManagement() {
            let tsInstance = null;
            return genericCrudManager({
                name: 'Peran',
                tableId: '#roles-table',
                dataTableUrl: "{{ route('api.roles.index') }}",
                apiUrl: "{{ url('api/roles') }}",
                defaultFormData: { name: '', permissions: [] },
                columns: [
                    { data: 'name', name: 'name' }, 
                    { data: 'permissions_name', name: 'permissions.name', orderable: false },
                    @can('setting-manage')
                    { 
                        data: 'action', name: 'action', orderable: false, searchable: false,
                        render: (data, type, row) => `
                            <div class="flex gap-2">
                                <button onclick="editRole(${row.id})" class="p-2 text-[#6750A4] hover:bg-[#ECE6F0] rounded-full transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                <button onclick="deleteRole(${row.id})" class="p-2 text-[#B3261E] hover:bg-[#F9DEDC] rounded-full transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </div>`
                    }
                    @endcan
                ],
                mapFetchData: (data) => ({
                    name: data.role.name,
                    permissions: data.permissions
                }),
                onOpenModal: (instance, isEdit, data) => {
                    if (!tsInstance) {
                        tsInstance = new TomSelect('#role-permissions', {
                            plugins: ['remove_button'],
                            create: true,
                            onChange: (value) => { 
                                instance.formData.permissions = Array.isArray(value) ? value : (typeof value === 'string' ? value.split(',').filter(v => v) : []); 
                            }
                        });
                    }
                    if (isEdit) {
                        tsInstance.setValue(data.permissions);
                    } else {
                        tsInstance.clear();
                    }
                }
            });
        }

        // Global functions
        function editCategory(id) { window.Alpine.evaluate(document.querySelector('[x-data="categoryManagement()"]'), `openModal(${id})`); }
        function deleteCategory(id) { window.Alpine.evaluate(document.querySelector('[x-data="categoryManagement()"]'), `deleteData(${id})`); }
        function editRole(id) { window.Alpine.evaluate(document.querySelector('[x-data="roleManagement()"]'), `openModal(${id})`); }
        function deleteRole(id) { window.Alpine.evaluate(document.querySelector('[x-data="roleManagement()"]'), `deleteData(${id})`); }
    </script>
    @endpush
</x-app-layout>
