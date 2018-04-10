<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function getErrorMessages($errorString) {
    $errors = explode("\n", $errorString);
    unset($errors[count($errors) - 1]);
    return $errors;
}

function getErrorMessagesHtml($errorString) {
    $message = '<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error(s):</strong><ul>';
    $message .= $errorString;
    $message .= '</ul></div>';

    return $message;
}

function getListItemHtml($message) {
    return '<li>' . $message . '</li>';
}

function convertDate($dateString) {
    return implode('-', array_reverse(explode('-', $dateString)));
}
