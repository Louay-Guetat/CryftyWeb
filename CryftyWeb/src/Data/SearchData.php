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
     * @var null|integer
     */
    public $triPrix;

    /**
     * @var null|integer
     */
    public $triLikes;

    /**
     * @return string
     */
    public function getQ(): string
    {
        return $this->q;
    }

    /**
     * @param string $q
     */
    public function setQ(string $q): void
    {
        $this->q = $q;
    }

    /**
     * @return array
     */
    public function getCurrency(): array
    {
        return $this->currency;
    }

    /**
     * @param array $currency
     */
    public function setCurrency(array $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @return array
     */
    public function getSubCategories(): array
    {
        return $this->subCategories;
    }

    /**
     * @param array $subCategories
     */
    public function setSubCategories(array $subCategories): void
    {
        $this->subCategories = $subCategories;
    }

    /**
     * @return int|null
     */
    public function getMax(): ?int
    {
        return $this->max;
    }

    /**
     * @param int|null $max
     */
    public function setMax(?int $max): void
    {
        $this->max = $max;
    }

    /**
     * @return int|null
     */
    public function getMin(): ?int
    {
        return $this->min;
    }

    /**
     * @param int|null $min
     */
    public function setMin(?int $min): void
    {
        $this->min = $min;
    }

    /**
     * @return int
     */
    public function isTriPrix(): ?int
    {
        return $this->triPrix;
    }

    /**
     * @param int $triPrix
     */
    public function setTriPrix(int $triPrix): void
    {
        $this->triPrix = $triPrix;
    }

    /**
     * @return int
     */
    public function isTriLikes(): ?int
    {
        return $this->triLikes;
    }

    /**
     * @param int $triLikes
     */
    public function setTriLikes(int $triLikes): void
    {
        $this->triLikes = $triLikes;
    }


}