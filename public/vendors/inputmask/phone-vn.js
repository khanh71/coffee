/*!
* phone-codes/phone-ru.js
* https://github.com/RobinHerbots/Inputmask
* Copyright (c) 2010 - 2017 Robin Herbots
* Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
* Version: 3.3.11
*/

!function(factory) {
    "function" == typeof define && define.amd ? define([ "../inputmask" ], factory) : "object" == typeof exports ? module.exports = factory(require("../inputmask")) : factory(window.Inputmask);
}(function(Inputmask) {
    return Inputmask.extendAliases({
        phonevn: {
            alias: "abstractphone",
            countrycode: "0",
            phoneCodes: [{
                mask: "###.###.####",
                cc: "VN",
                cd: "Vietnam",
                desc_en: "",
                name_ru: "",
                desc_ru: ""
            } ]
        }
    }), Inputmask;
});