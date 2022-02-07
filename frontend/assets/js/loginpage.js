
var secqureInterval = setInterval(function () {
    // const head = document.getElementsByTagName("head")[0];
    // head.innerHTML += `<link rel="stylesheet" href=`+ secqureajax.csslink + ` . admin/assets/css/style.css'" />`;

    var login = document.getElementById('login');
    if (login != null) {
        clearInterval(secqureInterval);

        login.innerHTML = "";
        login.insertAdjacentHTML("afterend", '<div id="secuuthForm"></div>');

        var x = document.getElementsByClassName("login");
        var i;
        for (i = 0; i < x.length; i++) {
            x[i].classList.remove("login");
        }

        const secqure = new Secuuth({
            keyId: secqureajax.apikey,
            profileName: "Default",
            containerId: "secuuthForm",
            onSubmit: (payload) => {
                jQuery(document).ready(function($) {
                var data = {
                    action : 'secqure_login',
                    'accessToken': payload.tokens.accessToken,
                    'userId': payload.user.userId
                };
                setInterval(function () {
                    $.post({
                        url: secqureajax.ajax_url, 
                        data: data, 
                        success: function (resp) {
                            console.log("Response: " + resp)
                            window.location.href = secqureajax.redirect;
                        }
                    });
                }, 2000);
            });
            },
        });
    }
}, 200)