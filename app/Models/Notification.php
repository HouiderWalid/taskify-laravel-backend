<?php

namespace App\Models;

use App\Models\helpers\BaseModel;

class Notification extends BaseModel
{
    const TABLE_NAME = "notification";

    const id_attribute_name = "id";
    const title_attribute_name = "title";
    const description_attribute_name = 'description';
}
