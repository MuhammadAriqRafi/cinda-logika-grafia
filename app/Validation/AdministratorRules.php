<?php

namespace App\Validation;

class AdministratorRules
{
    public function validate(string $str, string $fields, array $data)
    {
        $administratorModel = new Administrator();
        $administrator = $administratorModel->where('username', $data['username'])->first();

        if (!$administrator) return false;

        return password_verify($data['password'], $administrator['password']);
    }
}
