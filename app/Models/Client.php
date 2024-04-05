<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name', 'email', 'profile', 'country_id', 'state_id', 'city_id', 'postcode', 'mobile_no', 'company_name', 'company_website', 'company_address', 'company_logo', 'tax', 'gst_vat', 'office_mobile', 'address', 'added_by', 'note'];
}
