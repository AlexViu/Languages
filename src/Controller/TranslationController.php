<?php
namespace App\Controller;
use App\Repository\TranslationRepository;
use App\Repository\ContainerRepository;
use App\Repository\LanguageRepository;
use App\Entity\Translation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TranslationController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class TranslationController
{
    private $translationRepository;
    private $languageRepository;
    private $containerRepository;

    public function __construct(TranslationRepository $translationRepository, LanguageRepository $languageRepository, ContainerRepository $containerRepository)
    {
        $this->translationRepository = $translationRepository;
        $this->languageRepository = $languageRepository;
        $this->containerRepository = $containerRepository;

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age:86400');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token,  Accept, Authorization, X-Requested-With');
    }

    /**
     * @Route("translation", name="add_translation", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $containerId = $data['containerId'];
        $transKey = $data['transKey'];
        $translates = $data['translates'];

        if ( empty($transKey)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        // obtenemos el contenedor
        if($containerId != null){
            $container = $this->containerRepository->findOneBy(['id' => $containerId]);
        } else {
            $container = null;
        }
        
        

        foreach ($translates as $translate) {

            // obtenemos el idioma
            $lang = $this->languageRepository->findOneBy(['id' => $translate['langId']]);
            if(empty($lang)) {
                throw new NotFoundHttpException('El Lenguaje no es valido!');
            }
            
            //creamos la entidad trasnlation
            $translation = new Translation();
            $translation
                ->setContainer($container)
                ->setLang($lang)
                ->setTransKey($transKey)
                ->setValue($translate['value']);

            $translation = $this->translationRepository->save($translation);
        }
        return new JsonResponse("ok", Response::HTTP_CREATED);
    }

    /**
     * @Route("translation/{id}", name="get_one_translation", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $translation = $this->translationRepository->findOneBy(['id' => $id]);
        
        if (empty($translation)) {
            return new JsonResponse(null, Response::HTTP_OK);
        }

        

        return new JsonResponse($translation->toJson(), Response::HTTP_OK);
    }

    /**
     * @Route("translation", name="get_all_translation", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $translations = $this->translationRepository->findAll();
        $data = [];
        $array_data = [];

        foreach ($translations as $translation) {
           
            $array_aux_translate = [];
            $array_aux_translate['id'] = $translation->getId();
            $array_aux_translate['value'] = $translation->getValue();
            $array_aux_translate['langId'] = $translation->getLang()->getId();
            
            $array_data[$translation->getTransKey()]['translates'][$translation->getLang()->getLangKey()] = $array_aux_translate;
           if ($translation->getContainer() != null) {
            $array_data[$translation->getTransKey()]['container'] = $translation->getContainer()->getName();
           } else {
            $array_data[$translation->getTransKey()]['container'] = "";
           }
        }

        foreach($array_data as $key => $values) {
            $array_aux = [];
            $array_aux['transKey'] =  $key;
            $array_aux['container'] =  $values['container'];
            $array_aux['translate'] = $values['translates'];

            $data[] = $array_aux;
        }
        
        return new JsonResponse( $data, Response::HTTP_OK);
    }

    /**
     * @Route("translation/{id}", name="update_translation", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $translation = $this->translationRepository->findOneBy(['id' => $id]);

        if (empty($translation)) {
            throw new NotFoundHttpException('No se encuentra la traduccion!');
        }

        $data = json_decode($request->getContent(), true);

        if(!empty($data['containerId'])) {
            // obtenemos el grupo
            $container = $this->containerRepository->findOneBy(['id' => $data['containerId']]);
            if(empty($container)) {
                throw new NotFoundHttpException('El contenedor no es valido!');
            }
            
            $translation->setContainer($container);
        }

        if(!empty($data['langId'])) {
            // obtenemos el grupo
            $lang = $this->languageRepository->findOneBy(['id' => $data['langId']]);
            if(empty($lang)) {
                throw new NotFoundHttpException('El contenedor no es valido!');
            }
            $translation->setLang($lang);
        }

        empty($data['transKey']) ? true : $translation->setTransKey($data['transKey']);
        empty($data['value']) ? true : $translation->setValue($data['value']);

        $updatedtranslation = $this->translationRepository->update($translation);

		return new JsonResponse($translation->toJson(), Response::HTTP_OK);
    }

    /**
     * @Route("translation/{id}", name="delete_translation", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $translation = $this->translationRepository->findOneBy(['id' => $id]);

        if (empty($translation)) {
            throw new NotFoundHttpException('No se encuentra la traduccion!');
        }

        $this->translationRepository->remove($translation);

        return new JsonResponse(true, Response::HTTP_OK);
    }
}

?>