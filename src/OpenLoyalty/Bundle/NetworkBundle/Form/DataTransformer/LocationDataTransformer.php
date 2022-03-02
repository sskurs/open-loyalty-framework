<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\NetworkBundle\Form\DataTransformer;

use Assert\AssertionFailedException;
use OpenLoyalty\Component\Network\Domain\Model\Location;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class LocationDataTransformer.
 */
class LocationDataTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function transform($value)
    {
        if (null == $value) {
            return;
        }

        if (!$value instanceof Location) {
            throw new InvalidArgumentException();
        }

        return $value->serialize();
    }

    /**
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function reverseTransform($value)
    {
        if ($value == null) {
            return;
        }

        try {
            return Location::deserialize($value);
        } catch (AssertionFailedException $e) {
            return new Location(
                isset($value['region']) ? $value['province'] : null,
                isset($value['city']) ? $value['city'] : null,
                isset($value['postal']) ? $value['postal'] : null,
                isset($value['country']) ? $value['country'] : null,
                isset($value['lat1']) ? $value['lat1'] : null,
                isset($value['long1']) ? $value['long1'] : null,
                isset($value['lat2']) ? $value['lat2'] : null,
                isset($value['long2']) ? $value['long2'] : null,
                true
            );
        }
    }
}
