<?php namespace Everalan\Api;

use League\Fractal\Serializer\ArraySerializer;

class NoDataSerializer extends ArraySerializer {
    public function collection($resourceKey, array $data)
    {
        return $data;
    }
}
