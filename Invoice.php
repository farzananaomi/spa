<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\FilterPaginatedOrder;

class Invoice extends Model
{
    use FilterPaginatedOrder;

    protected $fillable =
        [
            'customer_id', 'title', 'date', 'due_date', 'sub_total', 'discount', 'total', 'created_at',

            'customer.id', 'customer.company', 'customer.email', 'customer.name', 'customer.phone', 'customer.address',
            'customer.created_at',
        ];

    public static function initialize()
    {
        return [
            'customer_id' => 'select',
            'title'       => 'invoice for',
            'date'        => date('y-m-d'),
            'due_date'    => null,
            'discount'    => 0,
            'items'       => [
                InvoiceItem::initialize(),

            ],
        ];
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function customer(){

        return $this->belongsTo(Customer::class);
    }
}
