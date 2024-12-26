<?php

namespace App\Validation;

class ValidEmailOrUsername
{
    public function valid_email_or_username($str)
    {
        
        if (filter_var($str, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        if (preg_match('/^[a-zA-Z0-9_]+$/', $str)) {
            return true;
        }

        return false;
    }
}
