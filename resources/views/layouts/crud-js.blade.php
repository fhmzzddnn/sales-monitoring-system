<script>
    /**
     * Generic CRUD Manager for Alpine.js and DataTables
     * Config properties:
     * - name: String (e.g. 'Item')
     * - entityKey: String (optional, defaults to lowercase name)
     * - tableId: String (e.g. '#items-table')
     * - dataTableUrl: String (API route for index)
     * - apiUrl: String (Base API URL for show/store/update/destroy)
     * - defaultFormData: Object
     * - columns: Array (DataTables column definitions)
     * - mapFetchData: Function (optional, to transform data before loading into form)
     * - onOpenModal: Function (optional, hook after modal opens)
     */
    function genericCrudManager(config) {
        return {
            isModalOpen: false,
            isEdit: false,
            modalTitle: '',
            currentId: null,
            formData: { ...config.defaultFormData },
            errors: {},
            
            init() {
                const self = this;
                $(config.tableId).DataTable({
                    retrieve: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    pageLength: 10,
                    stripeClasses: [],
                    ajax: config.dataTableUrl,
                    columns: config.columns,
                    createdRow: function(row, data) {
                        $(row).addClass('cursor-pointer hover:bg-[#ECE6F0] transition-all');
                        $(row).on('click', function(e) {
                            if ($(e.target).closest('button').length) return;
                            self.openModal(data.id);
                        });
                    }
                });
            },
            
            openModal(id = null) {
                this.errors = {};
                if (id) {
                    this.isEdit = true;
                    this.currentId = id;
                    this.modalTitle = `Ubah ${config.name}`;
                    this.fetchData(id);
                } else {
                    this.isEdit = false;
                    this.currentId = null;
                    this.modalTitle = `Tambah ${config.name}`;
                    this.formData = { ...config.defaultFormData };
                    if (config.onOpenModal) config.onOpenModal(this, false);
                    this.isModalOpen = true;
                }
            },
            
            closeModal() { 
                this.isModalOpen = false; 
            },
            
            fetchData(id) {
                fetch(`${config.apiUrl}/${id}`, { 
                    headers: { 'X-Requested-With': 'XMLHttpRequest' } 
                })
                .then(res => res.json())
                .then(data => {
                    if (config.mapFetchData) {
                        this.formData = config.mapFetchData(data);
                    } else {
                        const entityName = (config.entityKey || config.name).toLowerCase();
                        const entityData = data[entityName] || data;
                        this.formData = { ...this.formData, ...entityData };
                    }
                    if (config.onOpenModal) config.onOpenModal(this, true, data);
                    this.isModalOpen = true;
                });
            },
            
            submitForm() {
                const url = this.isEdit ? `${config.apiUrl}/${this.currentId}` : config.apiUrl;
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
                            title: 'Berhasil', 
                            text: data.message, 
                            confirmButtonColor: '#6750A4',
                            customClass: { popup: 'rounded-[28px]' }
                        });
                        this.closeModal();
                        $(config.tableId).DataTable().ajax.reload(null, false);
                    } else { 
                        this.errors = data.errors || {}; 
                    }
                });
            },
            
            deleteData(id) {
                Swal.fire({
                    title: `Hapus ${config.name.toLowerCase()}?`,
                    text: "Tindakan ini tidak dapat dibatalkan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#B3261E',
                    cancelButtonColor: '#79747E',
                    confirmButtonText: 'Hapus',
                    customClass: { popup: 'rounded-[28px]' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`${config.apiUrl}/${id}`, {
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
                                title: 'Terhapus', 
                                text: data.message, 
                                confirmButtonColor: '#6750A4',
                                customClass: { popup: 'rounded-[28px]' }
                            });
                            $(config.tableId).DataTable().ajax.reload(null, false);
                        });
                    }
                });
            }
        };
    }
</script>