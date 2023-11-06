let withdrawalBtn = document.querySelector(".withdrawal__btn");
let btnRing = document.querySelector(".btn-ring");

console.log(withdrawalBtn);

$("#withdrawalForm").on("submit", (e) => {
  $("#withdrawalIcon").hide();
  $(".withdrawal__btn").attr("disabled", true);
  $(".btn-ring").show();
});

// ----------     06 - Wallet Page     ----------
//Form Validation
(function () {
  "use strict";
  var forms = document.querySelectorAll(".needs-validation");
  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add("was-validated");
      },
      false
    );
  });
})();

if (document.getElementById("construction-license")) {
  let ConstructionLicense = document.getElementById("construction-license");
  let fileSelect = document.getElementsByClassName("file-upload-select")[0];
  fileSelect.onclick = function () {
    ConstructionLicense.click();
  };

  ConstructionLicense.onchange = function () {
    let filename = ConstructionLicense.files[0].name;
    let selectName = document.getElementsByClassName("file-select-name")[0];
    selectName.innerText = filename;
  };
}


/** Uncomment Here 

$("#withdrawal_btn").on("click", (e) => {
  e.preventDefault();

  $('#modalTwo').modal('hide');

  let lang            = $("html").attr("lang") == "ar" ? "ar" : "en";

  let msg             = $('#withdrawalForm').data('swal-msg');
  let image           = $('#withdrawalForm').data('swal-image');
  let token           = $('#withdrawalForm').data('swal-confirm-token');
  let confirm_action  = $('#withdrawalForm').data('swal-confirm-route');
  let send_otp_action = $('#withdrawalForm').data('swal-send-otp-route');
  let success_image   = $('#withdrawalForm').data('swal-success-icon');
  let error_image     = $('#withdrawalForm').data('swal-error-icon');
  let failed_image    = $('#withdrawalForm').data('swal-failed-icon');
  let opSuccess       = $('#withdrawalForm').data('swal-success-msg');

  let confirmTxt  = lang == "ar" ? "استمرار" : "Continue";
  let enterCode   = lang == "ar" ? "أدخل الرمز" : "Enter code";
  let opFailed    = lang == "ar" ? "فشلت العملية" : "Operation failed";
  let okBtn       = lang == "ar" ? "نعم" : "Yes";
  let cancelBtn   = lang == "ar" ? "لا" : "No";
  let textMsg     = lang == "ar"
    ? "لقد قمنا بإرسال رمز التأكيد إلى رقم الجوال المسجل مع حسابك"
    : "We sent the confirmation code to the registered mobile number with your account";

  swal({
    content: {
      element: "img",
      attributes: {
        src: image,
        className: "swalIcon",
      },
    },
    title: msg,
    buttons: {
      confirm: {
        text: okBtn,
        value: true,
        visible: true,
        className: "confirmBtn",
        closeModal: true,
      },
      cancel: {
        text: cancelBtn,
        value: false,
        visible: true,
        className: "cancelBtn",
        closeModal: true,
      },
    },
  }).then((isConfirm) => {
    if (isConfirm) {
      var url = confirm_action;
      $.post(url, {"_token": token});
      swal({
        text: textMsg,
        content: {
          element: "input",
          attributes: {
            id: "withdrawal_otp",
            name: "otp",
            placeholder: enterCode,
            type: "number",
          },
        },
        buttons: {
          confirm: {
            text: confirmTxt,
            value: true,
            visible: true,
            className: "confirmBtn sendCode",
            closeModal: true,
          },
        },
      }).then(() => {
        var url = send_otp_action;
        var otp = $('#withdrawal_otp').val();
        $.post(url,{_token: token, otp: otp})
          .done(function(data) {
              swal({
                  content: {
                      element: "img",
                      attributes: {
                      src: success_image,
                      className: "swalIcon",
                      },
                  },
                  title: opSuccess,
                  buttons: {
                      confirm: {
                          text: confirmTxt,
                          value: true,
                          visible: true,
                          },
                  },
              }).then(() => {
                  $("#withdrawalIcon").hide();
                  $(".withdrawal__btn").attr("disabled", true);
                  $(".btn-ring").show();
                  $('#modalTwo').modal('show');
                  $("#withdrawalForm").submit();
              });
        })
        .fail(function (response){
            var errors = response.responseJSON;
            swal({
                content: {
                    element: "img",
                    attributes: {
                    src: error_image,
                    className: "swalIcon",
                    },
                },
                title: errors.message,
            });
        });
      });
    } else {
      swal({
        content: {
          element: "img",
          attributes: {
            src: failed_image,
            className: "swalIcon",
          },
        },
        title: opFailed,
      });
    }
  });
});

 /**  * */