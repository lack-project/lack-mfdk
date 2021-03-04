<?php


namespace Lack\Mfdk\Type;


class T_Manifest_Route
{
    /**
     * @var string|null
     */
    public $route = null;

    /**
     * @var string
     */
    public $target;

    /**
     * @var string[]
     */
    public $files = [];

}