<?php

namespace App\Service\OneC\Contracts;

interface OneCServiceContract
{
    public function getClient(): OneCClientContract;
}