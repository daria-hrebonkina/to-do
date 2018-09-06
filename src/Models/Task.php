<?php

namespace Models;


use core\Model;

class Task extends Model
{
    protected static $fillable = ['id', 'title', 'project_id', 'priority', 'date', 'completed'];

}