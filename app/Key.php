<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Keygen\Keygen;

class Key extends Model
{
    private $key;
    protected $table = "keys";
    protected $fillable = ['key'];


    public function setKey($key): void
    {
        $this->key = $key;
    }

    public function getKey(): int
    {
        return $this->key;
    }
}

