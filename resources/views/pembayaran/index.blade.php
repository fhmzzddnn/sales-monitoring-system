<x-app-layout>
    <div x-data="paymentManagement()">
        <x-m3.page-header title="Pembayaran" 
                          buttonText="Tambah Pembayaran" 
                          buttonAction="openCreateModal()" />

        <!-- Date Filter -->
        <div class="mb-6">
            <div class="w-full max-w-xs group relative">
                <label class="block text-sm font-medium text-[#49454F] mb-2 px-1">Filter Tanggal</label>
                <input type="date" x-model="filterDate" @change="reloadTable()" 
                       class="block w-full border border-[#79747E] rounded-xl px-4 py-3 bg-[#FEF7FF] text-[#1C1B1F] focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all">
            </div>
        </div>

        <x-m3.table-card>
            <table id="payments-table" class="display responsive nowrap w-full">
                <thead>
                    <tr>
                        <th>Kode Pembayaran</th>
                        <th>Kode Penjualan</th>
                        <th>Jumlah Bayar</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
            </table>
        </x-m3.table-card>

        <!-- Create Modal -->
        <x-m3.modal show="isCreateModalOpen" close="closeCreateModal()" title="'Tambah Pembayaran'" maxWidth="md">
            <form @submit.prevent="submitCreate" class="space-y-6">
                <x-m3.select label="Pilih Penjualan" model="createForm.sale_id" @change="updateSaleTotal()" placeholder="Pilih Penjualan">
                    @foreach($availableSales as $sale)
                        <option value="{{ $sale->id }}" data-total="{{ $sale->total_price }}">{{ $sale->code }} ({{ number_format($sale->total_price, 0, ',', '.') }})</option>
                    @endforeach
                </x-m3.select>

                <div x-show="selectedSaleTotal > 0" class="bg-[#ECE6F0] p-4 rounded-2xl">
                    <span class="text-xs text-[#49454F]">Total Tagihan:</span>
                    <div class="text-xl font-bold text-[#1C1B1F]" x-text="formatCurrency(selectedSaleTotal)"></div>
                </div>

                <x-m3.input label="Jumlah Bayar" type="number" model="createForm.amount_paid" prefix="Rp" error="errors.amount_paid" />

                <div class="bg-[#F3EDF7] p-4 rounded-2xl flex justify-between items-center">
                    <span class="text-xs text-[#49454F]">Status Pembayaran:</span>
                    <span :class="createForm.amount_paid == selectedSaleTotal ? 'bg-[#E8DEF8] text-[#1D192B]' : 'bg-[#F9DEDC] text-[#410E0B]'" 
                          class="px-3 py-1 rounded-full text-xs font-semibold"
                          x-text="createForm.amount_paid == selectedSaleTotal ? 'Lunas' : 'Belum Lunas'"></span>
                </div>

                <x-m3.modal-actions cancelAction="closeCreateModal()" saveText="Simpan" />
            </form>
        </x-m3.modal>

        <!-- Detail & Edit Modal -->
        <x-m3.modal show="isDetailModalOpen" close="closeDetailModal()" maxWidth="2xl">
            <!-- Modal Header -->
            <div class="flex justify-between items-start mb-8">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-[#49454F]" x-text="selectedPayment.code"></span>
                    <span class="text-3xl font-bold text-[#1C1B1F]" x-text="formatCurrency(selectedPayment.amount_paid)"></span>
                </div>
                <div>
                    <span :class="{
                        'bg-[#F9DEDC] text-[#410E0B]': selectedPayment.payment_status === 'Belum Lunas',
                        'bg-[#E8DEF8] text-[#1D192B]': selectedPayment.payment_status === 'Lunas'
                    }" class="px-4 py-2 rounded-full text-sm font-semibold" x-text="selectedPayment.payment_status"></span>
                </div>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4 bg-[#F3EDF7] p-4 rounded-2xl">
                    <div>
                        <span class="text-xs text-[#49454F]">Kode Penjualan:</span>
                        <div class="font-bold text-[#1C1B1F]" x-text="selectedSale.code"></div>
                    </div>
                    <div>
                        <span class="text-xs text-[#49454F]">Total Penjualan:</span>
                        <div class="font-bold text-[#1C1B1F]" x-text="formatCurrency(selectedSale.total_price)"></div>
                    </div>
                </div>

                <!-- Sale Items Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="border-b border-[#CAC4D0] text-xs text-[#49454F]">
                            <tr>
                                <th class="py-3 px-1">Item</th>
                                <th class="py-3 px-1">Harga</th>
                                <th class="py-3 px-1 text-right">Qty</th>
                                <th class="py-3 px-1 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-[#1C1B1F]">
                            <template x-for="item in saleItems" :key="item.id">
                                <tr class="border-b border-[#CAC4D0]/30">
                                    <td class="py-4 px-1" x-text="item.item.name"></td>
                                    <td class="py-4 px-1" x-text="formatCurrency(item.price)"></td>
                                    <td class="py-4 px-1 text-right" x-text="item.quantity"></td>
                                    <td class="py-4 px-1 text-right font-semibold" x-text="formatCurrency(item.subtotal)"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Edit Mode -->
                <div x-show="isEditMode" class="bg-[#ECE6F0] p-6 rounded-3xl space-y-4">
                    <x-m3.input label="Jumlah Bayar Baru" type="number" model="editForm.amount_paid" prefix="Rp" error="errors.amount_paid" />
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-[#49454F]">Status Baru:</span>
                        <span :class="editForm.amount_paid == selectedSale.total_price ? 'text-[#1D192B]' : 'text-[#410E0B]'" 
                              class="font-bold"
                              x-text="editForm.amount_paid == selectedSale.total_price ? 'Lunas' : 'Belum Lunas'"></span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t border-[#CAC4D0] pt-6">
                    <template x-if="!isEditMode">
                        <div class="flex justify-between items-center w-full">
                            <button @click="deletePayment()" class="text-[#B3261E] font-semibold px-6 py-2.5 rounded-full hover:bg-[#F9DEDC] transition-all">Hapus</button>
                            <div class="flex gap-3">
                                <button @click="closeDetailModal()" class="text-[#6750A4] font-semibold px-6 py-2.5 rounded-full hover:bg-[#ECE6F0] transition-all">Tutup</button>
                                <button @click="enterEditMode()" x-show="selectedPayment.payment_status !== 'Lunas'" 
                                        class="bg-[#6750A4] text-white font-semibold px-8 py-2.5 rounded-full hover:bg-[#4F378B] shadow-md transition-all">Edit</button>
                            </div>
                        </div>
                    </template>
                    <template x-if="isEditMode">
                        <x-m3.modal-actions cancelAction="isEditMode = false" saveText="Simpan Perubahan" @click="submitEdit()" saveType="button" />
                    </template>
                </div>
            </div>
        </x-m3.modal>
    </div>

    @push('scripts')
    <script>
        function paymentManagement() {
            return {
                filterDate: '',
                isCreateModalOpen: false,
                isDetailModalOpen: false,
                isEditMode: false,
                selectedSaleTotal: 0,
                selectedPayment: {},
                selectedSale: {},
                saleItems: [],
                createForm: { sale_id: '', amount_paid: 0 },
                editForm: { amount_paid: 0 },
                errors: {},

                init() {
                    const self = this;
                    $('#payments-table').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        pageLength: 10,
                        ajax: {
                            url: "{{ route('api.pembayaran.index') }}",
                            data: function(d) {
                                d.date = self.filterDate;
                            }
                        },
                        columns: [
                            { data: 'code', name: 'code' },
                            { data: 'sale_code', name: 'sale.code' },
                            { 
                                data: 'amount_paid', 
                                name: 'amount_paid',
                                render: (data) => this.formatCurrency(data)
                            },
                            { 
                                data: 'payment_status', 
                                name: 'payment_status',
                                render: function(data) {
                                    let color = data === 'Lunas' ? 'bg-[#E8DEF8] text-[#1D192B]' : 'bg-[#F9DEDC] text-[#410E0B]';
                                    return `<span class="${color} px-3 py-1 rounded-full text-xs font-semibold">${data}</span>`;
                                }
                            },
                            { data: 'created_at', name: 'created_at' }
                        ],
                        createdRow: function(row, data) {
                            $(row).addClass('cursor-pointer hover:bg-[#ECE6F0] transition-all');
                            $(row).on('click', () => self.openDetailModal(data.id));
                        }
                    });
                },

                reloadTable() {
                    $('#payments-table').DataTable().ajax.reload();
                },

                formatCurrency(value) {
                    if (value === undefined || value === null || isNaN(value)) return 'Rp 0';
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
                },

                updateSaleTotal() {
                    const select = document.querySelector('select[x-model="createForm.sale_id"]');
                    const selectedOption = select.options[select.selectedIndex];
                    this.selectedSaleTotal = selectedOption ? Number(selectedOption.dataset.total) : 0;
                    this.createForm.amount_paid = this.selectedSaleTotal;
                },

                openCreateModal() {
                    this.createForm = { sale_id: '', amount_paid: 0 };
                    this.selectedSaleTotal = 0;
                    this.errors = {};
                    this.isCreateModalOpen = true;
                },

                closeCreateModal() { this.isCreateModalOpen = false; },

                submitCreate() {
                    fetch("{{ route('api.pembayaran.store') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(this.createForm)
                    }).then(res => res.json()).then(data => {
                        if (data.message) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, confirmButtonColor: '#6750A4' });
                            this.closeCreateModal();
                            this.reloadTable();
                            location.reload(); // To refresh availableSales in dropdown
                        } else {
                            this.errors = data.errors || {};
                        }
                    });
                },

                openDetailModal(id) {
                    fetch(`{{ url('api/pembayaran') }}/${id}`)
                        .then(res => res.json())
                        .then(data => {
                            this.selectedPayment = data.payment;
                            this.selectedSale = data.sale;
                            this.saleItems = data.items;
                            this.isEditMode = false;
                            this.isDetailModalOpen = true;
                        });
                },

                closeDetailModal() { this.isDetailModalOpen = false; },

                enterEditMode() {
                    this.editForm.amount_paid = this.selectedPayment.amount_paid;
                    this.isEditMode = true;
                },

                submitEdit() {
                    fetch(`{{ url('api/pembayaran') }}/${this.selectedPayment.id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(this.editForm)
                    }).then(res => res.json()).then(data => {
                        if (data.message) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, confirmButtonColor: '#6750A4' });
                            this.isEditMode = false;
                            this.openDetailModal(this.selectedPayment.id);
                            this.reloadTable();
                        } else {
                            this.errors = data.errors || {};
                        }
                    });
                },

                deletePayment() {
                    Swal.fire({ title: 'Hapus Pembayaran?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#B3261E' })
                        .then((result) => {
                            if (result.isConfirmed) {
                                fetch(`{{ url('api/pembayaran') }}/${this.selectedPayment.id}`, {
                                    method: 'DELETE',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                }).then(res => res.json()).then(data => {
                                    this.closeDetailModal();
                                    this.reloadTable();
                                    location.reload();
                                });
                            }
                        });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
