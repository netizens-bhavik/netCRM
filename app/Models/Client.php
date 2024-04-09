<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory,SoftDeletes,HasUuids;

    protected $fillable = ['id','name', 'email', 'avtar', 'country_id', 'state_id', 'city_id', 'zipcode', 'phone_no', 'company_name', 'company_website', 'company_address', 'company_logo', 'tax', 'gst_vat', 'office_mobile', 'address', 'added_by', 'note'];
}
