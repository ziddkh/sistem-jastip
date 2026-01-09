import clientRequest from "./request.js";
import showToast from "./toast.js";

const swal2 = Swal.mixin()

$(function () {
  const deleteForm = $("#deleteForm");
  const deleteModal = $("#deleteModal");

  const table = $("#jastipTable");

  const route = window.location.pathname;
  const isJastipProgress = route === "/jastip/diterima";
  if (!isJastipProgress) {
    /**
     * GET JASTIP
     */
    const dataTableSetup = {
      serverSide: true,
      ajax: DATA_URL,
      columns: [
        {
          sClass: "text-center",
          data: "DT_RowIndex",
          name: "id",
        },
        {
          data: "name",
        },
        {
          data: "recipient_status.status.name",
        },
        { data: "action", orderable: false, searchable: false },
      ],
    };

    const drawTable = (dataTableData) => {
      table.DataTable(dataTableData);
    };

    drawTable(dataTableSetup);


  }

  const formatNumber = (number) => {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  };

  table.on("click", ".btn-detail", function () {
    const url = $(this).data("uri");
    const recipientNameElement = $("#recipientName");
    const createdDateElement = $("#createdDate");
    const totalWeightElement = $("#totalWeight");
    const totalCubicWeightElement = $("#totalCubicWeight");
    const editOrderBtn = $("#editOrderBtn");
    
    $.get(url, function ({ id, name, packages, created_at }) {
      $("#detailModal").modal("show");
      recipientNameElement.text(name);
      
      // Format the created_at date
      const date = new Date(created_at);
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      const formattedDate = date.toLocaleDateString('id-ID', options);
      createdDateElement.text(formattedDate);
      
      // Set edit button URL
      editOrderBtn.attr("href", `/jastip/${id}`);
      
      let totalWeight = 0;
      let totalCubicWeight = 0;
      let tdElement = "";
      let rowNum = 0;
      
      packages.map(({ tracking_number, weight, cubic_weight, length, width, height }) => {
        rowNum++;
        weight = weight ? weight.replace(",", ".") : "0";
        cubic_weight = cubic_weight ? cubic_weight.replace(",", ".") : "0";
        weight = parseFloat(weight);
        cubic_weight = parseFloat(cubic_weight);
        totalWeight += weight;
        totalCubicWeight += cubic_weight;
        
        const dimensions = `${length || 0} x ${width || 0} x ${height || 0}`;
        
        tdElement += `<tr>
                        <td>${rowNum}</td>
                        <td>
                          <div class="fw-semibold">${tracking_number}</div>
                        </td>
                        <td class="text-center">${weight}</td>
                        <td class="text-center text-primary">${dimensions}</td>
                        <td class="text-center">${cubic_weight.toFixed(1)}</td>
                      </tr>`;
      });
      
      $("#detailTableBody").html(tdElement);
      totalWeight = totalWeight % 1 === 0 ? totalWeight : totalWeight.toFixed(1);
      totalCubicWeight = totalCubicWeight % 1 === 0 ? totalCubicWeight : totalCubicWeight.toFixed(1);
      totalWeightElement.text(totalWeight);
      totalCubicWeightElement.text(totalCubicWeight);
    });
  });

  /**
   * DELETE JASTIP
   */
  table.on("click", ".btn-delete", function () {
    const url = $(this).data("uri");
    deleteForm.attr("action", url);
  });

  deleteForm.on("submit", function (e) {
    e.preventDefault();
    const DELETE_URL = $(this).attr("action");
    console.log(DELETE_URL);
    const data = { _method: "DELETE" };
    clientRequest(DELETE_URL, "post", data, (success, res) => {
      if (success) {
        table.DataTable().ajax.reload();
        deleteModal.modal("hide");
        showToast(res.data.message);
      } else {
        showToast("Gagal Untuk Menghapus Data");
      }
    });
  });

  if(isJastipProgress) {
    /**
     * SAVE PROGRESS
     */
    const buttonSendJastip = $("#btn-send-jastip");
    const inputSendLocation = $("#input-send-location")
    function checkInputSendLocation() {
      if (inputSendLocation.val() !== '') {
        buttonSendJastip.prop("disabled", false);
      } else {
        buttonSendJastip.prop("disabled", true);
      }
    }

    inputSendLocation.on("input", function () {
      checkInputSendLocation();
    })

    function setLoading(isLoading) {
      if (isLoading) {
        buttonSendJastip.html(
          `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sedang menyimpan data...`
        );
        buttonSendJastip.prop("disabled", true);
      } else {
        buttonSendJastip.html("Simpan");
        buttonSendJastip.prop("disabled", false);
      }
    }

    const formSendLocation = $('#form-send-location');

    formSendLocation.on('submit', async function(e) {
      e.preventDefault();
      setLoading(true);
      swal2.fire({
        title: `Apakah anda yakin untuk menyimpan barang ke lokasi <b>${inputSendLocation.val()}</b>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, simpan!',
        cancelButtonText: 'Tidak, batalkan!',
        reverseButtons: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
      }).then(async (result) => {
        if (result.isConfirmed) {
          const formData = new FormData(this);
          try {
            const { data } = await axios.post(SEND_JASTIP_URL, formData);
            window.location.reload();
          } catch (error) {
            setLoading(false);
            console.log(error)
          }
        } else setLoading(false);
      })
    })

    buttonSendJastip.on('click', function() {
      formSendLocation.submit();
    })
  }
});
