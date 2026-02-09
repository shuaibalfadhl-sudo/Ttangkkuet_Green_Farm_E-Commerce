<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankInfo extends Model
{
    protected $table = 'bank_info';

    protected $fillable = [
        'bank_name_one',
        'account_number_one',
        'account_holder_one',
        'bank_name_two',
        'account_number_two',
        'account_holder_two',
    ];
}
