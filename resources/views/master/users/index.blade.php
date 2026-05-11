<x-app-layout>
    <div x-data="userManagement()">
        <x-m3.page-header title="Users" 
                          :buttonText="auth()->user()->can('user-create') ? 'Add User' : null" 
                          buttonAction="openModal()" />

        <!-- M3 Surface Container -->
        <x-m3.table-card>
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
        </x-m3.table-card>

        <!-- M3 Modal / Dialog -->
        <x-m3.modal show="isModalOpen" close="closeModal()" title="modalTitle">
            <form @submit.prevent="submitForm" class="space-y-6">
                <x-m3.input label="Full Name" model="formData.name" placeholder="Enter name" error="errors.name" />
                <x-m3.input label="Email Address" type="email" model="formData.email" placeholder="name@example.com" error="errors.email" />
                <x-m3.input label="Password" type="password" model="formData.password" placeholder="••••••••" error="errors.password" />

                <x-m3.select label="User Role" model="formData.role" placeholder="Select a role" error="errors.role">
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
                        pageLength: 10,
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
