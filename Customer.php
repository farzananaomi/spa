<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\FilterPaginatedOrder;

class Customer extends Model
{
    use FilterPaginatedOrder;


    protected $fillable = [
        'company', 'email', 'name', 'phone', 'address',

    ];
    //white list of filter-able column

    protected $filter = [
        'id', 'company', 'email', 'name', 'phone', 'address', 'created_at',
    ];

    public static function initialize()
    {
        [
            'company' => '', 'email' => '', 'name' => '', 'phone' => '', 'address' => '',
        ];
    }




    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}