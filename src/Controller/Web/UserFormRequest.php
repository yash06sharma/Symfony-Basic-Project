<?php

namespace App\Controller\Web;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class UserFormRequest extends Request
{
    /**
     * Get the data from the request with additional processing if needed.
     *
     * @return array
     */
    public function getFormData(): array
    {
        $formData = $this->request->all();
        return $formData;
    }
}





//     private string $name;
//     private int $age;
//     private int $mobile;
//     private array $course;
//     private string $city;
//     private string $image;

//     public function __construct(protected ValidatorInterface $validator)
//     {
//     }   

//     public function validate(Request $request): Response
//     {
//         if($request !== null){

       
//         $this->populate($request);

//         $constraints = $this->rules();
//         $errors = $this->validator->validate($this);

//         if (count($errors) > 0) {
//             $messages = ['message' => 'validation_failed', 'errors' => []];
//             foreach ($errors as $error) {
//                 $messages['errors'][] = [
//                     'property' => $error->getPropertyPath(),
//                     'value' => $error->getInvalidValue(),
//                     'message' => $error->getMessage(),
//                 ];
//             }
//             // return $messages;
//             return new JsonResponse($messages, Response::HTTP_BAD_REQUEST);
//         }

//     }
//         return null;
// }

//     protected function populate(Request $request): void
//     {
//         if($request !== null){
//             $this->name = $request->request->get('name');
//             $this->age = (int)$request->request->get('age');
//             $this->mobile = (int)$request->request->get('mobile');
//             $this->course = $request->request->get('course', []);
//             $this->city = $request->request->get('city');
//             $this->image = $request->files->get('image');
//         }
//     }

//     protected function rules(): Assert\Collection
//     {
//         return new Assert\Collection([
//             'name' => new Assert\NotBlank(['message' => 'Name is required']),
//             'age' => [
//                 new Assert\NotBlank(['message' => 'Age is required']),
//                 new Assert\Type(['type' => 'integer', 'message' => 'Age must be an integer']),
//             ],
//             'mobile' => [
//                 new Assert\NotBlank(['message' => 'Mobile is required']),
//                 new Assert\Type(['type' => 'integer', 'message' => 'Mobile must be an integer']),
//             ],
//             'course' => new Assert\Required(['message' => 'Course is required']),
//             'city' => new Assert\NotBlank(['message' => 'City is required']),
//             'image' => new Assert\Image(['message' => 'Image is required']),
//         ]);
//     }

