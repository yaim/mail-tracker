<?php

if (!function_exists('domDocument')) {
    function domDocument($html)
    {
        $domDocument = new DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $domDocument->loadHTML($html);
        libxml_use_internal_errors($internalErrors);

        return $domDocument;
    }
}
