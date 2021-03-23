<?php namespace Everalan\Api;

use League\Fractal\Serializer\ArraySerializer;

class NoDataSerializer extends ArraySerializer {
    public function collection($resourceKey, array $data)
    {
        if($resourceKey === false) {
            return $data;
        }else{
            return [$resourceKey ?: 'data' => $data];
        }
    }
}
