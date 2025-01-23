$(document).ready(function() {
    // Initialize DataTable with buttons for #datatable
    $('#datatable').DataTable({
        "order": [[3, 'desc']], // Sort by the second column (index 1) by default, in descending order
        "columnDefs": [
            { "type": "date", "targets": [1] } // Ensure that the 'Created At' column is treated as a date for sorting
        ],
		pagingType: "simple",
    });
    $("#datatable-buttons").DataTable({
        lengthChange: false,
        buttons: ["copy", "excel", "pdf"],
        pagingType: "simple",
    }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");

    // Initialize DataTable with buttons for #datatable1
    $("#datatable1").DataTable({
        lengthChange: false,
        pagingType: "simple",
    });
    $("#datatable-buttons1").DataTable({
        lengthChange: false,
        buttons: ["copy", "excel", "pdf"],
        pagingType: "simple",
    }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");

    // Add form-select class to the DataTable's length dropdown
    $(".dataTables_length select").addClass("form-select form-select-sm");

    // Apply dropdown style based on screen size for both tables
    handleButtonLayout();
});

// Function to handle button layout for both datatables
function handleButtonLayout() {
    if ($(window).width() <= 768) {  // Adjust this value as needed for your layout
        // Switch buttons to dropdown on smaller screens (mobile view)
        $(".dataTables_wrapper").each(function() {
            var buttonsContainer = $(this).find(".dt-buttons");
            var tableId = $(this).attr("id");  // Identify the table (datatable or datatable1)
            
            buttonsContainer.addClass("btn-group").html(`
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton${tableId}" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton${tableId}">
                        <li><button class="dropdown-item" id="copyBtn${tableId}">Copy</button></li>
                        <li><button class="dropdown-item" id="excelBtn${tableId}">Excel</button></li>
                        <li><button class="dropdown-item" id="pdfBtn${tableId}">PDF</button></li>
                    </ul>
                </div>
            `);

            // Add event listeners to buttons (these will trigger the respective actions)
            $(`#copyBtn${tableId}`).on("click", function() { $(`#${tableId} .buttons-copy`).click(); });
            $(`#excelBtn${tableId}`).on("click", function() { $(`#${tableId} .buttons-excel`).click(); });
            $(`#pdfBtn${tableId}`).on("click", function() { $(`#${tableId} .buttons-pdf`).click(); });
        });
    } else {
        // Remove dropdown and revert to buttons for larger screens
        $(".dataTables_wrapper").each(function() {
            var buttonsContainer = $(this).find(".dt-buttons");
            buttonsContainer.removeClass("btn-group").html(`
                <button class="btn btn-secondary">Copy</button>
                <button class="btn btn-secondary">Excel</button>
                <button class="btn btn-secondary">PDF</button>
            `);
        });
    }
}

// Reapply layout changes on window resize
$(window).resize(function() {
    handleButtonLayout();
});
