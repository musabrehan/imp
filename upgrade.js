let upgradeBtn = document.querySelector(".upgrade__btn");
let btnRing = document.querySelector(".btn-ring");


$("#upgrade__btn").on("click", (e) => {
    e.preventDefault();

    // $('#upgrade_pro_account').modal('hide');

    let lang = $("html").attr("lang") == "ar" ? "ar" : "en";

    let msg = $('#upgrade_pro_account_text').data('swal-msg');
    let image = $('#upgrade_pro_account_text').data('swal-image');
    let token = $('#upgrade_pro_account_text').data('swal-confirm-token');
    let confirm_action = $('#upgrade_pro_account_text').data('swal-confirm-route');
    let send_otp_action = $('#upgrade_pro_account_text').data('swal-send-otp-route');
    let success_image = $('#upgrade_pro_account_text').data('swal-success-icon');
    let error_image = $('#upgrade_pro_account_text').data('swal-error-icon');
    let failed_image = $('#upgrade_pro_account_text').data('swal-failed-icon');
    let opSuccess = $('#upgrade_pro_account_text').data('swal-success-msg');

    let confirmTxt = lang == "ar" ? "استمـرار" : "Continue";

    let cancelBtn = lang == "ar" ? "لا" : "No";


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
                text: confirmTxt,
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
    })
        .then((isConfirm) => {
            
            console.log('elo,', token);
            // return ;
            if (isConfirm) {
                var url = confirm_action;
                var response = $.post(url, { "_token": token }).then((v) => {
                    console.log('elo,', v.message);
                    if (v.message == 'success') {
                        swal({
                            content: {
                                element: "img",
                                attributes: {
                                    src: success_image,
                                    className: "swalIcon",
                                },
                            },
                            title: opSuccess,
                        }).then((v)=> {
                            if(v== true){
                                location.reload();

                            }
                        });
                    }
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
                    title: 'Fails',
                });
            }
        });
});
