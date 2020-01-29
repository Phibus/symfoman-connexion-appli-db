<?php

namespace App\Controller\Api;

use App\Provider\Api\UserProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/api/user", name="api_user", methods={"GET"})
     */
    public function findAll(UserProvider $provider)
    {
        $users = $provider->findAll();

        return new JsonResponse($users);
    }

    /**
     * @Route("/api/user/find-by", name="api_user_find_by", methods={"POST"})
     */
    public function findBy(Request $request, UserProvider $provider)
    {
        $params = json_decode($request->getContent(), true);
        $users = $provider->findBy($params);

        return new JsonResponse($users);
    }
}