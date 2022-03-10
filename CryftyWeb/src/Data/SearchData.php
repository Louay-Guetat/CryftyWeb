<?php

namespace App\Data;

class SearchData
{

    /**
     * @var string
     */
    public $q ='';

    /**
     * @var array
     */
    public $currency=[];

    /**
     * @var array
     */
    public $categories = [];

    /**
     * @var array
     */
    public $subCategories =[];

    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;

    /**
     * @var boolean
     */
    public $triPrix;

    /**
     * @var boolean
     */
    public $triLikes;

}