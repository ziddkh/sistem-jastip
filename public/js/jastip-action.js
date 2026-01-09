import clientRequest from "./request.js";
import { clearErrors } from "./handle-error.js";
import showToast from "./toast.js";

const swal2 = Swal.mixin()

$(function () {
  const createForm = $("#createForm");
  const editForm = $("#editForm");
  const submitButton = $("#submitButton");

  function updateItemCount() {
    const count = $(".package-row").length;
    const text = `${count} Paket`;
    $("#itemCount").text(text);
  }

  function initializeRow(row) {
    const index = row.data("index");
    const weight = row.find(`#weight-${index}`);
    const length = row.find(`#length-${index}`);
    const width = row.find(`#width-${index}`);
    const height = row.find(`#height-${index}`);
    const cubicWeight = row.find(`#cubicWeight-${index}`);

    // Initialize Cleave for number formatting
    if (weight.length && !weight.data("cleave")) {
      const weightCleave = new Cleave(weight[0], {
        numeral: true,
        numeralPositiveOnly: true,
        numeralDecimalMark: ",",
        delimiter: ".",
      });
      weight.data("cleave", weightCleave);
    }

    if (length.length && !length.data("cleave")) {
      const lengthCleave = new Cleave(length[0], {
        numeral: true,
        numeralPositiveOnly: true,
        numeralDecimalMark: ",",
        delimiter: ".",
      });
      length.data("cleave", lengthCleave);
    }

    if (width.length && !width.data("cleave")) {
      const widthCleave = new Cleave(width[0], {
        numeral: true,
        numeralPositiveOnly: true,
        numeralDecimalMark: ",",
        delimiter: ".",
      });
      width.data("cleave", widthCleave);
    }

    if (height.length && !height.data("cleave")) {
      const heightCleave = new Cleave(height[0], {
        numeral: true,
        numeralPositiveOnly: true,
        numeralDecimalMark: ",",
        delimiter: ".",
      });
      height.data("cleave", heightCleave);
    }

    if (cubicWeight.length && !cubicWeight.data("cleave")) {
      const cubicWeightCleave = new Cleave(cubicWeight[0], {
        numeral: true,
        numeralPositiveOnly: true,
        numeralDecimalMark: ",",
        delimiter: ".",
      });
      cubicWeight.data("cleave", cubicWeightCleave);
    }

    // Calculate cubic weight on dimension change
    function calculateCubicWeight() {
      const l = parseFloat(length.data("cleave")?.getRawValue() || length.val() || 0);
      const w = parseFloat(width.data("cleave")?.getRawValue() || width.val() || 0);
      const h = parseFloat(height.data("cleave")?.getRawValue() || height.val() || 0);
      const cubicValue = (l * w * h) / 4000;
      
      if (cubicWeight.data("cleave")) {
        cubicWeight.data("cleave").setRawValue(cubicValue.toFixed(2));
      } else {
        cubicWeight.val(cubicValue.toFixed(2));
      }
    }

    length.off("keyup").on("keyup", calculateCubicWeight);
    width.off("keyup").on("keyup", calculateCubicWeight);
    height.off("keyup").on("keyup", calculateCubicWeight);

    // Initialize cubic weight calculation on load
    calculateCubicWeight();
  }

  const packagesContainer = $("#packagesContainer");
  const addPackageButton = $("#addPackageButton");

  addPackageButton.on("click", function () {
    const rowCount = $(".package-row").length;
    const newIndex = rowCount + 1;
    
    const newRow = `
      <div class="package-row mb-3" data-index="${newIndex}">
        <input type="hidden" name="packages[${newIndex}][pricing_option]" value="kubikasi">
        <input type="hidden" name="packages[${newIndex}][price]" id="price-${newIndex}" value="0">
        <div class="row g-2 align-items-center">
          <div class="col-12 col-md-3">
            <div class="input-group">
              <span class="input-group-text bg-light d-flex align-items-center justify-content-center">
                <i class="bi bi-upc-scan"></i>
              </span>
              <input type="text" class="form-control" name="packages[${newIndex}][tracking_number]" 
                placeholder="Scan Resi/Input Resi...">
            </div>
          </div>
          <div class="col-6 col-md-2">
            <div class="input-group">
              <input type="text" class="form-control weight" id="weight-${newIndex}" 
                name="packages[${newIndex}][weight]" placeholder="0.0">
              <span class="input-group-text bg-light">kg</span>
            </div>
          </div>
          <div class="col-12 col-md-3">
            <div class="d-flex align-items-center gap-1">
              <input type="text" class="form-control dimension text-center" 
                id="length-${newIndex}" name="packages[${newIndex}][length]" placeholder="P">
              <span class="text-muted">×</span>
              <input type="text" class="form-control dimension text-center" 
                id="width-${newIndex}" name="packages[${newIndex}][width]" placeholder="L">
              <span class="text-muted">×</span>
              <input type="text" class="form-control dimension text-center" 
                id="height-${newIndex}" name="packages[${newIndex}][height]" placeholder="T">
            </div>
          </div>
          <div class="col-6 col-md-2">
            <div class="input-group">
              <input type="text" class="form-control cubic-weight" id="cubicWeight-${newIndex}" 
                name="packages[${newIndex}][cubic_weight]" value="0,00" readonly
                style="background-color: #f8f9fa;">
              <span class="input-group-text bg-light">kg</span>
            </div>
          </div>
          <div class="col-12 col-md-2 d-flex align-items-center justify-content-center">
            <button type="button" class="btn btn-sm btn-outline-danger removeButton">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      </div>
    `;
    
    packagesContainer.append(newRow);
    const newRowElement = packagesContainer.find(`.package-row[data-index="${newIndex}"]`);
    initializeRow(newRowElement);
    updateItemCount();
  });

  packagesContainer.on("click", ".removeButton", function () {
    $(this).closest(".package-row").remove();
    updateItemCount();
  });

  // Initialize all existing rows
  $(".package-row").each(function () {
    initializeRow($(this));
  });
  updateItemCount();

  function setLoading(isLoading) {
    if (isLoading) {
      submitButton.attr("disabled", true);
      submitButton.html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
      );
    } else {
      submitButton.attr("disabled", false);
      submitButton.html('<i class="bi bi-send me-1"></i> Simpan');
    }
  }

  /**
   * CREATE JASTIP
   */
  createForm.on("submit", function (e) {
    e.preventDefault();
    const data = new FormData(this);
    const CREATE_URL = $(this).attr("action");
    setLoading(true);
    swal2.fire({
      title: "Simpan data jastip?",
      text: "Pastikan semua data sudah benar",
      icon: "question",
      showCancelButton: true,
      cancelButtonText: "Batal",
      confirmButtonText: "Ya, Simpan",
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        clientRequest(CREATE_URL, "post", data, (success, res) => {
          clearErrors();
          setLoading(false);
          if (success) {
            swal2.fire({
              title: "Berhasil!",
              text: res.data.message,
              icon: "success",
              showCancelButton: true,
              cancelButtonText: "Tambah Jastip Lagi",
              confirmButtonText: "Lihat Daftar Jastip",
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = res.data.redirect_uri;
              } else {
                window.location.reload();
              }
            });
          } else {
            if (res.status === 422) {
              showToast("Lengkapi semua data yang diperlukan");
            } else {
              showToast("Gagal menyimpan data");
            }
          }
        });
      } else {
        setLoading(false);
      }
    })
  });

  /**
   * EDIT JASTIP
   */
  editForm.on("submit", function (e) {
    e.preventDefault();
    const data = new FormData(this);
    data.set("_method", "PUT");
    const EDIT_URL = $(this).attr("action");
    setLoading(true);
    swal2.fire({
      title: "Simpan perubahan?",
      text: "Pastikan semua data sudah benar",
      icon: "question",
      showCancelButton: true,
      cancelButtonText: "Batal",
      confirmButtonText: "Ya, Simpan",
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        clientRequest(EDIT_URL, "post", data, (success, res) => {
          clearErrors();
          setLoading(false);
          if (success) {
            swal2.fire({
              title: "Berhasil!",
              text: res.data.message,
              icon: "success",
              showCancelButton: true,
              cancelButtonText: "Tambah Jastip Baru",
              confirmButtonText: "Lihat Daftar Jastip",
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = res.data.redirect_home;
              } else {
                window.location.href = res.data.redirect_create;
              }
            });
          } else {
            if (res.status === 422) {
              showToast("Lengkapi semua data yang diperlukan");
            } else {
              showToast("Gagal menyimpan perubahan");
            }
          }
        });
      } else {
        setLoading(false);
      }
    })
  });
});

// Handle keyboard navigation
$(document).on("keydown", "input, select", function(e) {
  if (e.which === 13 || e.which === 9) {
    e.preventDefault();
    const $canfocus = $(
      'input:not([readonly]):not([disabled]):not([type="hidden"]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'
    );
    const index = $canfocus.index(this) + 1;
    if (index >= $canfocus.length) $canfocus[0].focus();
    else $canfocus[index].focus();
  }
});
