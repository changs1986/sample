<?php
namespace NN\Interfaces;

Interface SmsInterface
{
    public function send($phone, $content);
}
