<?php
namespace App\Controller;
use App\Repository\ContainerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Container;

/**
 * Class ContainerController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class ContainerController
{
    private $containerRepository;

    public function __construct(ContainerRepository $containerRepository)
    {
        $this->containerRepository = $containerRepository;

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age:86400');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token,  Accept, Authorization, X-Requested-With');

    }

    /**
     * @Route("container", name="add_container", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];

        if (empty($name)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        //creamos la entidad container
        $container = new Container();
        $container
            ->setName($name);

        $container = $this->containerRepository->save($container);

        return new JsonResponse($container->toJson(), Response::HTTP_CREATED);

    }

    /**
     * @Route("container/{id}", name="get_one_container", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $container = $this->containerRepository->findOneBy(['id' => $id]);
        
        if (empty($container)) {
            return new JsonResponse(null, Response::HTTP_OK);
        }


        return new JsonResponse($container->toJson(), Response::HTTP_OK);
    }

    /**
     * @Route("container", name="get_all_container", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $containers = $this->containerRepository->findAll();
        $data = [];

        foreach ($containers as $container) {
            $data[] = $container->toJson();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("container/{id}", name="update_container", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $container = $this->containerRepository->findOneBy(['id' => $id]);
        if (empty($container)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
       
        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $container->setName($data['name']);

        $this->containerRepository->update($container);

		return new JsonResponse($container->toJson(), Response::HTTP_OK);
    }

    /**
     * @Route("container/{id}", name="delete_container", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $container = $this->containerRepository->findOneBy(['id' => $id]);
        if (empty($container)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->containerRepository->remove($container);

        return new JsonResponse(true, Response::HTTP_OK);
    }
}

?>