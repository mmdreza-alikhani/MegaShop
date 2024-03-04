const progress = document.querySelector('.progress-bar')

window.addEventListener('scroll', ()=>{
    const winScroll = window.pageYOffset;
    const height = document.documentElement.scrollHeight - window.innerHeight;
    const scrolled = (winScroll/height) * 100;
    progress.style.width = `${scrolled}%`
})

const login_modal = $(".login-modal")
const forget_password_modal = $(".forget-password-modal")
const register_modal = $(".register-modal")

$(".forget_password").click(function () {
    register_modal.hide()
    login_modal.hide()
    forget_password_modal.show()
})
$(".back_to_login").click(function () {
    forget_password_modal.hide()
    register_modal.hide()
    login_modal.show()
})
$(".register").click(function () {
    forget_password_modal.hide()
    login_modal.hide()
    register_modal.show()
})

// window.toPersianNum = function (num, dontTrim) {
//
//     var i = 0,
//
//         dontTrim = dontTrim || false,
//
//         num = dontTrim ? num.toString() : num.toString().trim(),
//         len = num.length,
//
//         res = '',
//         pos,
//
//         persianNumbers = typeof persianNumber == 'undefined' ?
//             ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'] :
//             persianNumbers;
//
//     for (; i < len; i++)
//         if ((pos = persianNumbers[num.charAt(i)]))
//             res += pos;
//         else
//             res += num.charAt(i);
//
//     return res;
// }

// $("#ratingStars").rating({
//     "half":true,
//     "color":"#dd163b",
//     "click": function (e) {
//         console.log(e); // {stars: 3, event: E.Event}
//         alert(e.stars); // 3
//     }
// });
var loadFile = function (event) {
    var image = document.getElementById("output");
    image.src = URL.createObjectURL(event.target.files[0]);
};
