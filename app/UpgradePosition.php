<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UpgradePosition extends Model
{
    protected $table = 'upgrade_positions';
    public $timestamps = false;
    public $fillable = ['position' , 'name' , 'price'];
    public function upgrades(){
        return $this->hasMany(Upgrade::class , 'upgrade_position_id');
    }
}
