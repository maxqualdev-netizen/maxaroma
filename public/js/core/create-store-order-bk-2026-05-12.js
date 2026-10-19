document.addEventListener("DOMContentLoaded", function () {
    const tabLinks = document.querySelectorAll('#tabOptions .nav-link');
    const tabPanes = document.querySelectorAll('#tabContent .tab-pane');

    tabLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();

            // Remove 'active' from all tabs and tab panes
            tabLinks.forEach(link => link.classList.remove('active'));
            tabPanes.forEach(pane => {
                pane.classList.remove('show', 'active');
            });

            // Activate clicked tab
            this.classList.add('active');

            // Get the associated pane via href
            const targetId = this.getAttribute('href');
            const targetPane = document.querySelector(targetId);
            if (targetPane) {
                targetPane.classList.add('show', 'active');
            }
        });
    });
});
/*
var isHtml5QrCodeLibLoaded = false;
var html5QrcodeScanner = null;

function loadHtml5QrCodeLib()
{
	if (!isHtml5QrCodeLibLoaded)
	{
		isHtml5QrCodeLibLoaded = true;

		loadScriptSequentially(site_url+'public/js/core/html5-qrcode.min.js')
			.then(() => {
				html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                    fps: 10,
                    qrbox: 250,
                    formatsToSupport: [
                        Html5QrcodeSupportedFormats.CODE_128,
                        Html5QrcodeSupportedFormats.UPC_A,
                        Html5QrcodeSupportedFormats.UPC_E
                    ],
                    supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA],
                    videoConstraints: {
                        facingMode: { exact: "environment" }
                    },
                    useBarCodeDetectorIfSupported: true,
                    rememberLastUsedCamera: true,
                    showTorchButtonIfSupported: false,
                    showZoomSliderIfSupported: true,
                    defaultZoomValueIfSupported: 1.5
                },false);
				html5QrcodeScanner.render(onScanSuccess, onScanError);
			})
			.catch((error) => { });
	}
}
function loadScriptSequentially(file) {
  return new Promise((resolve, reject) => {
    const newScript = document.createElement('script');
    newScript.setAttribute('src', file);
    newScript.setAttribute('async', 'true');

    newScript.onload = () => {
      resolve(); // Resolve the promise
    };
    newScript.onerror = () => {
      reject(new Error(`Error loading script: ${file}`));
    };

    document.head.appendChild(newScript);
  });
}
function beep() {
    var snd = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");
    snd.play();
}
function onScanSuccess(productSKU)
{
    // Notify user that a barcode has been found.
    //navigator.vibrate(200);
    beep();
    //resetHtml5QrScanner();
    //alert(productSKU);
    AddToCartStore( productSKU, 1 );

    let shouldPauseVideo = true;
    let showPausedBanner = false;
    const isCameraScan = html5QrcodeScanner.getState() === 2;
    if (isCameraScan) {
        html5QrcodeScanner.pause(shouldPauseVideo, showPausedBanner);
        setTimeout(() => {
            html5QrcodeScanner.resume();
        }, 3000);
    }
}

function onScanError(errorMessage)
{
    console.log(errorMessage);
    //handle scan error
    //alert(errorMessage);
}
function resetHtml5QrScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear()
            .then(() => {
                html5QrcodeScanner = null;
                isHtml5QrCodeLibLoaded = false;

                // Re-load scanner fresh
                loadHtml5QrCodeLib();
            })
            .catch(err => {
                console.error("reset failed", err);
            });
    }
}
*/
var scannerStarted = false;

$('.custom-tabnav .nav-link').click(function (e) {

    var target = $(e.target).attr("href");
    console.log(target);
    if (target === "#tab-camera")
    {
        if (!scannerStarted)
        {
            initCameraPermission();
            scannerStarted = true;
        }
    }
});
const html5QrCode = new Html5Qrcode("reader");
let cameraRunning = false;
function loadScriptSequentially(file) {
  return new Promise((resolve, reject) => {
    const newScript = document.createElement('script');
    newScript.setAttribute('src', file);
    newScript.setAttribute('async', 'true');

    newScript.onload = () => {
      resolve(); // Resolve the promise
    };
    newScript.onerror = () => {
      reject(new Error(`Error loading script: ${file}`));
    };

    document.head.appendChild(newScript);
  });
}
function startScanner()
{
    const config = {
        fps: 10,
        qrbox: { width: 320, height: 150 },
        formatsToSupport: [
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.UPC_A,
            Html5QrcodeSupportedFormats.UPC_E
        ]
    };

    html5QrCode.start(
        { facingMode: "environment" }, // back camera
        config,
        onScanSuccess,
        onScanFailure
    ).then(() => {
        cameraRunning = true;
    });
}

function restartScanner()
{
    html5QrCode.stop().then(() => {
        startScanner();
    }).catch(err => {
        console.log("Stop failed", err);
    });
}

function restartCamera() {

    if (html5QrCode.getState() === 2 || html5QrCode.getState() === 3) {

        html5QrCode.stop().then(() => {
            startCamera();
        });

    } else {

        startCamera();

    }
}

function beep()
{
    const audio = new Audio(
        "https://actions.google.com/sounds/v1/cartoon/wood_plank_flicks.ogg"
    );
    audio.play();
}
async function initCameraPermission() {
    try {
        await navigator.mediaDevices.getUserMedia({ video: true });
        startScanner();
    } catch (err) {
        console.error("Camera permission denied", err);
    }
}
function onScanSuccess(decodedText, decodedResult)
{
    console.log("Scanned:", decodedText);
    //$("#result").text(decodedText);
    // beep(
    beep();
    // optional vibration
    if ("vibrate" in navigator)
    {
        navigator.vibrate(150);
    }
    AddToCartStore( decodedText, 1 );
    html5QrCode.pause();

    setTimeout(() => {
        html5QrCode.resume();
    }, 2000);
    // reset scanning
    //restartScanner();
}

function onScanFailure(error) {
    console.log(error)
}
async function scanImage(file)
{
    try {
        // stop camera first
        const decodedText = await html5QrCode.scanFile(file, true);
        console.log("Image scan:", decodedText);
        AddToCartStore(decodedText, 1);
        beep();
        // // optional vibration
        // if ("vibrate" in navigator)
        // {
        //     navigator.vibrate(150);
        // }
    } catch (err) {
        console.log(err);
    } finally {
        // restart camera
        // setTimeout(() => {
        //     startScanner();
        // }, 300);
    }
}
Dropzone.autoDiscover = false;

const myDropzone = new Dropzone("#barcodeDropzone", {
    url: "/", // not used
    autoProcessQueue: false,
    maxFiles: 1,
    acceptedFiles: "image/*",
    init: function () {
        this.on("addedfile", async function(file) {
            scanImage(file);
            this.removeAllFiles(true);
        });
    }
});
$("#uploadBarcode").on("change", function ()
{
    const file = this.files[0];
    if (file) {
        scanImage(file);
    }
    this.value = ""; // allow same file upload again
});
$(document).ready(function ()
{
    //loadHtml5QrCodeLib();
    sku_auto_complete();
})

$("#btnimg").click(async function()
{
    $(".optbuttons").removeClass('divactive');
    if (html5QrCode.getState() === 2 || html5QrCode.getState() === 3) {
        await html5QrCode.stop();
    }
    $(this).parent().addClass('divactive');
    $("#divreader").hide();
    $("#divimagereader").show();
})
$("#btncam").click(function()
{
    $(".optbuttons").removeClass('divactive');
    $(this).parent().addClass('divactive');
    $("#divreader").show();
    $("#divimagereader").hide();
    startScanner();
});

function sku_auto_complete()
{
    $("#skuval").autocomplete({

        minLength: 2,

        source: function(request, response){
            $(".input-loader").show();
            $.ajax({
                url: site_url + "store/search-product",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data){
                    $(".input-loader").hide();
                    $(".dropdown-arrow").show();
                    if(data.length === 0)
                    {
                        response([{
                            label: "Product not found",
                            value: "",
                            notfound: true
                        }]);
                    }
                    else
                    {
                        response(data);
                    }

                }
            });

        },

        select: function(event, ui)
        {
            return false;
            // if(ui.item.notfound)
            //     return false;

            // // AddToCartStore(ui.item.sku, 1);
            // //$("#skuval").val("");
            // var $input = $("#skuval");
            // $input.val("");
            // $input.autocomplete("close");
            // $input.autocomplete("search", "");
            // $input.blur();
            // return false;
        }

    })
    .autocomplete("instance")._renderItem = function(ul, item)
    {

        if(item.notfound)
        {
            $(".dropdown-arrow").hide();
            return $("<li>")
                .append(`
                    <div style="
                        padding:8px;
                        color:red;
                        text-align:center;
                        font-weight:bold;
                        font-style:italic;
                    ">
                        Product not found
                    </div>
                `)
                .appendTo(ul);

        }

        var image = item.image ? item.image : site_url+"images/noimage-md.jpg";
        var price = item.price ? parseFloat(item.price).toFixed(2) : "0.00";
        var stock = item.stock !== undefined ? item.stock : 0;
        var exists_class = item.existsincart == 1 ? "bi-check-lg gr" : "bi-plus-lg";
        var short_desc = item.short_description ? item.short_description : "";
        var totalcount = item.totalResults !== undefined ? item.totalResults : 0;
        $("#sp_note").text(totalcount + " products found").css("padding","7px").show();

        var term = $("#skuval").val();

        function highlight(text){
			if(!text) return "";
			var regex = new RegExp("(" + term + ")", "gi");
			return text.replace(regex,'<span class="highlight-match">$1</span>');
		}

        var html = `

        <div class="autocomplete-row"
             data-sku="${item.sku}"
             style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                padding:8px;
                cursor:pointer;
            ">

            <div style="display:flex;gap:10px;align-items:flex-start; width:100%;">

                <img src="${image}"
                     style="
                        width:45px;
                        height:45px;
                        border:1px solid #ddd;
                        object-fit:cover;
                     ">

                <div class="auto-prdtext-row">
                    <div class="auto-leftprd-text">
                        <div class="auto-pricevalue">
                            ${highlight(item.value)}
                        </div>

                        <div class="auto-prdtext-sub">
                            ${highlight(short_desc)}
                        </div>

                        <div style="
                            font-size:11px;
                            color:#666;
                        ">
                            ${highlight(item.sku)}&nbsp; | &nbsp;${highlight(item.upc)}&nbsp; | &nbsp;${highlight(item.size)}
                        </div>
                    </div>

                    <div class="price-auto">

                            <div class="text-md-right">

                                <div class="auto-price-text">
                                    $${price}
                                </div>

                                <div class="auto-price-stock" style="

                                    font-size:11px;
                                    color:${stock > 0 ? '#28a745' : '#dc3545'};
                                ">
                                    Stock: ${stock}
                                </div>

                            </div>

                            <span class="cart-icon"
                                  data-sku="${item.sku}"
                                  style="font-size:18px;font-weight:bold;cursor:pointer; color:#000;">
                                  <i class="bi ${exists_class}" id="cls${item.sku}" aria-hidden="true"></i>
                            </span>

                        </div>

                </div>

            </div>

        </div>
        `;

        return $("<li>")
            .attr("data-sku", item.sku)
            .append(html)
            .appendTo(ul);
    };
}

$(document).on("click", ".ui-autocomplete li", function(){
    var sku = $(this).data("sku");
    if(sku)
    {
        AddToCartStore(sku, 1);
        var checkData = setInterval(function() {
            if ($('#show_success_msg').is(':visible') && $("#skuval").val() != "") {
                var input_offset = $("#skuval").offset();
                var input_height = $("#skuval").outerHeight();
                var new_top = input_offset.top + input_height;
                var new_left = input_offset.left;
                $("#ui-id-1").css({
                    top: new_top + "px",
                    left: new_left + "px",
                    display:"inline-block"
                });
                $("#cls"+sku).removeClass("bi-plus-lg").addClass("bi-check-lg gr");
                clearInterval(checkData);
            }
        }, 1000);

        // setTimeout(function() {
        //     var input_offset = $("#skuval").offset();
        //     var input_height = $("#skuval").outerHeight();

        //     var new_top = input_offset.top + input_height;// + 20;
        //     var new_left = input_offset.left;

        //     $("#ui-id-1").css({
        //         top: new_top + "px",
        //         left: new_left + "px",
        //         display:"inline-block"
        //     });
        // }, 2000);

        //$("#skuval").val("");

    }
});

$(document).on("click",".clear-input",function(){
    $("#skuval").val("").focus();
    $("#ui-id-1").hide();
    //$("#ui-id-1").css("display","none !important");
    $(".dropdown-arrow").hide();
    $("#sp_note").hide();
    $(this).hide();
});
$("#skuval").on("input blur", function(){
    if($(this).val().length > 0){
        $(".clear-input").show();
    }else{
        $("#ui-id-1").hide();
        $(".clear-input").hide();
        $(".dropdown-arrow").hide();
        $("#sp_note").hide();
    }
});
// $(".dropdown-arrow").on("click", function(){
//     if($("#skuval").val()!=''){
//         var checkData = setInterval(function() {
//             var input_offset = $("#skuval").offset();
//             var input_height = $("#skuval").outerHeight();
//             var new_top = input_offset.top + input_height;
//             var new_left = input_offset.left;
//             //alert(22);
//             $("#ui-id-1").css({
//                 top: new_top + "px",
//                 left: new_left + "px",
//                 display:"inline-block"
//             });
//             $("#cls"+sku).removeClass("bi-plus-lg").addClass("bi-check-lg gr");
//             clearInterval(checkData);
//         }, 500);
//     } else {
//         $(".dropdown-arrow").hide();
//         $("#ui-id-1").hide();
//     }
// });
$(".dropdown-arrow").on("click", function () {

    var skuVal = $("#skuval").val().trim();

    if (skuVal === "") {
        $(".dropdown-arrow").hide();
        $("#ui-id-1").hide();
        return;
    }

    setTimeout(function () {
        if ($("#skuval").val().trim() === "") {
            $("#ui-id-1").hide();
            return;
        }

        var input_offset = $("#skuval").offset();
        var input_height = $("#skuval").outerHeight();

        var new_top  = input_offset.top + input_height;
        var new_left = input_offset.left;

        $("#ui-id-1").css({
            top: new_top + "px",
            left: new_left + "px",
            display: "inline-block"
        });

        $("#cls" + sku)
            .removeClass("bi-plus-lg")
            .addClass("bi-check-lg gr");

    }, 300);

});

$(document).ready(function () {
    if (window.visualViewport) {
        var initialHeight = window.visualViewport.height;
        var keyboardOpen = false;
        var isSkuFocused = false;

        $("#skuval").on("focus", function () {
            isSkuFocused = true;
        });

        $("#skuval").on("blur", function () {
            isSkuFocused = false;
        });

        window.visualViewport.addEventListener("resize", function () {

            var currentHeight = window.visualViewport.height;

            if (currentHeight < initialHeight) {

                if (!keyboardOpen && isSkuFocused) {
                    keyboardOpen = true;

                    setTimeout(function () {
                        document.getElementById("skuval").scrollIntoView({
                            top: 150,
                            behavior: "smooth",
                            block: "center"
                        });
                    }, 150);
                }

            } else {
                keyboardOpen = false;
            }

        });

    }

});

// $(document).ready(function () {
//     if (window.visualViewport) {
//         var initialHeight = window.visualViewport.height;
//         var keyboardOpen = false;
//         window.visualViewport.addEventListener("resize", function () {
//             var currentHeight = window.visualViewport.height;
//             if (currentHeight < initialHeight) {
//                 if (!keyboardOpen) {
//                     keyboardOpen = true;
//                     setTimeout(function () {
//                         window.scrollBy({
//                             top: 150,
//                             behavior: "smooth"
//                         });
//                     }, 100);
//                 }
//             } else {
//                 keyboardOpen = false;
//             }
//         });
//     }
// });

// $(document).ready(function () {
//     if (window.visualViewport) {
//         var initialHeight = window.visualViewport.height;
//         window.visualViewport.addEventListener("resize", function () {
//             if (window.visualViewport.height < initialHeight) {
//                 // alert("Keyboard opened");
//                 // console.log("Keyboard opened");
//             } else {
//                 // alert("Keyboard closed");
//                 // console.log("Keyboard closed");
//             }
//         });
//     }
// });
