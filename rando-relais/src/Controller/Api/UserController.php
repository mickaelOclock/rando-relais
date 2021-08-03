<?php

namespace App\Controller\Api;

use App\Controller\RegistrationController;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/{id}/profil", name="api_user_details", methods={"GET"})
     */
    public function details(int $id, UserRepository $userRepository): Response
    {
        // We get all the Angel user by is id.
        $user = $userRepository->find($id);

        // We display the data with a array of optional data.
        // We specify the related HTTP response status code.
        return $this->json($user, 200, [], [
            'groups' => 'users'
        ]);
    }

    /**
     * @Route("", name="api_user_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializerInterface, ValidatorInterface $validatorInterface): Response
    {
        // We get the data in JSON.
        $jsonData = $request->getContent();

        // We use the deserialize() method to convert the JSON in objet => Deserialisation.
        $user = $serializerInterface->deserialize($jsonData, User::class, 'json');

        // $status = $user->getStatus();
        // dd($status);

        // We check if the Asserts of the User Entity are respected.
        $errors = $validatorInterface->validate($user);

        // If the number of error is uppder than 0.
        if (count($errors) > 0) {
            // We have at least one error.
            // We display the eventual errors for the user.
            // We specify the related HTTP response status code.
            return $this->json([
                'errors' => (string) $errors
            ], 500);
        } // Else we don't count any error.
        else {
            // We call the getManager() method.
            $entityManager = $this->getDoctrine()->getManager();
            // We persist the data.
            $entityManager->persist($user);
            // We backup the data in the database.
            $entityManager->flush();

            // We display a flash message for the user.
            // We specify the related HTTP response status code.
            return $this->json([
                'message' => 'Le compte de ' . $user->getFirstName() . ' ' . $user->getLastName() . ' a bien été créé.'
            ], 201);
        }
    }

    /**
     * @Route("/{id}", name="api_user_update", methods={"PUT|PATCH"})
     */
    public function update(Request $request, User $user, SerializerInterface $serializerInterface, ValidatorInterface $validatorInterface): Response
    {
        // We get the data in JSON.
        $jsonData = $request->getContent();

        // We use the deserialize() method to convert the JSON in objet => Deserialisation.
        $user = $serializerInterface->deserialize($jsonData, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        // We check if the Asserts of the User Entity are respected.
        $errors = $validatorInterface->validate($user);

        // If the number of error is uppder than 0.
        if (count($errors) > 0) {
            // We have at least one error.
            // We display the eventual errors for the user.
            // We specify the related HTTP response status code.
            return $this->json([
                'errors' => (string) $errors
            ], 400);
        } // Else we don't count any error.
        else {
            // We call the getManager() method.
            // We backup the data in the database.
            $this->getDoctrine()->getManager()->flush();

            // We display a flash message for the user.
            // We specify the related HTTP response status code.
            return $this->json([
                'message' => 'Le compte de ' . $user->getFirstName() . ' ' . $user->getLastName() . ' a bien été mis à jour.'
            ], 201);
        }
    }

    /**
     * @Route("/{id}", name="api_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user, SerializerInterface $serializerInterface): Response
    {
        // We get the data in JSON.
        $jsonData = $request->getContent();

        // We use the deserialize() method to convert the JSON in objet => Deserialisation.
        $user = $serializerInterface->deserialize($jsonData, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
        
        // We set to $desactivateStatus the value of DESACTIVATE_STATUS.
        $desactivateStatus = RegistrationController::DESACTIVATE_STATUS;

        // We get the user's status.
        $status = $user->getStatus();
        
        // If the status of the current user different than 0.
        if ($status != $desactivateStatus) {
            // We set the value of his status to DESACTIVATE_STATUS.
            $user->setStatus($desactivateStatus);

            // We call the getManager() method.
            // We backup the data in the database.
            $this->getDoctrine()->getManager()->flush();
        }
        
        // We display a flash message for the user.
        // We specify the related HTTP response status code.
        return $this->json([
            'message' => 'Le compte de ' .$user->getFirstName(). ' ' .$user->getLastName(). ' sera prochainement supprimé.'
        ], 200);

        // ! START DON'T TOUCH.  Code for delete in database.
        // // We delete the user.
        // $this->entityManager->remove($user);
        // // We backup in database the information specifying that it be deleted.
        // $this->entityManager->flush();

        // // We display a flash message for the user.
        // // We specify the related HTTP response status code.
        // return $this->json([
        //     'message' => 'Le compte de ' .$user->getFirstName(). ' ' .$user->getLastName(). ' a bien été supprimé.'
        // ], 200);
        // ! END.
    }
}
