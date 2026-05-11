<x-app-layout>
    <div x-data="itemManagement()" x-init="init()">
        <x-m3.page-header title="Barang" 
                          :buttonText="auth()->user()->can('item-create') ? 'Tambah Barang' : null" 
                          buttonAction="openModal()" />

        <x-m3.table-card>
            <table id="items-table" class="display responsive nowrap w-full">
                <thead>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Kategori</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        @if(auth()->user()->can('item-edit') || auth()->user()->can('item-delete'))
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
            </table>
        </x-m3.table-card>

        <!-- Modal Barang -->
        <x-m3.modal show="isModalOpen" close="closeModal()" title="modalTitle" maxWidth="md">
            <form @submit.prevent="submitForm" class="space-y-6">
                <x-m3.select label="Kategori" model="formData.category_id" placeholder="Pilih Kategori" error="errors.category_id">
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->prefix }})</option>
                    @endforeach
                </x-m3.select>
                <p class="text-[10px] text-[#49454F] mt-1 px-1">Kode Barang akan dibuat otomatis berdasarkan Kategori.</p>

                <x-m3.input label="Nama Barang" model="formData.name" placeholder="Masukkan nama" error="errors.name" />
                
                <x-m3.input label="Harga" type="number" model="formData.price" placeholder="0" prefix="Rp" error="errors.price" />

                <x-m3.modal-actions cancelAction="closeModal()" />
            </form>
        </x-m3.modal>
    </div>

    @push('scripts')
    <script>
        function itemManagement() {
            return genericCrudManager({
                name: 'Barang',
                entityKey: 'item',
                tableId: '#items-table',
                dataTableUrl: "{{ route('api.items.index') }}",
                apiUrl: "{{ url('api/items') }}",
                defaultFormData: { category_id: '', name: '', price: '' },
                columns: [
                    { data: 'code', name: 'code' },
                    { data: 'category_name', name: 'category.name' },
                    { data: 'name', name: 'name' },
                    { 
                        data: 'price', 
                        name: 'price',
                        render: (data) => `<span class="font-semibold text-main">${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(data)}</span>`
                    },
                    @if(auth()->user()->can('item-edit') || auth()->user()->can('item-delete'))
                    { 
                        data: 'action', name: 'action', orderable: false, searchable: false,
                        render: (data, type, row) => `
                            <div class="flex gap-2">
                                @can('item-edit')
                                <button onclick="editItem(${row.id})" class="p-2 text-[#6750A4] hover:bg-[#ECE6F0] rounded-full transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                @endcan
                                @can('item-delete')
                                <button onclick="deleteItem(${row.id})" class="p-2 text-[#B3261E] hover:bg-[#F9DEDC] rounded-full transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                @endcan
                            </div>`
                    }
                    @endif
                ]
            });
        }
        function editItem(id) { window.Alpine.evaluate(document.querySelector('[x-data="itemManagement()"]'), `openModal(${id})`); }
        function deleteItem(id) { window.Alpine.evaluate(document.querySelector('[x-data="itemManagement()"]'), `deleteData(${id})`); }
    </script>
    @endpush
</x-app-layout>
