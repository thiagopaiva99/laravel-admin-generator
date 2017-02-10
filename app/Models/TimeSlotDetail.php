<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlotDetail extends Model
{
    /**
     * Nome da tabela
     *
     * @var string
     */
    public $table = 'time_slot_details';

    /**
     * Atributos que devem ser escondidos na hora de transformar para JSON
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /*
     * Relacionamentos entre modelos
     */

    /**
     * Este modelo pertence à um TimeSlot
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeSlot() {
        return $this->belongsTo(TimeSlot::class);
    }

    /**
     * Este modelo pertence à um HealthPlan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function healthPlan() {
        return $this->belongsTo(HealthPlan::class);
    }
}
