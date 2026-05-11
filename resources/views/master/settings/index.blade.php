<x-app-layout>
    <div x-data="{ activeTab: 'categories' }">
        <x-m3.page-header title="System Settings" />

        <!-- M3 Tonal Tabs -->
        <div class="flex gap-2 mb-6 bg-[#F3EDF7] p-1.5 rounded-full w-fit">
            <button @click="activeTab = 'categories'" 
                    :class="activeTab === 'categories' ? 'bg-[#EADDFF] text-[#21005D] shadow-sm' : 'text-[#49454F] hover:bg-[#ECE6F0]'"
                    class="px-8 py-2.5 rounded-full text-sm font-semibold transition-all duration-200">
                Categories
            </button>
            <button @click="activeTab = 'roles'" 
                    :class="activeTab === 'roles' ? 'bg-[#EADDFF] text-[#21005D] shadow-sm' : 'text-[#49454F] hover:bg-[#ECE6F0]'"
                    class="px-8 py-2.5 rounded-full text-sm font-semibold transition-all duration-200">
                Roles
            </button>
        </div>

        <!-- Tab Content: Categories -->
        <div x-show="activeTab === 'categories'" x-cloak x-data="categoryManagement()" x-init="init()">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-medium text-[#1C1B1F]">Manage Categories</h2>
                <button @click="openModal()" class="bg-[#EADDFF] hover:bg-[#D0BCFF] text-[#21005D] font-semibold py-2 px-6 rounded-xl text-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Category</span>
                </button>
            </div>
            <x-m3.table-card class="p-6 sm:p-8">
                <table id="categories-table" class="display responsive nowrap w-full">
                    <thead><tr><th>Name</th><th>Prefix</th><th>Action</th></tr></thead>
                </table>
            </x-m3.table-card>

            <!-- Category Modal -->
            <x-m3.modal show="isModalOpen" close="closeModal()" title="modalTitle" maxWidth="md">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <x-m3.input label="Category Name" model="formData.name" placeholder="e.g. Electronics" error="errors.name" />
                    <x-m3.input label="Code Prefix" model="formData.prefix" placeholder="e.g. EL" error="errors.prefix" class="uppercase" />
                    
                    <x-m3.modal-actions cancelAction="closeModal()" />
                </form>
            </x-m3.modal>
        </div>

        <!-- Tab Content: Roles -->
        <div x-show="activeTab === 'roles'" x-cloak x-data="roleManagement()" x-init="init()">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-medium text-[#1C1B1F]">Manage Roles</h2>
                <button @click="openModal()" class="bg-[#EADDFF] hover:bg-[#D0BCFF] text-[#21005D] font-semibold py-2 px-6 rounded-xl text-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Role</span>
                </button>
            </div>
            
            <x-m3.table-card class="p-6 sm:p-8">
                <table id="roles-table" class="display responsive nowrap w-full">
                    <thead><tr><th>Role Name</th><th>Permissions</th><th>Action</th></tr></thead>
                </table>
            </x-m3.table-card>

            <!-- Role Modal -->
            <x-m3.modal show="isModalOpen" close="closeModal()" title="modalTitle" maxWidth="md">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <x-m3.input label="Role Name" model="formData.name" placeholder="e.g. Supervisor" error="errors.name" />
                    
                    <div>
                        <label class="block text-sm font-medium text-[#49454F] mb-2 px-1">Permissions</label>
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

    @push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        .ts-control { border-radius: 12px !important; padding: 10px 16px !important; border: 1px solid #79747E !important; background: transparent !important; }
        .ts-wrapper.multi .ts-control > div { background: #EADDFF !important; color: #21005D !important; border-radius: 8px !important; }
    </style>

    <script>
        function categoryManagement() {
            return {
                isModalOpen: false, isEdit: false, modalTitle: '', currentId: null, formData: { name: '', prefix: '' }, errors: {},
                init() {
                    $('#categories-table').DataTable({
                        retrieve: true, processing: true, serverSide: true, responsive: true,
                        pageLength: 10,
                        ajax: "{{ route('api.categories.index') }}",
                        columns: [{ data: 'name', name: 'name' }, { data: 'prefix', name: 'prefix' }, { data: 'action', name: 'action', orderable: false, searchable: false }]
                    });
                },
                openModal(id = null) {
                    this.errors = {};
                    if (id) { this.isEdit = true; this.currentId = id; this.modalTitle = 'Edit Category'; this.fetchCategory(id); }
                    else { this.isEdit = false; this.modalTitle = 'New Category'; this.formData = { name: '', prefix: '' }; this.isModalOpen = true; }
                },
                closeModal() { this.isModalOpen = false; },
                fetchCategory(id) {
                    fetch(`{{ url('api/categories') }}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.json()).then(data => { this.formData = { name: data.category.name, prefix: data.category.prefix }; this.isModalOpen = true; });
                },
                submitForm() {
                    const url = this.isEdit ? `{{ url('api/categories') }}/${this.currentId}` : "{{ route('api.categories.store') }}";
                    const method = this.isEdit ? 'PUT' : 'POST';
                    fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify(this.formData)
                    }).then(async res => {
                        const data = await res.json();
                        if (res.ok) { Swal.fire({ icon: 'success', title: 'Success', text: data.message, confirmButtonColor: '#6750A4' }); this.closeModal(); $('#categories-table').DataTable().ajax.reload(); }
                        else { this.errors = data.errors || {}; }
                    });
                }
            }
        }

        function roleManagement() {
            let tsInstance = null;
            return {
                isModalOpen: false, isEdit: false, modalTitle: '', currentId: null, formData: { name: '', permissions: [] }, errors: {},
                init() {
                    $('#roles-table').DataTable({
                        retrieve: true, processing: true, serverSide: true, responsive: true,
                        pageLength: 10,
                        ajax: "{{ route('api.roles.index') }}",
                        columns: [
                            { data: 'name', name: 'name' }, 
                            { data: 'permissions_name', name: 'permissions.name', orderable: false },
                            { data: 'action', name: 'action', orderable: false, searchable: false }
                        ]
                    });
                },
                initTomSelect() {
                    if (!tsInstance) {
                        tsInstance = new TomSelect('#role-permissions', {
                            plugins: ['remove_button'],
                            create: true,
                            onChange: (value) => { 
                                this.formData.permissions = Array.isArray(value) ? value : (typeof value === 'string' ? value.split(',').filter(v => v) : []); 
                            }
                        });
                    }
                },
                openModal(id = null) {
                    this.errors = {};
                    this.initTomSelect();
                    if (id) { this.isEdit = true; this.currentId = id; this.modalTitle = 'Edit Role'; this.fetchRole(id); }
                    else { this.isEdit = false; this.modalTitle = 'New Role'; this.formData = { name: '', permissions: [] }; tsInstance.clear(); this.isModalOpen = true; }
                },
                closeModal() { this.isModalOpen = false; },
                fetchRole(id) {
                    fetch(`{{ url('api/roles') }}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.json()).then(data => { 
                        this.formData = { name: data.role.name, permissions: data.permissions }; 
                        tsInstance.setValue(data.permissions);
                        this.isModalOpen = true; 
                    });
                },
                submitForm() {
                    const url = this.isEdit ? `{{ url('api/roles') }}/${this.currentId}` : "{{ route('api.roles.store') }}";
                    const method = this.isEdit ? 'PUT' : 'POST';
                    fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify(this.formData)
                    }).then(async res => {
                        const data = await res.json();
                        if (res.ok) { Swal.fire({ icon: 'success', title: 'Success', text: data.message, confirmButtonColor: '#6750A4' }); this.closeModal(); $('#roles-table').DataTable().ajax.reload(); }
                        else { this.errors = data.errors || {}; }
                    });
                }
            }
        }

        // Global functions
        function editCategory(id) { window.Alpine.evaluate(document.querySelector('[x-data="categoryManagement()"]'), `openModal(${id})`); }
        function deleteCategory(id) { 
            Swal.fire({ title: 'Delete category?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#B3261E' })
            .then((result) => { if (result.isConfirmed) { fetch(`{{ url('api/categories') }}/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } }).then(res => res.json()).then(data => { $('#categories-table').DataTable().ajax.reload(); }); } });
        }
        function editRole(id) { window.Alpine.evaluate(document.querySelector('[x-data="roleManagement()"]'), `openModal(${id})`); }
        function deleteRole(id) { 
            Swal.fire({ title: 'Delete role?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#B3261E' })
            .then((result) => { if (result.isConfirmed) { fetch(`{{ url('api/roles') }}/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } }).then(res => res.json()).then(data => { $('#roles-table').DataTable().ajax.reload(); }); } });
        }
    </script>
    @endpush
</x-app-layout>
