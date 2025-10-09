/**
 * jQuery Validation Plugin - Persian/Farsi Messages
 * تنظیمات و پیام‌های فارسی برای jQuery Validation
 */

// بهتره بجای تغییر فایل اصلی، این فایل رو بعد از jquery.validate.js لود کنی
// <script src="jquery.validate.min.js"></script>
// <script src="jquery.validate.messages_fa.js"></script>

jQuery.extend(jQuery.validator.messages, {
    required: "این فیلد الزامی است",
    remote: "لطفا این فیلد را تصحیح کنید",
    email: "لطفا یک ایمیل معتبر وارد کنید",
    url: "لطفا یک آدرس اینترنتی معتبر وارد کنید",
    date: "لطفا یک تاریخ معتبر وارد کنید",
    dateISO: "لطفا یک تاریخ معتبر وارد کنید (ISO)",
    number: "لطفا یک عدد معتبر وارد کنید",
    digits: "لطفا فقط عدد وارد کنید",
    creditcard: "لطفا یک شماره کارت اعتباری معتبر وارد کنید",
    equalTo: "لطفا مقدار یکسانی وارد کنید",
    extension: "لطفا فایلی با پسوند معتبر انتخاب کنید",
    maxlength: jQuery.validator.format("لطفا حداکثر {0} کاراکتر وارد کنید"),
    minlength: jQuery.validator.format("لطفا حداقل {0} کاراکتر وارد کنید"),
    rangelength: jQuery.validator.format("لطفا بین {0} تا {1} کاراکتر وارد کنید"),
    range: jQuery.validator.format("لطفا عددی بین {0} تا {1} وارد کنید"),
    max: jQuery.validator.format("لطفا عددی کوچکتر یا برابر {0} وارد کنید"),
    min: jQuery.validator.format("لطفا عددی بزرگتر یا برابر {0} وارد کنید"),
    step: jQuery.validator.format("لطفا مضربی از {0} وارد کنید"),

    // پیام‌های اضافی
    accept: "لطفا فایلی با فرمت معتبر انتخاب کنید",
    maxfilesize: jQuery.validator.format("حجم فایل نباید بیشتر از {0} باشد"),
    phone: "لطفا شماره تلفن معتبر وارد کنید",
    mobile: "لطفا شماره موبایل معتبر وارد کنید (09xxxxxxxxx)",
    nationalcode: "لطفا کد ملی معتبر وارد کنید",
    postalcode: "لطفا کد پستی معتبر وارد کنید (10 رقم)",
    iban: "لطفا شماره شبا معتبر وارد کنید"
});

// تنظیمات پیش‌فرض فارسی
jQuery.validator.setDefaults({
    errorElement: "span",
    errorClass: "error text-danger",
    validClass: "valid",

    highlight: function(element, errorClass, validClass) {
        $(element).addClass("is-invalid").removeClass("is-valid");
        $(element).closest(".form-group").addClass("has-error");
    },

    unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass("is-invalid").addClass("is-valid");
        $(element).closest(".form-group").removeClass("has-error");
    },

    errorPlacement: function(error, element) {
        // برای checkbox و radio
        if (element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    }
});

// متدهای اضافی برای اعتبارسنجی ایرانی
jQuery.validator.addMethod("mobile", function(value, element) {
    return this.optional(element) || /^09[0-9]{9}$/.test(value);
}, "لطفا شماره موبایل معتبر وارد کنید (09xxxxxxxxx)");

jQuery.validator.addMethod("nationalcode", function(value, element) {
    if (this.optional(element)) {
        return true;
    }

    value = value.replace(/\s/g, '');

    if (!/^\d{10}$/.test(value) || value === '0000000000') {
        return false;
    }

    const check = parseInt(value[9]);
    let sum = 0;

    for (let i = 0; i < 9; i++) {
        sum += parseInt(value[i]) * (10 - i);
    }

    const remainder = sum % 11;

    return (remainder < 2 && check === remainder) || (remainder >= 2 && check === 11 - remainder);
}, "لطفا کد ملی معتبر وارد کنید");

jQuery.validator.addMethod("postalcode", function(value, element) {
    return this.optional(element) || /^\d{10}$/.test(value.replace(/\s/g, ''));
}, "لطفا کد پستی معتبر 10 رقمی وارد کنید");

jQuery.validator.addMethod("iban", function(value, element) {
    if (this.optional(element)) {
        return true;
    }

    value = value.toUpperCase().replace(/\s/g, '');

    if (!/^IR\d{24}$/.test(value)) {
        return false;
    }

    // اعتبارسنجی IBAN با الگوریتم mod-97
    const rearranged = value.substring(4) + value.substring(0, 4);
    const numeric = rearranged.replace(/[A-Z]/g, function(char) {
        return char.charCodeAt(0) - 55;
    });

    let remainder = numeric.substring(0, 2);

    for (let i = 2; i < numeric.length; i += 7) {
        remainder = (remainder + numeric.substring(i, i + 7)) % 97;
    }

    return remainder === 1;
}, "لطفا شماره شبا معتبر وارد کنید (IR + 24 رقم)");

jQuery.validator.addMethod("phone", function(value, element) {
    return this.optional(element) || /^0\d{2,3}\d{8}$/.test(value.replace(/\s/g, ''));
}, "لطفا شماره تلفن معتبر وارد کنید");

jQuery.validator.addMethod("persiantext", function(value, element) {
    return this.optional(element) || /^[\u0600-\u06FF\s]+$/.test(value);
}, "لطفا فقط حروف فارسی وارد کنید");

jQuery.validator.addMethod("englishtext", function(value, element) {
    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
}, "لطفا فقط حروف انگلیسی وارد کنید");

jQuery.validator.addMethod("alphanumeric", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
}, "لطفا فقط حروف و اعداد انگلیسی وارد کنید");

jQuery.validator.addMethod("maxfilesize", function(value, element, param) {
    if (this.optional(element)) {
        return true;
    }

    if (element.files.length === 0) {
        return true;
    }

    const fileSize = element.files[0].size / 1024 / 1024; // به MB
    return fileSize <= param;
}, jQuery.validator.format("حجم فایل نباید بیشتر از {0} مگابایت باشد"));

// مثال استفاده:
/*
$("#myForm").validate({
    rules: {
        name: {
            required: true,
            persiantext: true,
            minlength: 3
        },
        mobile: {
            required: true,
            mobile: true
        },
        national_code: {
            required: true,
            nationalcode: true
        },
        postal_code: {
            required: true,
            postalcode: true
        },
        email: {
            required: true,
            email: true
        },
        password: {
            required: true,
            minlength: 8
        },
        password_confirmation: {
            required: true,
            equalTo: "#password"
        }
    },
    messages: {
        name: {
            required: "وارد کردن نام الزامی است",
            minlength: "نام باید حداقل 3 حرف باشد"
        }
    }
});
*/
