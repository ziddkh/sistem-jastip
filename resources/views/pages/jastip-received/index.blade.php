<x-app title="Laporan Jastip">
  <x-slot:style>
    <style>
      .card-input {
        max-width: 500px !important;
        width: 100% !important;
      }

      .input-location-group {
        display: inline-flex !important;
        align-items: center;
        max-width: 500px;
        width: 100%;
        gap: 8px;
      }

      .input-location-group input {
        display: inline !important;
      }

      .input-location-group label {
        display: inline-block !important;
        width: 115px !important;
      }
    </style>
  </x-slot:style>
  <x-page-heading>
    <x-page-title>Laporan Jastip Diterima</x-page-title>
    <section class="section">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h5 class="card-title">
            Data Jastip Diterima
          </h5>
          @if (count($recipients) > 0 && count($packages) > 0)
          <div class="card-input">
            <form id="form-send-location">
              <div class="input-location-group">
                <label for="input-send-location">Dikirim Via:</label>
                <input id="input-send-location" type="text" class="form-control form-control-sm" name="name">
              </div>
              @foreach ($recipients as $recipient)
              <input type="hidden" name="recipients[]" value="{{ $recipient->id }}">
              @endforeach
            </form>
          </div>
          @endif
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table" id="jastipTable">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama Penerima</th>
                  <th>Resi</th>
                  <th>Berat</th>
                  <th>P</th>
                  <th>L</th>
                  <th>T</th>
                  <th>KGVOL</th>
                </tr>
              </thead>
              <tbody>
                @if (count($recipients) > 0)
                @foreach ($packages as $package)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $package->name }}</td>
                  <td>{{ $package->tracking_number }}</td>
                  <td>{{ $package->weight }}</td>
                  <td>{{ $package->length }}</td>
                  <td>{{ $package->width }}</td>
                  <td>{{ $package->height }}</td>
                  <td>{{ $package->cubic_weight }}</td>
                  @endforeach
                  @else
                <tr>
                  <td colspan="9" class="text-center">Tidak ada data</td>
                </tr>
                @endif
              </tbody>
              @if (count($recipients) > 0)
              @php
              $totalWeight = 0;
              $totalCubicWeight = 0;
              $totalPrice = 0;
              foreach ($packages as $package) {
              $package->weight = floatval(str_replace(',', '.', $package->weight));
              $package->cubic_weight = floatval(str_replace(',', '.', $package->cubic_weight));

              $totalWeight += $package->weight;
              $totalCubicWeight += $package->cubic_weight;
              $totalPrice += $package->price;
              }
              @endphp
              <tfoot>
                <tr>
                  <th colspan="3">Total</th>
                  <th>{{ $totalWeight }}</th>
                  <th colspan="3">&nbsp;</th>
                  <th>{{ $totalCubicWeight }}</th>
                </tr>
              </tfoot>
              @endif
            </table>
            @if (count($recipients) > 0 && count($packages) > 0)
            <button id="btn-send-jastip" class="btn btn-primary mt-2 float-end" disabled>Kirim Paket</button>
            @endif
          </div>
        </div>
      </div>
    </section>
  </x-page-heading>
  <x-slot:script>
    <script>
      const SEND_JASTIP_URL = "{{ route('jastip.saveJastipReport') }}"
    </script>
    <script type="module" src="{{ asset('js/jastip.js') }}"></script>
  </x-slot:script>
</x-app>