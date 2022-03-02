<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\NetworkBundle\Event\Listener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use OpenLoyalty\Bundle\SettingsBundle\Service\SettingsManager;
use OpenLoyalty\Component\Network\Domain\Network;

/**
 * Class NetworkSerializationListener.
 */
class NetworkSerializationListener implements EventSubscriberInterface
{
    /**
     * @var SettingsManager
     */
    protected $settingsManager;

    /**
     * NetworkSerializationListener constructor.
     *
     * @param SettingsManager $settingsManager
     */
    public function __construct(SettingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.post_serialize', 'method' => 'onPostSerialize'),
        );
    }

    /**
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event): void
    {
        /** @var Network $network */
        $network = $event->getObject();

        if ($network instanceof Network) {
            $currency = $this->settingsManager->getSettingByKey('currency');
            $currency = $currency ? $currency->getValue() : 'USD';
            $event->getVisitor()->addData('currency', $currency);
        }
    }
}
