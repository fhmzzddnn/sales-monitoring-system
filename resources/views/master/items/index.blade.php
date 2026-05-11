<x-app-layout>
    <div x-data="itemManagement()">
        <x-m3.page-header title="Items" 
                          :buttonText="auth()->user()->can('item-create') ? 'Add Item' : null" 
                          buttonAction="openModal()" />

        <x-m3.table-card>
            <table id="items-table" class="display responsive nowrap w-full">
                <thead>
                    <tr>
                        <th>Item Code</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </x-m3.table-card>

        <!-- Item Modal -->
        <x-m3.modal show="isModalOpen" close="closeModal()" title="modalTitle" maxWidth="md">
            <form @submit.prevent="submitForm" class="space-y-6">
                <x-m3.select label="Category" model="formData.category_id" placeholder="Select Category" error="errors.category_id">
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->prefix }})</option>
                    @endforeach
                </x-m3.select>
                <p class="text-[10px] text-[#49454F] mt-1 px-1">Item Code will be auto-generated based on Category.</p>

                <x-m3.input label="Item Name" model="formData.name" placeholder="Enter name" error="errors.name" />
                
                <x-m3.input label="Price" type="number" model="formData.price" placeholder="0" prefix="Rp" error="errors.price" />

                <x-m3.modal-actions cancelAction="closeModal()" />
            </form>
        </x-m3.modal>
    </div>

    @push('scripts')
    <script>
        function itemManagement() {
            return {
                isModalOpen: false, isEdit: false, modalTitle: '', currentId: null, formData: { category_id: '', name: '', price: '' }, errors: {},
                init() {
                    $('#items-table').DataTable({
                        processing: true, serverSide: true, responsive: true,
                        pageLength: 10,
                        ajax: "{{ route('api.items.index') }}",
                        columns: [
                            { data: 'code', name: 'code' },
                            { data: 'category_name', name: 'category.name' },
                            { data: 'name', name: 'name' },
                            { 
                                data: 'price', 
                                name: 'price',
                                render: function(data) {
                                    return `<span class="font-semibold text-[#1C1B1F]">${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(data)}</span>`;
                                }
                            },
                            { 
                                data: 'action', name: 'action', orderable: false, searchable: false,
                                render: function(data, type, row) {
                                    return `
                                        <div class="flex gap-2">
                                            <button onclick="editItem(${row.id})" class="p-2 text-[#6750A4] hover:bg-[#ECE6F0] rounded-full transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                            <button onclick="deleteItem(${row.id})" class="p-2 text-[#B3261E] hover:bg-[#F9DEDC] rounded-full transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                        </div>`;
                                }
                            }
                        ]
                    });
                },
                openModal(id = null) {
                    this.errors = {};
                    if (id) { this.isEdit = true; this.currentId = id; this.modalTitle = 'Edit Item'; this.fetchItem(id); }
                    else { this.isEdit = false; this.modalTitle = 'New Item'; this.formData = { category_id: '', name: '', price: '' }; this.isModalOpen = true; }
                },
                closeModal() { this.isModalOpen = false; },
                fetchItem(id) {
                    fetch(`{{ url('api/items') }}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.json()).then(data => { this.formData = { category_id: data.item.category_id, name: data.item.name, price: data.item.price }; this.isModalOpen = true; });
                },
                submitForm() {
                    const url = this.isEdit ? `{{ url('api/items') }}/${this.currentId}` : "{{ route('api.items.store') }}";
                    const method = this.isEdit ? 'PUT' : 'POST';
                    fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify(this.formData)
                    }).then(async res => {
                        const data = await res.json();
                        if (res.ok) { Swal.fire({ icon: 'success', title: 'Success', text: data.message, confirmButtonColor: '#6750A4' }); this.closeModal(); $('#items-table').DataTable().ajax.reload(); }
                        else { this.errors = data.errors || {}; }
                    });
                }
            }
        }
        function editItem(id) { window.Alpine.evaluate(document.querySelector('[x-data="itemManagement()"]'), `openModal(${id})`); }
        function deleteItem(id) {
            Swal.fire({ title: 'Delete item?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#B3261E' })
            .then((result) => { if (result.isConfirmed) { fetch(`{{ url('api/items') }}/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } }).then(res => res.json()).then(data => { $('#items-table').DataTable().ajax.reload(); }); } });
        }
    </script>
    @endpush
</x-app-layout>
