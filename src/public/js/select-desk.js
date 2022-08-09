var rowsCount = 0;
window.onload = function(){ 
    verifyCSS();
}

function selectDesk(row, col) {
    rowsCount = 0;
    $('#addResourcesBody').empty();
    verifyCSS()
    var newDeskBtn = document.getElementById(`newDeskBtn${row},${col}`);
    console.log("THIS DESK COLOR IS : ", newDeskBtn.style.backgroundColor);
    console.log(`Desk ${row} and ${col} has been selected`);
    $("#newDeskModal").modal("show");
    $("#row").val(row);
    $("#col").val(col);
}

function editDesk(id, row, col, roomId, isClosed) {

    $.ajax({
        type: "GET",
        url: "/get-resources",
        data: {data: id},
        success: function (data) {
            $('#addResourcesBodyEdit').empty();
            $('#addResourcesBodyEdit').append(data[0]);
            rowsCount = data[1];
            verifyCSS()
        }
    });

    $('#addResourcesBodyEdit').empty();
    verifyCSS()
    // alert(`edit desk: ${row}, ${col}`);
    $("#editDeskModal").modal("show");
    var deskClosedSwitch = document.getElementById("is_closed");
    console.log(isClosed);

    if(isClosed) {
        $("#is_closedEdit").prop('checked', false);
    } else {
        $("#is_closedEdit").prop('checked', true);
    }
    
    $("#pos_x").val(row); 
    $("#pos_y").val(col);
    $("#room_id").val(roomId)
    $("#id").val(id);
}

function verifyCSS() {
    if (rowsCount == 0) {
        $('#resourcesListEdit').hide();
        $('#resourcesListAdd').hide();
        $('#resourcesWarningEdit').show();
        $('#resourcesWarningAdd').show();
    } else {
        $('#resourcesWarningEdit').hide();
        $('#resourcesWarningAdd').hide();
        $('#resourcesListEdit').show();
        $('#resourcesListAdd').show();
    }
}

function addNewResource($isEdit) {
    if ($isEdit) {
        rowsCount += 1;
        $.ajax({
            type: "GET",
            url: "/get-resources-append-new",
            data: {data: true},
            success: function (data) {
                $('#addResourcesBodyEdit').append(data);
                verifyCSS();
            }
        });
    } else {
        rowsCount += 1;
        $.ajax({
            type: "GET",
            url: "/get-resources-append-new",
            data: {data: false},
            success: function (data) {
                $('#addResourcesBodyAdd').append(data);
                verifyCSS();
            }
        });
    }
}

function deleteResource(submitter) {
    rowsCount -= 1;
    $(submitter).parents('tr')[0].remove();
    verifyCSS();
}