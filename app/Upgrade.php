<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upgrade extends Model
{
    protected $table='upgrades';

    public function upgradable(){
        return $this->morphTo('upgradable', 'upgradable_type', 'upgradable_id');
    }
    public function position(){
        return $this->belongsTo(UpgradePosition::class , 'upgrade_position_id');
    }
}
