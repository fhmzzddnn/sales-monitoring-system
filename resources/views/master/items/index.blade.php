<x-app-layout>
    <div x-data="itemManagement()">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-medium text-[#1C1B1F]">Items</h1>
            @can('item-create')
            <button @click="openModal()" 
                    class="bg-[#EADDFF] hover:bg-[#D0BCFF] text-[#21005D] font-semibold py-3 px-6 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Item</span>
            </button>
            @endcan
        </div>

        <div class="bg-[#F3EDF7] rounded-[28px] overflow-hidden">
            <div class="p-4 sm:p-8">
                <div class="overflow-x-auto">
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
                </div>
            </div>
        </div>

        <!-- Item Modal -->
        <div x-show="isModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/30 backdrop-blur-[2px]" @click="closeModal()"></div>
            <div class="relative bg-[#FEF7FF] rounded-[28px] shadow-2xl w-full max-w-md p-8 z-10"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 scale-95">
                
                <h3 class="text-2xl font-normal text-[#1C1B1F] mb-6" x-text="modalTitle"></h3>
                
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="group relative">
                        <label class="block text-sm font-medium text-[#49454F] mb-2 px-1">Category</label>
                        <div class="relative">
                            <select x-model="formData.category_id" 
                                    style="background-image: none;"
                                    class="block w-full border border-[#79747E] rounded-xl px-4 py-3 bg-transparent text-[#1C1B1F] focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all appearance-none">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->prefix }})</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-[#49454F]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <p class="text-[10px] text-[#49454F] mt-1 px-1">Item Code will be auto-generated based on Category.</p>
                        <template x-if="errors.category_id"><p class="text-[#B3261E] text-xs mt-1 font-medium" x-text="errors.category_id[0]"></p></template>
                    </div>

                    <div class="group relative">
                        <label class="block text-sm font-medium text-[#49454F] mb-2 px-1">Item Name</label>
                        <input type="text" x-model="formData.name" placeholder="Enter name" class="block w-full border border-[#79747E] rounded-xl px-4 py-3 bg-transparent text-[#1C1B1F] focus:ring-2 focus:ring-[#6750A4] transition-all">
                        <template x-if="errors.name"><p class="text-[#B3261E] text-xs mt-1 font-medium" x-text="errors.name[0]"></p></template>
                    </div>

                    <div class="group relative">
                        <label class="block text-sm font-medium text-[#49454F] mb-2 px-1">Price</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-[#49454F] font-medium text-sm">Rp</span>
                            <input type="number" step="1" x-model="formData.price" placeholder="0" class="block w-full border border-[#79747E] rounded-xl pl-10 pr-4 py-3 bg-transparent text-[#1C1B1F] placeholder:text-[#79747E]/50 focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all">
                        </div>
                        <template x-if="errors.price"><p class="text-[#B3261E] text-xs mt-1 font-medium" x-text="errors.price[0]"></p></template>
                    </div>

                    <div class="flex justify-end gap-3 mt-8">
                        <button type="button" @click="closeModal()" class="text-[#6750A4] font-semibold px-6 py-2.5 rounded-full hover:bg-[#ECE6F0] transition-all">Cancel</button>
                        <button type="submit" class="bg-[#6750A4] text-white font-semibold px-8 py-2.5 rounded-full hover:bg-[#4F378B] transition-all">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function itemManagement() {
            return {
                isModalOpen: false, isEdit: false, modalTitle: '', currentId: null, formData: { category_id: '', name: '', price: '' }, errors: {},
                init() {
                    $('#items-table').DataTable({
                        processing: true, serverSide: true, responsive: true,
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
