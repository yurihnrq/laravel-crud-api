<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'cpf', 'phone', 'email', 'address', 'note'];

    public function verifyCPF()
    {
        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $this->cpf);

        // Verifica se foi informado todos os dígitos corretamente.
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de dígitos repetidos. Ex: 111.111.111-11.
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF.
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}
