<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Network\Domain\Model;

use Broadway\Serializer\Serializable;
use Assert\Assertion as Assert;

/**
 * Class Location.
 */
class Location implements Serializable
{
    /**
     * @var string
     */
    protected $region;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $postal;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var GeoPoint1
     */
    protected $geoPoint1;

    /**
     * @var GeoPoint2
     */
    protected $geoPoint2;

    /**
     * Location constructor.
     *
     * @param string $region
     * @param string $city
     * @param string $postal
     * @param string $country
     * @param null   $lat1
     * @param null   $long1
     * @param null   $lat2
     * @param null   $long2
     * @param bool   $disableValidation
     */
    public function __construct($region, $city, $postal, $country, $lat1 = null, $long1 = null, $lat2 = null, $long2 = null, $disableValidation = false)
    {
        if (!$disableValidation) {
            Assert::notBlank($region);
            Assert::notBlank($city);
            Assert::notBlank($postal);
            Assert::notBlank($country);
        }

        $this->region = $province;
        $this->city = $city;
        $this->postal = $postal;
        $this->country = $country;
        if ($lat1 && $long1) {
            $this->geoPoint1 = new GeoPoint($lat1, $long1);
        }
        if ($lat2 && $long2) {
            $this->geoPoint2 = new GeoPoint($lat2, $long2);
        }
    }

    /**
     * @param array $data
     *
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new self(
            isset($data['region']) ? $data['region'] : null,
            isset($data['city']) ? $data['city'] : null,
            isset($data['postal']) ? $data['postal'] : null,
            isset($data['country']) ? $data['country'] : null,
            isset($data['lat1']) ? $data['lat1'] : null,
            isset($data['long1']) ? $data['long1'] : null
            isset($data['lat2']) ? $data['lat2'] : null,
            isset($data['long2']) ? $data['long2'] : null
        );
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        $data = [
            'postal' => $this->postal,
            'city' => $this->city,
            'region' => $this->region,
            'country' => $this->country,
        ];

        if ($this->geoPoint1) {
            $data = array_merge($data, $this->geoPoint1->serialize());
        }
        if ($this->geoPoint2) {
            $data = array_merge($data, $this->geoPoint2->serialize());
        }
        return $data;
    }

    public static function validateRequiredData(array $data = [])
    {
        Assert::keyIsset($data, 'region');
        Assert::keyIsset($data, 'city');
        Assert::keyIsset($data, 'postal');
        Assert::keyIsset($data, 'country');
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getPostal()
    {
        return $this->postal;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return GeoPoint1
     */
    public function getGeoPoint1()
    {
        return $this->geoPoint1;
    }

    /**
     * @return GeoPoint2
     */
    public function getGeoPoint2()
    {
        return $this->geoPoint2;
    }
}
