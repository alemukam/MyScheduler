<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupEvent_GroupName extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this -> id,
            'group_id' => $this -> group_id,
            'group_name' => $this -> group_name,
            'date' => $this -> date,
            'title' => $this -> title,
            'start_time' => $this -> start_time
        ];
    }
}
