<?php
namespace App\Controller;
use App\Repository\LanguageRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Language;

/**
 * Class LanguageController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class LanguageController
{
    private $languageRepository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * @Route("language", name="add_language", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $langKey = $data['langKey'];

        if (empty($name) || empty($langKey)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        //creamos la entidad language
        $language = new Language();
        $language
            ->setName($name)
            ->setLangKey($langKey);

        $language = $this->languageRepository->save($language);

        return new JsonResponse($language->toJson(), Response::HTTP_CREATED);

    }

    /**
     * @Route("language/{id}", name="get_one_language", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $language = $this->languageRepository->findOneBy(['id' => $id]);
        
        if (empty($language)) {
            return new JsonResponse(null, Response::HTTP_OK);
        }

        return new JsonResponse($language->toJson(), Response::HTTP_OK);


    }

    /**
     * @Route("language", name="get_all_languages", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $languages = $this->languageRepository->findAll();
        $data = [];

        foreach ($languages as $language) {
            $data[] = $language->toJson();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("language/{id}", name="update_language", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $language = $this->languageRepository->findOneBy(['id' => $id]);
        if (empty($language)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $language->setName($data['name']);
        empty($data['langKey']) ? true : $language->setLangKey($data['langKey']);

        $this->languageRepository->update($language);

		return new JsonResponse($language->toJson(), Response::HTTP_OK);
    }

    /**
     * @Route("language/{id}", name="delete_language", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $language = $this->languageRepository->findOneBy(['id' => $id]);
        if (empty($language)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->languageRepository->remove($language);

        return new JsonResponse(true, Response::HTTP_OK);

    }
}
?>