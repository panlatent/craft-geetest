/*
 * Geetest plugin for Craft 3
 *
 * @link https://github.com/panlatent/craft-geetest/
 * @copyright Copyright (c) 2018 Panlatent
 */
(function() {
    $("#password-field").after('<div id="geetest-embed-captcha"></div>');

    XMLHttpRequest.XCeptor.post(null, function(request, response) {
        var challenge = $('input[name=geetest_challenge]').val();
        var validate = $('input[name=geetest_validate]').val();
        var seccode = $('input[name=geetest_seccode]').val();

        request.data += '&geetest_challenge=' + challenge + '&geetest_validate=' + validate + '&geetest_seccode=' + seccode;
    });
})();