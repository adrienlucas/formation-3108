<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * Class TotoListener
 *
 * @package App\EventListener
 */
class TotoListener {

  public function onKernelResponse(ResponseEvent $event): void {
    $response = $event->getResponse();
    $response->headers->add(
      [
        'tete-a-toto' => 'boule-a-zero',
      ]
    );
  }
}
