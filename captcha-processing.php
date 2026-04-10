<?php


function verifyCaptcha($formData)
{

    if (!isset($formData['captcha_status']) || $formData['captcha_status'] !== 'solved') {
        return false;
    }
    return true;
}
