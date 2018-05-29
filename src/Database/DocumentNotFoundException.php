<?php


namespace UltraStar\Database;


class DocumentNotFoundException extends \RuntimeException
{
    public function __construct(string $id)
    {
        $message = "Cant't find document. Id: {$id}";
        parent::__construct($message);
    }
}
