var baseUrl = $("meta[name=base_url]").attr("content");

function submitAfterValid(formId, suffixID = "", massError = false) {
    var initText = $("#btn-for-" + formId).html();

    var imgLoading =
        "<img src='" +
        baseUrl +
        "/easyadmin/idev/img/loading-buffering.gif' style='width:15px;' width='20px'>";
    $("#btn-for-" + formId).html(imgLoading + " Processing...");
    $("#btn-for-" + formId).attr("disabled", "disabled");

    var datastring = $("#" + formId).serialize();
    var formData = new FormData($("#" + formId)[0]);

    var url = $("#" + formId).attr("action");

    $(".rect-validation").css({ border: "1px solid #428fc7" });
    $(".error-message").remove();
    $(".progress-loading").remove();
    blinkElement(".btn");
    setInterval(blinkElement, 1000);

    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            $("#btn-for-" + formId).removeAttr("disabled");
            $(".progress-loading").remove();
            $("#btn-for-" + formId).html(initText);
            if (response.status) {
                window.location.href = response.redirect_to;
            } else {
                messageErrorGeneral("#" + formId, response.message);
                if (massError && response.mass_errors) {
                    $(".modal").modal("hide");
                    $("#massError-" + formId).modal("show");
                    var htmlTable = "";
                    $.each(response.mass_errors, function (index, error) {
                        htmlTable +=
                            "<tr><td>" +
                            error.row +
                            "</td><td>" +
                            error.errormsg[0] +
                            "</td></tr>";
                    });
                    $(".tbody-errors").html(htmlTable);
                    $("#supportDtCust").DataTable();
                } else {
                    $.each(response.validation_errors, function (index, error) {
                        var currentID = $("#" + error.id + suffixID);
                        $(currentID).css({ border: "1px solid #c74266" });
                        messageErrorForm(currentID, error.message);
                    });
                }
            }
        },
        error: function (xhr, status, error) {
            $("#btn-for-" + formId).html(initText);
            $("#btn-for-" + formId).removeAttr("disabled");
            $(".progress-loading").remove();
            var messageErr = "Something Went Wrong";
            if (xhr.responseJSON) {
                messageErr = xhr.responseJSON.message;
            }
            messageErrorGeneral("#" + formId, messageErr);
        },
    });
}

function blinkElement(elem) {
    $(elem).fadeOut(500);
    $(elem).fadeIn(300);
}

function messageErrorForm(currentID, message) {
    $(
        "<div class='error-message' style='color:#c74266; float:right; font-size:12px;'>" +
            message +
            "</div>"
    )
        .insertBefore(currentID)
        .hide()
        .show("medium");
}

function messageErrorGeneral(currentID, message, type = 'danger') {
    $("<div class='error-message alert alert-"+type+" text-white'>" + message + "</div>")
        .insertBefore(currentID)
        .hide()
        .show("medium");

    window.setTimeout(function () {
        $(".alert")
            .fadeTo(500, 0)
            .slideUp(500, function () {
                $(this).remove();
            });
    }, 6000);
}

function idevTable(formId, attrs = []) {
    var datastring = $("#form-filter-" + formId).serialize();
    var url = $("#form-filter-" + formId).attr("action");
    if (attrs.url) {
        url = attrs.url
    }
    var htmlTable = "Processing...";
    var idTable = "#table-" + formId;
    var paginateTable = "#paginate-" + formId;
    var routeKey = $(".route-name").val();
    $(idTable).css("opacity", "0.7");
    $("button").attr("disabled", "disabled");
    $("<div class='idev-loading loading-table text-center' style='width:100%;'><img src='" +
    baseUrl +
    "/easyadmin/idev/img/loading-buffering.gif' width='28px'><br>Processing...</div>").insertAfter(idTable)
    // $(idTable).append(
    //     "<div class='idev-loading loading-table text-center' style='width:100%;'><img src='" +
    //         baseUrl +
    //         "/idev/img/loading-buffering.gif' width='34px'><br>Processing...</div>"
    // );
    $(".count-total").text("");

    $.ajax({
        type: "GET",
        url: url,
        data: datastring,
        contentType: false,
        processData: false,
        success: function (responses) {
            htmlTable = "";
            var forcePrimary = responses.force_primary;
            var dataQueries = responses.data_queries;
            var dataColumns = responses.data_columns;
            var dataColFormat = responses.data_col_formatting;
            var dataPermissions = responses.data_permissions;
            var extraButtons = responses.extra_buttons ?? [];
            var intActionCol = 0;
            if (dataQueries) {
                $.each(dataQueries.data, function (key, item) {
                    var primaryKey = forcePrimary ? item[forcePrimary] : item.id;
    
                    var numb =
                        1 +
                        key +
                        (dataQueries.current_page - 1) * dataQueries.per_page;

                    if ($(window).width() <= 765) {
                        $(idTable + " thead").html("");

                        htmlTable += "<tr><td>";
                        // htmlTable += "<td>" + numb + "</td>";
                        $.each(dataColumns, function (key2, col) {  
                            var mLabel = col.replace("_", " ").toUpperCase() 
                            htmlTable += "<b>" + mLabel + " : </b><br>";

                            var mItem = formattingColumn(item, col, dataColFormat)

                            if (col == 'view_image') {
                                htmlTable += "<div><img class='img-thumbnail img-responsive' width='120px' src='"+mItem+"'></div>";
                            }else{
                                htmlTable += "<div class='" +formId +"-"+primaryKey+"-" +col +"'>" + mItem +"</div>";
                            }
                        });
                        htmlTable +=
                            "<div class='col-action' style='white-space: nowrap;'>";
                        $.each(extraButtons, function (key3, eb) {
                            if (item[eb] && dataPermissions.includes(eb.replace("btn_", ""))) {
                                htmlTable += item[eb];
                                intActionCol++;
                            }
                        });
                        htmlTable += "</div>";
                        htmlTable += "</td></tr>";
                    }else{
                        htmlTable += "<tr>";
                        htmlTable += "<td>" + numb + "</td>";
                        $.each(dataColumns, function (key2, col) {                        
                            var mItem = formattingColumn(item, col, dataColFormat)
    
                            if (col == 'view_image') {
                                htmlTable += "<td><img class='img-thumbnail img-responsive' width='120px' src='"+mItem+"'></td>";
                            }else{
                                htmlTable += "<td class='" +formId +"-"+primaryKey+"-" +col +"'>" + mItem +"</td>";
                            }
                        });
                        htmlTable +=
                            "<td class='col-action' style='white-space: nowrap;'>";
                        $.each(extraButtons, function (key3, eb) {
                            if (item[eb] && dataPermissions.includes(eb.replace("btn_", ""))) {
                                htmlTable += item[eb];
                                intActionCol++;
                            }
                        });
                        htmlTable += "</td>";
                        htmlTable += "</tr>";
                    }
                    
                });
    
                $(".count-total-" + formId).text(
                    "Total Data : " + dataQueries.total
                );
    
                $(idTable + " tbody").html(htmlTable);
                $(idTable).css("opacity", "1");
                $(paginateTable).html(
                    generatePaginate(
                        formId,
                        dataQueries.current_page,
                        dataQueries.links
                    )
                );
                if (dataQueries.data.length == 0) {
                    $(paginateTable).html("");
                }
            }
            if (intActionCol == 0) {
                $(idTable + " .col-action").remove();
            }
            $(".idev-loading").remove();
            $("button").removeAttr("disabled");
        },
        error: function (xhr, status, error) {
            $(".progress-loading").remove();
            $("button").removeAttr("disabled");
            var messageErr = "Something Went Wrong";
            if (xhr.responseJSON) {
                messageErr =
                    xhr.responseJSON.message == ""
                        ? xhr.responseJSON.exception
                        : xhr.responseJSON.message;
            }
            $(".table-responsive").html(
                "<div class='card'><div class='card-body'><strong class='text-danger'>" +
                    messageErr +
                    "</strong></div></div>"
            );
        },
    });
}

function generatePaginate(formId, current, pages) {
    var htmlPaginate = '<div class="idev-pagination">';
    $.each(pages, function (key, item) {
        var isCurrent = current == item.label ? "current" : "";
        if (item.label != "&laquo; Previous" && item.label != "Next &raquo;") {
            htmlPaginate +=
                '<button class="paginate_button ' +
                isCurrent +
                '" onclick="toPage(\'' +
                formId +
                "', " +
                item.label +
                ')" >' +
                item.label +
                "</button>";
        }
    });
    htmlPaginate += "</div>";

    return htmlPaginate;
}

function toPage(formId, page) {
    $(".current-paginate-" + formId.replace("list-", "")).val(page);
    idevTable(formId);
}

function orderBy(formId, column) {
    var mClass = ".current-order-state-" + formId.replace("list-", "");
    var orderState = $(mClass).val();
    if (orderState == "ASC") {
        $(mClass).val("DESC");
    } else {
        $(mClass).val("ASC");
    }
    $(".current-order-" + formId.replace("list-", "")).val(column);
    idevTable(formId);
}

function delay(callback, ms) {
    var timer = 0;
    return function () {
        var context = this,
            args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}

function setDeleteOld(id, url, key) {
    var currentRow =
        "<table class='table table-striped table-sm' style='color:#ffadac; font-size:14px;'><tr>";

    $(id + " tbody tr:eq(" + key + ") td").each(function (i, item) {
        if (!$(this).hasClass("col-action") && i < 3) {
            i++;
            currentRow +=
                "<td>" +
                $(
                    id + " tbody tr:eq(" + key + ") td:nth-child(" + i + ")"
                ).html() +
                "</td>";
        }
    });
    currentRow += "</tr></table>";

    $("#formDelete").attr("action", url);
    $("#attrDelete").html(currentRow);
}

function trashButton(idTable, route, key) {
    var htmlButton = "";
    htmlButton +=
        '<button type="button" name="button" class="btn btn-sm btn-outline-danger mx-1" data-bs-toggle="modal" data-bs-target="#modalDelete" onclick="setDelete(\'' +
        idTable +
        "', '" +
        route +
        "', '" +
        key +
        "')\">";
    htmlButton +=
        '<i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Hapus"> </i>';
    htmlButton += "</button>";

    return htmlButton;
}

function editButton(route) {
    var htmlButton = "";
    htmlButton +=
        '<button type="button" name="button" class="btn btn-sm btn-outline-info mx-1" data-bs-toggle="modal" data-bs-target="#modalEdit" onclick="window.location=\'' +
        route +
        "'\">";
    htmlButton +=
        '<i class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="Edit"> </i>';
    htmlButton += "</button>";

    return htmlButton;
}

function softSubmit(formId, reloadList, callback = false) {
    var initText = $("#btn-for-" + formId).html();

    var suffixID = "";
    var massError = false;

    var imgLoading =
        "<img src='" +
        baseUrl +
        "/easyadmin/idev/img/loading-buffering.gif' style='width:15px;' width='20px'>";
    $("#btn-for-" + formId).html(imgLoading + " Processing...");
    $("#btn-for-" + formId).attr("disabled", "disabled");

    var datastring = $("#" + formId).serialize();
    var formData = new FormData($("#" + formId)[0]);

    var url = $("#" + formId).attr("action");

    $(".rect-validation").css({ border: "1px solid #428fc7" });
    $(".error-message").remove();
    $(".progress-loading").remove();
    blinkElement(".btn");
    setInterval(blinkElement, 1000);

    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            $("#btn-for-" + formId).removeAttr("disabled");
            $(".progress-loading").remove();
            $("#btn-for-" + formId).html(initText);
            if (callback != false) {
                callback()
            }else{
                if (response.status) {
                    $(".modal").modal("hide");
                    $(".btn-close").click();
                    idevTable(reloadList);
                    alertSwal("success", response.message);
                } else {
                    var typeAlert = 'danger'
                    if (response.alert) {
                        typeAlert = response.alert
                    }
                    messageErrorGeneral("#" + formId, response.message, typeAlert);
                    if (massError && response.mass_errors) {
                        $(".modal").modal("hide");
                        $("#massError-" + formId).modal("show");
                        var htmlTable = "";
                        $.each(response.mass_errors, function (index, error) {
                            htmlTable +=
                                "<tr><td>" +
                                error.row +
                                "</td><td>" +
                                error.errormsg[0] +
                                "</td></tr>";
                        });
                        $(".tbody-errors").html(htmlTable);
                        $("#supportDtCust").DataTable();
                    } else {
                        $.each(response.validation_errors, function (index, error) {
                            var currentID = $("#" + error.id + suffixID);
                            $(currentID).css({ border: "1px solid #c74266" });
                            messageErrorForm(currentID, error.message);
                        });
                    }
                }
            }
            
        },
        error: function (xhr, status, error) {
            $("#btn-for-" + formId).html(initText);
            $("#btn-for-" + formId).removeAttr("disabled");
            $(".progress-loading").remove();
            var messageErr = "Something Went Wrong";
            if (xhr.responseJSON) {
                messageErr = xhr.responseJSON.message;
            }
            messageErrorGeneral("#" + formId, messageErr);
        },
    });
}

// $("#multiple-select")
//     .mobiscroll()
//     .select({
//         inputElement: document.getElementById("my-input"),
//         touchUi: false,
//     });

function alertSwal(type, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top",
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: false,
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    });

    Toast.fire({
        icon: type,
        title: message,
    });
}


function checkboxAction(arrSavedInstance, params = {}) {
    var classSingleCb = $(params.classSingleCb)
    var classAllCb = $(params.classAllCb)
    var inputHidden = $(params.inputHidden)
    classAllCb.prop('checked', false)

    var currentChecked = []
        // check initial checkboxes
        $.each(classSingleCb, function (j, dt) {
            const index = arrSavedInstance.indexOf($(this).val());
            if (index > -1) {
                $(dt).prop('checked', true)
                currentChecked.push($(this).val())
            }
        });

        // handle for single checkbox
        classSingleCb.click(function() {
            var isChecked = $(this).prop('checked')
            if (isChecked) {
                arrSavedInstance.push($(this).val())
                currentChecked.push($(this).val())
            } else {
                const index = arrSavedInstance.indexOf($(this).val());
                const index2 = currentChecked.indexOf($(this).val());
                if (index > -1) {
                    arrSavedInstance.splice(index, 1)
                }
                if (index2 > -1) {
                    currentChecked.splice(index2, 1)
                }
            }
            inputHidden.val(JSON.stringify(arrSavedInstance))
            const arrayContains = currentChecked.every(element => {
                return arrSavedInstance.includes(element);
              });
    
            var shouldCheckedAll = arrayContains && currentChecked.length > 0 && classSingleCb.length == currentChecked.length
            classAllCb.prop('checked', shouldCheckedAll)
        })

        // handle for multiple checkbox
        classAllCb.click(function() {
            var isChecked = $(this).prop('checked')
            currentChecked = []

            if (isChecked) {
                $.each(classSingleCb, function (j, dt) {
                    const index = arrSavedInstance.indexOf($(this).val());
                    if (index == -1) {
                        arrSavedInstance.push($(this).val())
                        currentChecked.push($(this).val())
                    }
                });
            }else{
                arrSavedInstance = []
            }
            classSingleCb.prop('checked', isChecked)
            inputHidden.val(JSON.stringify(arrSavedInstance))
        })

        const arrayContains = currentChecked.every(element => {
            return arrSavedInstance.includes(element);
          });

        var shouldCheckedAll = arrayContains && currentChecked.length > 0 && classSingleCb.length == currentChecked.length
        classAllCb.prop('checked', shouldCheckedAll)
}

// define your column formatting here
function formattingColumn(items, col, dcfs) {
    var dcf = (dcfs) ? dcfs[col] : ""
    var item = items[col]
    var mItem = item ? item : "";

    if (mItem.length > 100) {
        mItem = mItem.substr(0,80)+"..."
    }
    if (typeof item === "number") {
        mItem = item
    }
    if (dcf === "toRupiah") {
        mItem = formatToRupiah(item)
    }

    return mItem
}

const formatToRupiah = (number)=>{
    return new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR"
    }).format(number);
  }
