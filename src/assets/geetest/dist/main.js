/*
 * Geetest plugin for Craft 3
 *
 * @link https://github.com/panlatent/craft-geetest/
 * @copyright Copyright (c) 2018 Panlatent
 */

(function() {
    initGeetest({
        gt: geetest.gt,
        challenge: geetest.challenge,
        new_captcha: geetest.new_captcha,
        product: "embed", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
        offline: !geetest.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
        // 更多配置参数请参见：http://www.geetest.com/install/sections/idx-client-sdk.html#config
    }, function (captchaObj) {
        // 将验证码加到id为captcha的元素里，同时会有三个input的值：geetest_challenge, geetest_validate, geetest_seccode
        captchaObj.appendTo("#geetest-embed-captcha");
        // 更多接口参考：http://www.geetest.com/install/sections/idx-client-sdk.html
    });
})();