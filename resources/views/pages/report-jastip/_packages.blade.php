<div class="card">
  <div class="card-header">
    <h4>Data Laporan Jastip</h4>
  </div>
  <div class="card-body">
    <table class="table">
      <thead>
        <th>No.</th>
        <th>Nama Penerima</th>
        <th>Resi</th>
        <th>Berat</th>
        <th>P</th>
        <th>L</th>
        <th>T</th>
        <th>KGVOL</th>
      </thead>
      <tbody>
        @if (count($packages) > 0)
          @php
            $totalWeight = 0;
            $totalCubicWeight = 0;
            $totalPrice = 0;
            foreach ($packages as $package) {
                $package->weight = floatval(str_replace(',', '.', $package->weight));
                $package->cubic_weight = floatval(str_replace(',', '.', $package->cubic_weight));

                $totalWeight += $package->weight;
                $totalCubicWeight += $package->cubic_weight;
            }
          @endphp
          <tr>
            <th colspan="3" class="text-center">Total</th>
            <th>{{ $totalWeight }}</th>
            <th colspan="3">&nbsp;</th>
            <th>{{ $totalCubicWeight }}</th>
          </tr>
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
          <tr>
            <th colspan="3" class="text-center">Total</th>
            <th>{{ $totalWeight }}</th>
            <th colspan="3">&nbsp;</th>
            <th>{{ $totalCubicWeight }}</th>
          </tr>
        @else
          <tr>
            <td colspan="9" class="text-center">Tidak ada data</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>
