<?php


namespace Lack\Mfdk\Type;


class T_Manifest
{
    /**
     * @var string
     * @internal
     */
    public $baseUrl = "";

    /**
     * @var T_Manifest_Route[]
     */
    public $routes = [];


    /**
     * @var T_Manifest_Nav[]
     */
    public $naviLeft = [];


    /**
     * @var T_Manifest_Nav[]
     */
    public $naviTop= [];

    /**
     * @var T_Manifest_Nav[]
     */
    public $naviAccount= [];

}