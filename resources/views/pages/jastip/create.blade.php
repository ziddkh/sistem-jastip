<x-app title="Tambah Jastip">
  <x-page-heading>
    <section class="section">
      <style>
        .input-group-text i.bi {
          display: flex;
          align-items: center;
          justify-content: center;
          line-height: 1;
        }
      </style>
      <form id="createForm" action="{{ route('jastip.store') }}" autocomplete="off">
        @csrf

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center align-items-md-start mb-4 gap-2">
          <div>
            <h2 class="mb-1 text-center text-md-start">Tambah Jastip</h2>
            <p class="text-muted mb-0 text-center text-md-start">Buat permintaan jastip baru untuk pengiriman paket</p>
          </div>
          <div class="d-flex gap-2">
            <a href="{{ route('jastip.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" id="submitButton" class="btn btn-primary">
              <i class="bi bi-send me-1"></i> Simpan
            </button>
          </div>
        </div>

        <!-- Recipient Data Card -->
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="mb-4">
              <i class="bi bi-person-badge text-primary me-2"></i>
              Data Penerima
            </h5>
            <div class="row">
              <div class="col-12 col-md-8">
                <label class="form-label">Nama Penerima</label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                  <input type="text" id="name" class="form-control" name="name"
                    placeholder="Masukkan nama lengkap penerima">
                </div>
                <span class="invalid-feedback"></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Package Data Card -->
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h5 class="mb-0">
                <i class="bi bi-box-seam text-primary me-2"></i>
                Data Paket
              </h5>
              <span class="badge bg-light-primary text-primary" id="itemCount">1 Paket</span>
            </div>

            <!-- Table Header -->
            <div class="row mb-2 d-none d-md-flex align-items-center">
              <div class="col-md-3">
                <small class="text-muted text-uppercase fw-bold">Nomor Resi</small>
              </div>
              <div class="col-md-2">
                <small class="text-muted text-uppercase fw-bold">Berat (kg)</small>
              </div>
              <div class="col-md-3">
                <small class="text-muted text-uppercase fw-bold">Dimensi (cm)</small>
              </div>
              <div class="col-md-2">
                <small class="text-muted text-uppercase fw-bold">Kubikasi (kg)</small>
              </div>
              <div class="col-md-2 text-center">
                <small class="text-muted text-uppercase fw-bold">Aksi</small>
              </div>
            </div>

            <!-- Package Rows -->
            <div id="packagesContainer">
              <div class="package-row mb-3" data-index="1">
                <input type="hidden" name="packages[1][pricing_option]" value="kubikasi">
                <input type="hidden" name="packages[1][price]" id="price-1" value="0">
                <div class="row g-2 align-items-center">
                  <div class="col-12 col-md-3">
                    <div class="input-group">
                      <span class="input-group-text bg-light d-flex align-items-center justify-content-center">
                        <i class="bi bi-upc-scan"></i>
                      </span>
                      <input type="text" class="form-control" name="packages[1][tracking_number]"
                        placeholder="Scan Resi/Input Resi...">
                    </div>
                  </div>
                  <div class="col-6 col-md-2">
                    <div class="input-group">
                      <input type="text" class="form-control weight" id="weight-1"
                        name="packages[1][weight]" placeholder="0.0">
                      <span class="input-group-text bg-light">kg</span>
                    </div>
                  </div>
                  <div class="col-12 col-md-3">
                    <div class="d-flex align-items-center gap-1">
                      <input type="text" class="form-control dimension text-center"
                        id="length-1" name="packages[1][length]" placeholder="P">
                      <span class="text-muted">×</span>
                      <input type="text" class="form-control dimension text-center"
                        id="width-1" name="packages[1][width]" placeholder="L">
                      <span class="text-muted">×</span>
                      <input type="text" class="form-control dimension text-center"
                        id="height-1" name="packages[1][height]" placeholder="T">
                    </div>
                  </div>
                  <div class="col-6 col-md-2">
                    <div class="input-group">
                      <input type="text" class="form-control cubic-weight" id="cubicWeight-1"
                        name="packages[1][cubic_weight]" value="0,00" readonly
                        style="background-color: #f8f9fa;">
                      <span class="input-group-text bg-light">kg</span>
                    </div>
                  </div>
                  <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
                    <!-- First row - no delete button -->
                  </div>
                </div>
              </div>
            </div>

            <!-- Add Package Button -->
            <div class="mt-3">
              <button type="button" class="btn btn-outline-primary w-100 py-3" id="addPackageButton"
                style="border-style: dashed;">
                <i class="bi bi-plus-circle me-2"></i>
                Tambah Paket Lainnya
              </button>
            </div>
          </div>
        </div>
      </form>
    </section>
  </x-page-heading>
  <x-slot:script>
    <script type="module" src="{{ asset('js/jastip-action.js') }}"></script>
  </x-slot:script>
</x-app>