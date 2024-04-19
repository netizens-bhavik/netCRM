<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory,SoftDeletes,HasUuids;

    protected $fillable = ['id','name', 'email', 'avtar', 'country_id', 'state_id', 'city_id', 'zipcode', 'phone_no', 'company_name', 'company_website', 'company_address', 'company_logo', 'tax', 'gst_vat', 'office_mobile', 'address', 'added_by', 'note'];

    /**
     * Get the Country associated with the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    /**
     * Get the state that owns the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class,'state_id');
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    public function delete()
    {
        // Delete associated projects
        $this->projects->each->delete();

        // Then delete the client itself
        return parent::delete();
    }
}
