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
     * @return bool
     */
    public function isTriPrix(): bool
    {
        return $this->triPrix;
    }

    /**
     * @param bool $triPrix
     */
    public function setTriPrix(bool $triPrix): void
    {
        $this->triPrix = $triPrix;
    }

    /**
     * @return bool
     */
    public function isTriLikes(): bool
    {
        return $this->triLikes;
    }

    /**
     * @param bool $triLikes
     */
    public function setTriLikes(bool $triLikes): void
    {
        $this->triLikes = $triLikes;
    }


}