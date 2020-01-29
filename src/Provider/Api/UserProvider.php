<?php


namespace App\Provider\Api;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Json;

class UserProvider
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * UserProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @return string
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function findAll()
    {
        $users = $this->userRepo->findAll();

        return $this->getSerializer()->normalize($users);
    }

    /**
     * @param array $params
     */
    public function findBy(array $params)
    {
        $user = $this->userRepo->findOneBy($this->getParams($params));

        return $this->getSerializer()->normalize($user);
    }

    /**
     * @param array $params
     * @return array
     */
    private function getParams(array $params)
    {
        return array_filter($params, function ($key) {
            return in_array($key, ['id', 'username', 'password', 'roles']);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param array $encoders
     * @param array $normalizers
     * @return Serializer
     */
    private function getSerializer(array $encoders = [], array $normalizers = [])
    {
        if (empty($encoders)) {
            $encoders = [new JsonEncoder()];
        }

        if (empty($normalizers)) {
            $normalizers = [new ObjectNormalizer()];
        }

        return new Serializer($normalizers, $encoders);
    }
}