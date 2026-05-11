<x-app-layout>
    <div x-data="saleManagement()">
        <x-m3.page-header title="Penjualan" 
                          :buttonText="auth()->user()->can('sale-create') ? 'Tambah Penjualan' : null" 
                          buttonAction="openCreateModal()" />

        <!-- Filter Tanggal -->
        <div class="mb-6">
            <div class="w-full max-w-xs group relative">
                <label class="block text-sm font-medium text-[#49454F] mb-2 px-1">Filter Tanggal</label>
                <input type="date" x-model="filterDate" @change="reloadTable()"
                       class="block w-full border border-[#79747E] rounded-xl px-4 py-3 bg-[#FEF7FF] text-main focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all">
            </div>
        </div>

        <!-- Wadah Tabel -->
        <x-m3.table-card>
            <table id="sales-table" class="display responsive nowrap w-full">
                <thead>
                    <tr>
                        <th>Kode Penjualan</th>
                        <th>Total Harga</th>
                        <th>Status Pembayaran</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
            </table>
        </x-m3.table-card>

        <!-- Modal Tambah Penjualan -->
        <x-m3.modal show="isCreateModalOpen" close="closeCreateModal()" title="'Tambah Penjualan'" maxWidth="2xl">
            <form @submit.prevent="submitCreate" class="space-y-6">
                <div class="space-y-4">
                    <template x-for="(row, index) in createForm.items" :key="index">
                        <div class="grid grid-cols-12 gap-4 items-end bg-[#ECE6F0] p-4 rounded-2xl">
                            <div class="col-span-6 relative">
                                <x-m3.input label="Barang" model="row.search" placeholder="Cari Kode atau Nama Barang..." 
                                            @focus="row.showDropdown = true" 
                                            @input="row.showDropdown = true; row.item_id = ''; row.highlightedIndex = -1" 
                                            @keydown.down.prevent="moveHighlight(row, 'down')"
                                            @keydown.up.prevent="moveHighlight(row, 'up')"
                                            @keydown.enter.prevent="selectHighlighted(row)"
                                            @click.away="row.showDropdown = false" />
                                <div x-show="row.showDropdown && getFilteredItems(row).length > 0" 
                                     class="absolute z-50 w-full bg-white border border-[#CAC4D0] rounded-xl shadow-lg mt-1 overflow-hidden">
                                    <template x-for="(item, i) in getFilteredItems(row)" :key="item.id">
                                        <div @click="selectItem(row, item)" 
                                             :class="row.highlightedIndex === i ? 'bg-[#EADDFF]' : 'hover:bg-[#F3EDF7]'"
                                             class="px-4 py-3 cursor-pointer transition-colors border-b border-[#CAC4D0]/30 last:border-0">
                                            <div class="font-semibold text-sm text-main" x-text="item.code"></div>
                                            <div class="text-xs text-[#49454F]" x-text="item.name"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="col-span-2">
                                <x-m3.input label="Jumlah" type="number" model="row.quantity" @input="calculateSubtotal(row)" min="1" />
                            </div>
                            <div class="col-span-3 text-right">
                                <label class="block text-xs font-medium text-[#49454F] mb-1">Subtotal</label>
                                <div class="text-sm font-semibold text-main py-2" x-text="formatCurrency(row.subtotal)"></div>
                            </div>
                            <div class="col-span-1 flex justify-center pb-2">
                                <button type="button" @click="removeCreateRow(index)" class="text-[#B3261E] hover:bg-[#F9DEDC] p-2 rounded-full transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <button type="button" @click="addCreateRow()" class="text-[#6750A4] font-medium flex items-center gap-1 hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Baris
                </button>

                <div class="border-t border-[#CAC4D0] pt-6 flex justify-between items-center">
                    <div>
                        <span class="text-[#49454F] text-sm">Total Keseluruhan:</span>
                        <div class="text-2xl font-bold text-[#6750A4]" x-text="formatCurrency(calculateGrandTotal())"></div>
                    </div>
                    <x-m3.modal-actions cancelAction="closeCreateModal()" saveText="Simpan" class="mt-0" />
                </div>
            </form>
        </x-m3.modal>

        <!-- Modal Detail & Edit -->
        <x-m3.modal show="isDetailModalOpen" close="closeDetailModal()" maxWidth="2xl">
            <!-- Header Modal -->
            <div class="flex justify-between items-start mb-8">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-[#49454F]" x-text="selectedSale.code"></span>
                    <span class="text-3xl font-bold text-main" x-text="formatCurrency(selectedSale.total_price)"></span>
                </div>
                <div>
                    <span :class="{
                        'bg-[#F9DEDC] text-[#410E0B]': selectedSale.payment_status === 'Belum Dibayar',
                        'bg-[#FEF7FF] text-[#6750A4] border border-[#6750A4]': selectedSale.payment_status === 'Dibayar Sebagian',
                        'bg-[#E8DEF8] text-[#1D192B]': selectedSale.payment_status === 'Sudah Dibayar'
                    }" class="px-4 py-2 rounded-full text-sm font-semibold" x-text="selectedSale.payment_status"></span>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Tabel Tampilan Detail -->
                <div x-show="!isEditMode">
                    <table class="w-full text-left">
                        <thead class="border-b border-[#CAC4D0] text-xs text-[#49454F]">
                            <tr>
                                <th class="py-3 px-1">Kode Barang</th>
                                <th class="py-3 px-1">Nama Barang</th>
                                <th class="py-3 px-1">Harga</th>
                                <th class="py-3 px-1 text-right">Jumlah</th>
                                <th class="py-3 px-1 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-main">
                            <template x-for="item in saleItems" :key="item.id">
                                <tr class="border-b border-[#CAC4D0]/30">
                                    <td class="py-4 px-1" x-text="item.item.code"></td>
                                    <td class="py-4 px-1" x-text="item.item.name"></td>
                                    <td class="py-4 px-1" x-text="formatCurrency(item.price)"></td>
                                    <td class="py-4 px-1 text-right" x-text="item.quantity"></td>
                                    <td class="py-4 px-1 text-right font-semibold" x-text="formatCurrency(item.subtotal)"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Formulir Tampilan Edit -->
                <div x-show="isEditMode" class="space-y-4">
                    <template x-for="(row, index) in editForm.items" :key="index">
                        <div class="grid grid-cols-12 gap-4 items-end bg-[#ECE6F0] p-4 rounded-2xl">
                            <div class="col-span-6 relative">
                                <x-m3.input label="Barang" model="row.search" placeholder="Cari Kode atau Nama Barang..." 
                                            @focus="row.showDropdown = true" 
                                            @input="row.showDropdown = true; row.item_id = ''; row.highlightedIndex = -1" 
                                            @keydown.down.prevent="moveHighlight(row, 'down')"
                                            @keydown.up.prevent="moveHighlight(row, 'up')"
                                            @keydown.enter.prevent="selectHighlighted(row)"
                                            @click.away="row.showDropdown = false" />
                                <div x-show="row.showDropdown && getFilteredItems(row).length > 0" 
                                     class="absolute z-50 w-full bg-white border border-[#CAC4D0] rounded-xl shadow-lg mt-1 overflow-hidden">
                                    <template x-for="(item, i) in getFilteredItems(row)" :key="item.id">
                                        <div @click="selectItem(row, item)" 
                                             :class="row.highlightedIndex === i ? 'bg-[#EADDFF]' : 'hover:bg-[#F3EDF7]'"
                                             class="px-4 py-3 cursor-pointer transition-colors border-b border-[#CAC4D0]/30 last:border-0">
                                            <div class="font-semibold text-sm text-main" x-text="item.code"></div>
                                            <div class="text-xs text-[#49454F]" x-text="item.name"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="col-span-2">
                                <x-m3.input label="Jumlah" type="number" model="row.quantity" @input="calculateSubtotal(row)" min="1" />
                            </div>
                            <div class="col-span-3 text-right">
                                <label class="block text-xs font-medium text-[#49454F] mb-1">Subtotal</label>
                                <div class="text-sm font-semibold text-main py-2" x-text="formatCurrency(row.subtotal)"></div>
                            </div>
                            <div class="col-span-1 flex justify-center pb-2">
                                <button type="button" @click="removeEditRow(index)" class="text-[#B3261E] hover:bg-[#F9DEDC] p-2 rounded-full transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="addEditRow()" class="text-[#6750A4] font-medium flex items-center gap-1 hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Baris
                    </button>
                </div>

                <!-- Aksi Footer -->
                <div class="border-t border-[#CAC4D0] pt-6 flex justify-end gap-3">
                    <template x-if="!isEditMode">
                        <div class="flex gap-3 w-full justify-between items-center">
                            @can('sale-delete')
                            <button type="button" @click="deleteSale()" x-show="selectedSale.payment_status === 'Belum Dibayar'"
                                    class="text-[#B3261E] font-semibold px-6 py-2.5 rounded-full hover:bg-[#F9DEDC] transition-all">Hapus</button>
                            @else
                            <div></div>
                            @endcan
                            <div class="flex gap-3">
                                <button type="button" @click="closeDetailModal()" class="text-[#6750A4] font-semibold px-6 py-2.5 rounded-full hover:bg-[#ECE6F0] transition-all">Tutup</button>
                                @can('sale-edit')
                                <button type="button" @click="enterEditMode()" x-show="selectedSale.payment_status === 'Belum Dibayar'"
                                        class="bg-[#6750A4] text-white font-semibold px-8 py-2.5 rounded-full hover:bg-[#4F378B] transition-all shadow-md">Edit</button>
                                @endcan
                            </div>
                        </div>
                    </template>
                    <template x-if="isEditMode">
                        <div class="flex gap-3 w-full justify-between items-center">
                            <div>
                                <span class="text-[#49454F] text-xs">Total Baru:</span>
                                <div class="text-xl font-bold text-[#6750A4]" x-text="formatCurrency(calculateEditGrandTotal())"></div>
                            </div>
                            <x-m3.modal-actions cancelAction="isEditMode = false" saveText="Simpan Perubahan" @click="submitEdit()" saveType="button" class="mt-0" />
                        </div>
                    </template>
                </div>
            </div>
        </x-m3.modal>
    </div>

    @push('scripts')
    <script>
        function saleManagement() {
            return {
                filterDate: '',
                isCreateModalOpen: false,
                isDetailModalOpen: false,
                isEditMode: false,
                itemsList: @json($items),
                createForm: { items: [] },
                editForm: { items: [] },
                selectedSale: {},
                saleItems: [],

                init() {
                    this.initTable();
                },

                initTable() {
                    const self = this;
                    $('#sales-table').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        pageLength: 10,
                        stripeClasses: [],
                        ajax: {
                            url: "{{ route('api.penjualan.index') }}",
                            data: function(d) {
                                d.date = self.filterDate;
                            }
                        },
                        columns: [
                            { data: 'code', name: 'code' },
                            { 
                                data: 'total_price', 
                                name: 'total_price',
                                render: function(data) {
                                    return `<span class="font-semibold text-main">${self.formatCurrency(data)}</span>`;
                                }
                            },
                            { 
                                data: 'payment_status', 
                                name: 'payment_status',
                                render: function(data) {
                                    let color = 'text-[#410E0B] bg-[#F9DEDC]';
                                    if (data === 'Dibayar Sebagian') color = 'text-[#6750A4] border border-[#6750A4]';
                                    if (data === 'Sudah Dibayar') color = 'text-[#1D192B] bg-[#E8DEF8]';
                                    return `<span class="px-3 py-1 rounded-full text-xs font-medium ${color}">${data}</span>`;
                                }
                            },
                            { data: 'created_at', name: 'created_at' }
                        ],
                        order: [[3, 'desc']],
                        createdRow: function(row, data, dataIndex) {
                            $(row).on('click', () => self.openDetailModal(data.id));
                        }
                    });
                },

                reloadTable() {
                    $('#sales-table').DataTable().ajax.reload();
                },

                formatCurrency(value) {
                    if (value === undefined || value === null || isNaN(value)) return 'Rp 0';
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
                },

                // Logika Tambah
                openCreateModal() {
                    this.createForm.items = [{ item_id: '', search: '', showDropdown: false, highlightedIndex: -1, quantity: 1, price: 0, subtotal: 0 }];
                    this.isCreateModalOpen = true;
                },
                closeCreateModal() { this.isCreateModalOpen = false; },
                addCreateRow() {
                    this.createForm.items.push({ item_id: '', search: '', showDropdown: false, highlightedIndex: -1, quantity: 1, price: 0, subtotal: 0 });
                },
                removeCreateRow(index) {
                    if (this.createForm.items.length > 1) this.createForm.items.splice(index, 1);
                },
                getFilteredItems(row) {
                    if (!row.search) return this.itemsList.slice(0, 5);
                    const search = row.search.toLowerCase();
                    return this.itemsList
                        .filter(i => i.name.toLowerCase().includes(search) || i.code.toLowerCase().includes(search))
                        .slice(0, 5);
                },
                selectItem(row, item) {
                    row.item_id = item.id;
                    row.search = `${item.code} - ${item.name}`;
                    row.price = Number(item.price);
                    row.showDropdown = false;
                    row.highlightedIndex = -1;
                    this.calculateSubtotal(row);
                },
                moveHighlight(row, direction) {
                    const filtered = this.getFilteredItems(row);
                    if (filtered.length === 0) return;
                    
                    if (direction === 'down') {
                        row.highlightedIndex = (row.highlightedIndex + 1) % filtered.length;
                    } else if (direction === 'up') {
                        row.highlightedIndex = (row.highlightedIndex - 1 + filtered.length) % filtered.length;
                    }
                    row.showDropdown = true;
                },
                selectHighlighted(row) {
                    const filtered = this.getFilteredItems(row);
                    if (row.highlightedIndex >= 0 && row.highlightedIndex < filtered.length) {
                        this.selectItem(row, filtered[row.highlightedIndex]);
                    } else if (filtered.length > 0) {
                        this.selectItem(row, filtered[0]);
                    }
                },
                calculateSubtotal(row) {
                    row.subtotal = Number(row.price) * Number(row.quantity);
                },
                calculateGrandTotal() {
                    return this.createForm.items.reduce((sum, row) => sum + (Number(row.subtotal) || 0), 0);
                },
                submitCreate() {
                    fetch("{{ route('api.penjualan.store') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ items: this.createForm.items })
                    }).then(res => res.json()).then(data => {
                        if (data.message) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, confirmButtonColor: '#6750A4' });
                            this.closeCreateModal();
                            this.reloadTable();
                        } else if (data.errors) {
                            let errorMessages = Object.values(data.errors).flat().join('\n');
                            Swal.fire({ icon: 'error', title: 'Error', text: errorMessages });
                        }
                    });
                },

                // Logika Detail & Edit
                openDetailModal(id) {
                    fetch(`{{ url('api/penjualan') }}/${id}`)
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal mengambil data');
                            return res.json();
                        })
                        .then(data => {
                            this.selectedSale = data.sale || {};
                            this.saleItems = data.items || [];
                            this.isEditMode = false;
                            this.isDetailModalOpen = true;
                        })
                        .catch(err => {
                            Swal.fire({ icon: 'error', title: 'Error', text: err.message });
                        });
                },
                closeDetailModal() { 
                    this.isDetailModalOpen = false;
                },
                enterEditMode() {
                    this.editForm.items = this.saleItems.map(si => {
                        const item = this.itemsList.find(i => i.id == si.item_id);
                        return {
                            item_id: si.item_id,
                            search: item ? `${item.code} - ${item.name}` : '',
                            showDropdown: false,
                            highlightedIndex: -1,
                            quantity: si.quantity,
                            price: Number(si.price),
                            subtotal: Number(si.subtotal)
                        };
                    });
                    this.isEditMode = true;
                },
                addEditRow() {
                    this.editForm.items.push({ item_id: '', search: '', showDropdown: false, highlightedIndex: -1, quantity: 1, price: 0, subtotal: 0 });
                },
                removeEditRow(index) {
                    if (this.editForm.items.length > 1) this.editForm.items.splice(index, 1);
                },
                calculateEditGrandTotal() {
                    return this.editForm.items.reduce((sum, row) => sum + (Number(row.subtotal) || 0), 0);
                },
                submitEdit() {
                    fetch(`{{ url('api/penjualan') }}/${this.selectedSale.id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ items: this.editForm.items })
                    }).then(res => res.json()).then(data => {
                        if (data.message) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, confirmButtonColor: '#6750A4' });
                            this.isEditMode = false;
                            this.openDetailModal(this.selectedSale.id);
                            this.reloadTable();
                        }
                    });
                },
                deleteSale() {
                    Swal.fire({ title: 'Hapus Penjualan?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#B3261E' })
                        .then((result) => {
                            if (result.isConfirmed) {
                                fetch(`{{ url('api/penjualan') }}/${this.selectedSale.id}`, {
                                    method: 'DELETE',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                }).then(res => res.json()).then(data => {
                                    this.closeDetailModal();
                                    this.reloadTable();
                                });
                            }
                        });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
