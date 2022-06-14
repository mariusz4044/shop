<?php
namespace App\ValueObject;

use App\Exception\CouldNotValidateUnsignedInt;

class ProductId extends AbstractValueObject
{
    protected function validate($value): void
    {
        if($value < 0 && !is_null($value)){
            throw new CouldNotValidateUnsignedInt;
        }
    }
}