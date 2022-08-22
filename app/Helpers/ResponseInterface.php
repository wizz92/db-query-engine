<?php
namespace App\Helpers;

interface ResponseInterface
{
    public function setData($dataName, $data);

    public function setStatus($status);

    public function setErrors($errors);

    public function setAlerts($alerts);

    public function get();
}
