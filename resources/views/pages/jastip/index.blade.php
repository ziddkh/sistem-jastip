<x-app title="Jastip">
  <x-page-heading>
    <x-page-title>Kelola Jastip</x-page-title>
    <section class="section">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h5 class="card-title">
            Data Jastip
          </h5>
          <a href="{{ route('jastip.create') }}" class="btn btn-sm btn-primary">
            Tambah Data
          </a>
        </div>
        <div class="card-body">
          <table class="table" id="jastipTable">
            <thead>
              <tr>
                <th>No.</th>
                <th>Nama Penerima</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </x-page-heading>
  <x-modal id="detail" title="Detail Jastip" label="detailJastip">
    <!-- Header with name and date -->
    <div class="mb-4 pb-3 border-bottom">
      <h4 class="mb-1" id="recipientName">-</h4>
      <span class="text-primary" id="createdDate">-</span>
    </div>

    <!-- Package Details Title -->
    <h6 class="mb-3 fw-bold">Detail Paket</h6>

    <div class="table-responsive">
      <table id="detailTable" class="table">
        <thead>
          <tr class="text-muted small text-uppercase">
            <th style="width: 50px;">#</th>
            <th>Nomor Resi</th>
            <th class="text-center">Berat (kg)</th>
            <th class="text-center">Dimensi (cm)</th>
            <th class="text-center">Kubikasi (kg)</th>
          </tr>
        </thead>
        <tbody id="detailTableBody">
        </tbody>
        <tfoot>
          <tr class="bg-light border-top">
            <td></td>
            <td class="fw-bold text-primary">TOTAL</td>
            <td class="text-center fw-bold" id="totalWeight">-</td>
            <td class="text-center">-</td>
            <td class="text-center fw-bold text-primary" id="totalCubicWeight">-</td>
          </tr>
        </tfoot>
      </table>
    </div>

    <x-slot:footer>
      <a href="#" id="editOrderBtn" class="btn btn-outline-secondary">
        <i class="bi bi-pencil me-1"></i> Edit Order
      </a>
      <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
        Tutup
      </button>
    </x-slot:footer>
  </x-modal>
  <x-modal-form id="delete" title="Hapus Jastip" label="deleteJastip" variant="bg-danger" btn="danger">
    <p class="mb-0">Apakah Anda yakin ingin menghapus data jastip ini?</p>
    <p class="text-muted small">Data yang sudah dihapus tidak dapat dikembalikan.</p>
  </x-modal-form>
  <x-slot:script>
    <script>
      const DATA_URL = "{{ route('jastip.getData') }}"
    </script>
    <script type="module" src="{{ asset('js/jastip.js') }}"></script>
  </x-slot:script>
</x-app>