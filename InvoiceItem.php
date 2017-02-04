<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\FilterPaginatedOrder;

class InvoiceItem extends Model{


 use FilterPaginatedOrder;

    protected $fillable=[
        'description','quantity','unit_price',

    ];
    public static function initialize(){
        return[
            'description'=>'',
            'quantity'=>'',
            'unit_price'=>''
        ];
    }
}
