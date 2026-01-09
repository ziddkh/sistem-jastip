import { displayErrors, clearErrors } from "./handle-error.js"

$(function () {
  const form = $("#form-jastip-date");
  const targetPartial = $("#js-packages-partial-target")
  const buttonJastipDate = $('#button-submit-jastip-date')
  const buttonExportPdf = $('#button-export-pdf')

  $('.input-date').on('change', function() {
    const inputDate = $('.input-date')
    let isAllFilled = true
    inputDate.each(function() {
      if (!!$(this).val() === false) {
        isAllFilled = false
      }
    })
    if (isAllFilled) {
      buttonJastipDate.attr('disabled', false)
      buttonExportPdf.attr('disabled', false)
    } else {
      buttonJastipDate.attr('disabled', true)
      buttonExportPdf.attr('disabled', true)
    }
  })

  function setLoading(isLoading) {
    if (isLoading) {
      buttonJastipDate.attr('disabled', true)
      buttonJastipDate.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`)
    } else {
      buttonJastipDate.attr('disabled', false)
      buttonJastipDate.html(`Submit`)
    }
  }

  form.on('submit', function(e) {
    e.preventDefault()
    getPackages()
  })

  // Export PDF button click handler
  buttonExportPdf.on('click', function() {
    const startDate = $('#start-date').val()
    const endDate = $('#end-date').val()
    
    // Create a hidden form to submit as POST for PDF export
    const pdfForm = document.createElement('form')
    pdfForm.method = 'POST'
    pdfForm.action = EXPORT_PDF_URL
    pdfForm.style.display = 'none'
    
    // Add CSRF token
    const csrfInput = document.createElement('input')
    csrfInput.type = 'hidden'
    csrfInput.name = '_token'
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    pdfForm.appendChild(csrfInput)
    
    // Add start date
    const startDateInput = document.createElement('input')
    startDateInput.type = 'hidden'
    startDateInput.name = 'start_date'
    startDateInput.value = startDate
    pdfForm.appendChild(startDateInput)
    
    // Add end date
    const endDateInput = document.createElement('input')
    endDateInput.type = 'hidden'
    endDateInput.name = 'end_date'
    endDateInput.value = endDate
    pdfForm.appendChild(endDateInput)
    
    document.body.appendChild(pdfForm)
    pdfForm.submit()
    
    // Remove form after a delay to allow submission to complete
    setTimeout(() => {
      if (pdfForm.parentNode) {
        document.body.removeChild(pdfForm)
      }
    }, 1000)
  })

  async function getPackages() {
    const formData = new FormData(form[0])
    targetPartial.html(PLACEHOLDER_ELEMENT)
    setLoading(true)
    try {
      const { data } = await axios.post(REPORT_JASTIP_URL, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })

      targetPartial.html(data)
      clearErrors()
      setLoading(false)
    } catch (error) {
      displayErrors('#form-jastip-date', error.response.data.errors)
      console.log(error)
    }
  }

})
