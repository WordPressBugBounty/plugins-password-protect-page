var ppwUtils=function(e){var t={};function r(n){if(t[n])return t[n].exports;var o=t[n]={i:n,l:!1,exports:{}};return e[n].call(o.exports,o,o.exports,r),o.l=!0,o.exports}return r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)r.d(n,o,function(t){return e[t]}.bind(null,o));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="./dist/",r(r.s=0)}([function(e,t,r){"use strict";r.r(t);var n=function(e,t,r){document.getElementById(e).select(),document.execCommand("copy"),toastr&&toastr.success(t,r)};var o=function(e){e("#ppw_subscribe_form").submit(function(t){t.preventDefault();const r=e("#ppw_email_subscribe").val().trim();/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(r)?(e("#ppw_subscribe_button").val("Saving..."),function(e,t,r){var n={action:"ppw_free_subscribe_request",settings:t,security_check:e("#ppw_subscribe_form_nonce").val()};!function(e,t,r){let n=!1;window.ppw_general_data?n=ppw_general_data.ajax_url:window.ppw_entire_site_data&&(n=ppw_entire_site_data.ajax_url),n&&e.ajax({url:n,type:"POST",data:t,timeout:5e3,success:function(e){r(e,null)},error:function(e){r(null,e)}})}(e,n,r)}(e,{ppw_email:r},function(t,r){t?(e("#ppw_subscribe_form").hide(),e("#ppw_subscribe_form_success").show()):r&&(400===r.status?(e(".ppw_subscribe_error").text(r.responseJSON.message),e(".ppw_subscribe_error").show("slow")):(e(".ppw_subscribe_error").text("Oops! Something went wrong. Please reload the page and try again."),e(".ppw_subscribe_error").show("slow"))),e("#ppw_subscribe_button").val("Get Lucky")})):(e(".ppw_subscribe_error").show("slow"),e("#ppw_email_subscribe").focus(),e("#ppw_subscribe_button").val("Get Lucky"))})};r.d(t,"copy",function(){return n}),function(e){e(function(){o(e)})}(jQuery)}]);