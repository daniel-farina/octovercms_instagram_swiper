<?php namespace danielfarina\instagram\classes;

trait MakeKeyTrait
{
    public function makeKey()
    {
        $key = '';

        foreach($this->defineProperties() as $k => $v)
        {
            $key .= $this->property($k);
        }

        return $key;
    }
}