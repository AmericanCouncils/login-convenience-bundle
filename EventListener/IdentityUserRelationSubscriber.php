<?php

namespace AC\LoginConvenienceBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class IdentityUserRelationSubscriber implements EventSubscriber
{
    private $userModelClass;

    public function __construct($userModelClass)
    {
        $this->userModelClass = $userModelClass;
    }

    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        // Dynamically associate OpenIdIdentity with the app's user entity class

        $meta = $args->getClassMetadata();
        if ($meta->getName() != 'AC\LoginConvenienceBundle\Entity\OpenIdIdentity') {
            return;
        }

        $meta->mapManyToOne([
            'fieldName' => 'user',
            'targetEntity' => $this->userModelClass,
            'fetch' => 'eager'
        ]);
    }
}
