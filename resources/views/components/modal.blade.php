@props([
'id' => '',
'title' => 'Modal',
'label' => 'modal',
'variant' => '',
'size' => 'modal-lg',
])

<div class="modal fade text-left" id="{{ $id }}Modal" tabindex="-1" role="dialog"
  aria-labelledby="{{ $label }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable {{ $size }}" role="document">
    <div class="modal-content">
      <div class="modal-header {{ $variant }}">
        <h5 id="modalTitle" class="modal-title{{ $variant !== '' ? ' white' : '' }}" id="{{ $label }}">
          {{ $title }}
        </h5>
        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
          <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal-body">
        {{ $slot }}
      </div>
      @if(isset($footer))
      <div class="modal-footer">
        {{ $footer }}
      </div>
      @else
      <div class="modal-footer">
        <button type="button" id="cancelModal" class="btn" data-bs-dismiss="modal">
          <i class="bx bx-x d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Tutup</span>
        </button>
      </div>
      @endif
    </div>
  </div>
</div>