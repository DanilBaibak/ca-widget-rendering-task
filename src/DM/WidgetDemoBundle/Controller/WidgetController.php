<?php

namespace DM\WidgetDemoBundle\Controller;

use DM\WidgetDemoBundle\Entity\User;
use DM\WidgetDemoBundle\Services\WidgetRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class WidgetController extends Controller
{
    /**
     * @Route("/widget/{hash}")
     */
    public function renderAction(Request $request, $hash)
    {
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository('DMWidgetDemoBundle:User')->findOneBy([
            'hash' => $hash,
            'status' => User::STATUS_ACTIVE
        ]);

        if(!$user) {
            throw new NotFoundHttpException();
        }

        /** @var WidgetRenderer $renderer */
        $renderer = $this->get('dm_widget_demo.widget_renderer');
        $definedOptions = array_intersect_key($request->query->all(), array_flip($renderer->getDefinedOptions()));

        try {
            $widget = $renderer->render($user, $definedOptions);
        }
        catch (InvalidOptionsException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new Response($widget, 200, [
            'Content-Type' => 'image/png'
        ]);
    }
}
