<x-app-layout>
    <div x-data="userManagement()">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-medium text-[#1C1B1F]">Users</h1>
            @can('user-create')
            <!-- M3 Extended FAB / Button -->
            <button @click="openModal()" 
                    class="bg-[#EADDFF] hover:bg-[#D0BCFF] text-[#21005D] font-semibold py-3 px-6 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add User</span>
            </button>
            @endcan
        </div>

        <!-- M3 Surface Container -->
        <div class="bg-[#F3EDF7] rounded-[28px] overflow-hidden">
            <div class="p-4 sm:p-8">
                <div class="overflow-x-auto">
                    <table id="users-table" class="display responsive nowrap w-full">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                @if(auth()->user()->can('user-edit') || auth()->user()->can('user-delete'))
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- M3 Modal / Dialog -->
        <div x-show="isModalOpen" 
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4">
            
            <!-- Scrim -->
            <div x-show="isModalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 class="fixed inset-0 bg-black/30 backdrop-blur-[2px]" @click="closeModal()"></div>

            <!-- Modal Surface -->
            <div x-show="isModalOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-[#FEF7FF] rounded-[28px] shadow-2xl w-full max-w-md overflow-hidden z-10 p-6 sm:p-8">
                
                <h3 class="text-2xl font-normal text-[#1C1B1F] mb-6" x-text="modalTitle"></h3>
                
                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- M3 Outlined Text Field -->
                    <div class="group relative">
                        <label class="block text-sm font-medium text-[#49454F] mb-2 px-1">Full Name</label>
                        <input type="text" x-model="formData.name" 
                               placeholder="Enter name"
                               class="block w-full border border-[#79747E] rounded-xl px-4 py-3 bg-transparent text-[#1C1B1F] placeholder:text-[#79747E]/50 focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all">
                        <template x-if="errors.name">
                            <p class="text-[#B3261E] text-xs mt-1 px-1 font-medium" x-text="errors.name[0]"></p>
                        </template>
                    </div>

                    <div class="group relative">
                        <label class="block text-sm font-medium text-[#49454F] mb-2 px-1">Email Address</label>
                        <input type="email" x-model="formData.email" 
                               placeholder="name@example.com"
                               class="block w-full border border-[#79747E] rounded-xl px-4 py-3 bg-transparent text-[#1C1B1F] placeholder:text-[#79747E]/50 focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all">
                        <template x-if="errors.email">
                            <p class="text-[#B3261E] text-xs mt-1 px-1 font-medium" x-text="errors.email[0]"></p>
                        </template>
                    </div>

                    <div class="group relative">
                        <label class="block text-sm font-medium text-[#49454F] mb-2 px-1">Password</label>
                        <input type="password" x-model="formData.password" 
                               placeholder="••••••••"
                               class="block w-full border border-[#79747E] rounded-xl px-4 py-3 bg-transparent text-[#1C1B1F] placeholder:text-[#79747E]/50 focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all">
                        <template x-if="errors.password">
                            <p class="text-[#B3261E] text-xs mt-1 px-1 font-medium" x-text="errors.password[0]"></p>
                        </template>
                    </div>

                    <div class="group relative">
                        <label class="block text-sm font-medium text-[#49454F] mb-2 px-1">User Role</label>
                        <div class="relative">
                            <select x-model="formData.role" 
                                    class="block w-full border border-[#79747E] rounded-xl px-4 py-3 bg-transparent text-[#1C1B1F] focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all appearance-none">
                                <option value="">Select a role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-[#49454F]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <template x-if="errors.role">
                            <p class="text-[#B3261E] text-xs mt-1 px-1 font-medium" x-text="errors.role[0]"></p>
                        </template>
                    </div>

                    <div class="flex justify-end gap-3 mt-8">
                        <button type="button" @click="closeModal()" 
                                class="text-[#6750A4] hover:bg-[#ECE6F0] font-semibold px-6 py-2.5 rounded-full transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-[#6750A4] hover:bg-[#4F378B] text-white font-semibold px-8 py-2.5 rounded-full shadow-sm hover:shadow-md transition-all">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function userManagement() {
            return {
                isModalOpen: false,
                isEdit: false,
                modalTitle: '',
                currentId: null,
                formData: { name: '', email: '', password: '', role: '' },
                errors: {},
                init() {
                    $('#users-table').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        ajax: "{{ route('api.users.index') }}",
                        columns: [
                            { data: 'name', name: 'name' },
                            { data: 'email', name: 'email' },
                            { 
                                data: 'roles_name', 
                                name: 'roles_name',
                                render: function(data) {
                                    return `<span class="bg-[#EADDFF] text-[#21005D] px-3 py-1 rounded-full text-xs font-semibold">${data}</span>`;
                                }
                            },
                            @if(auth()->user()->can('user-edit') || auth()->user()->can('user-delete'))
                            { 
                                data: 'action', 
                                name: 'action', 
                                orderable: false, 
                                searchable: false,
                                render: function(data, type, row) {
                                    let buttons = '<div class="flex gap-2">';
                                    
                                    @can('user-edit')
                                    buttons += `
                                        <button onclick="editUser(${row.id})" class="p-2 text-[#6750A4] hover:bg-[#ECE6F0] rounded-full transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>`;
                                    @endcan

                                    @can('user-delete')
                                    buttons += `
                                        <button onclick="deleteUser(${row.id})" class="p-2 text-[#B3261E] hover:bg-[#F9DEDC] rounded-full transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>`;
                                    @endcan

                                    buttons += '</div>';
                                    return buttons;
                                }
                            }
                            @endif
                        ]
                    });
                },
                openModal(id = null) {
                    this.errors = {};
                    if (id) {
                        this.isEdit = true;
                        this.currentId = id;
                        this.modalTitle = 'Edit User';
                        this.fetchUser(id);
                    } else {
                        this.isEdit = false;
                        this.currentId = null;
                        this.modalTitle = 'New User';
                        this.formData = { name: '', email: '', password: '', role: '' };
                        this.isModalOpen = true;
                    }
                },
                closeModal() { this.isModalOpen = false; },
                fetchUser(id) {
                    fetch(`{{ url('api/users') }}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.json())
                    .then(data => {
                        this.formData = { name: data.user.name, email: data.user.email, password: '', role: data.role };
                        this.isModalOpen = true;
                    });
                },
                submitForm() {
                    const url = this.isEdit ? `{{ url('api/users') }}/${this.currentId}` : "{{ route('api.users.store') }}";
                    const method = this.isEdit ? 'PUT' : 'POST';
                    fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(this.formData)
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (res.ok) {
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Success', 
                                text: data.message, 
                                confirmButtonColor: '#6750A4',
                                customClass: { popup: 'rounded-[28px]' }
                            });
                            this.closeModal();
                            $('#users-table').DataTable().ajax.reload();
                        } else { this.errors = data.errors || {}; }
                    });
                }
            }
        }
        function editUser(id) { window.Alpine.evaluate(document.querySelector('[x-data="userManagement()"]'), `openModal(${id})`); }
        function deleteUser(id) {
            Swal.fire({
                title: 'Delete user?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#B3261E',
                cancelButtonColor: '#79747E',
                confirmButtonText: 'Delete',
                customClass: { popup: 'rounded-[28px]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('api/users') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Deleted', 
                            text: data.message, 
                            confirmButtonColor: '#6750A4',
                            customClass: { popup: 'rounded-[28px]' }
                        });
                        $('#users-table').DataTable().ajax.reload();
                    });
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
