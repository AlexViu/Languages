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
use Google\Cloud\Translate\V2\TranslateClient;

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
            $array_data[$translation->getTransKey()]['containerId'] = $translation->getContainer()->getId();
           } else {
            $array_data[$translation->getTransKey()]['container'] = null;
            $array_data[$translation->getTransKey()]['containerId'] = null;
           }
        }

        foreach($array_data as $key => $values) {
            $array_aux = [];
            $array_aux['transKey'] =  $key;
            $array_aux['container'] =  $values['container'];
            $array_aux['containerId'] =  $values['containerId'];
            $array_aux['translate'] = $values['translates'];

            $data[] = $array_aux;
        }
        
        return new JsonResponse( $data, Response::HTTP_OK);
    }

    /**
     * @Route("translation", name="update_translation", methods={"PUT"})
     */
    public function update(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $containerId = $data['containerId'];
        $translates = $data['translates'];

        // obtenemos el contenedor
        if($containerId != null){
            $container = $this->containerRepository->findOneBy(['id' => $containerId]);
        } else {
            $container = null;
        }
        
        foreach ($translates as $translate) {
            $translation = $this->translationRepository->findOneBy(['id' => $translate['id']]);
            if( $translation) {
                $translation->setContainer($container);
                empty($translate['value']) ? true : $translation->setValue($translate['value']);
                $this->translationRepository->update($translation);
            }
        }
        return new JsonResponse("ok", Response::HTTP_OK);
    }

    /**
     * @Route("deleteTranslation", name="delete_translation", methods={"PUT"})
     */
    public function deleteTranslation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $translates = $data['translate'];
        
        foreach ($translates as $translate) {

            $translation = $this->translationRepository->findOneBy(['id' => $translate['id']]);

            if (empty($translation)) {
                throw new NotFoundHttpException('No se encuentra la traduccion!');
            }

            $this->translationRepository->remove($translation);
        }
        return new JsonResponse(true, Response::HTTP_OK);
    }


    
    /**
     * @Route("autoTranslate", name="get_one_translation", methods={"POST"})
     */
    public function autoTranslate(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $text = $data['text'];
        $source = $data['source'];

        try {
            $translate = new TranslateClient();

            $languages = $this->languageRepository->findAll();
            $data = [];
            foreach ($languages as $language) {
                if($language->getLangKey() == $source) {
                    continue;
                }

                $result = $translate->translate($text, [
                    'target' => $language->getLangKey(),
                    'source' => $source
                ]);
                $array_translate = [];
                $array_translate['source'] = $language->getLangKey();
                $array_translate['text'] = $result['text'];
                $data[] = $array_translate;
            }
        } catch (\Exception $e) {
            throw new NotFoundHttpException('No se encuentra la traduccion!');
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }
}

?>