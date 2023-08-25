<?php

namespace App\GraphQL\Mutations;

use App\Constants\Attributes\AttributeName;
use App\Dto\CreateFeedbackDto;
use App\GraphQL\Types\FeedbackType;
use App\Models\Feedback;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Log;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateFeedbackMutation extends Mutation
{
  protected $attributes = [
    'name' => 'feedback'
  ];

  public function type(): Type
  {
    return GraphQL::type(FeedbackType::class);
  }

  public function args(): array
  {
    return [
      AttributeName::EXTERNAL_ID => [
        'name' => AttributeName::EXTERNAL_ID,
        'type' => Type::int(),
      ],
      AttributeName::EXTERNAL_UNIQUE_ID => [
        'name' => AttributeName::EXTERNAL_UNIQUE_ID,
        'type' => Type::string(),
      ],
      AttributeName::SITE_ID => [
        'name' => AttributeName::SITE_ID,
        'type' => Type::nonNull(Type::int()),
      ],
      AttributeName::TYPE => [
        'name' => AttributeName::TYPE,
        'type' => Type::nonNull(Type::string()),
      ],
      AttributeName::CLIENT_NAME => [
        'name' => AttributeName::CLIENT_NAME,
        'type' => Type::string(),
      ],
      AttributeName::CLIENT_SESSION => [
        'name' => AttributeName::CLIENT_SESSION,
        'type' => Type::string(),
       ],
      AttributeName::CLIENT_PHONE => [
        'name' => AttributeName::CLIENT_PHONE,
        'type' => Type::nonNull(Type::string()),
      ],
      AttributeName::CLIENT_AGE => [
        'name' => AttributeName::CLIENT_AGE,
        'type' => Type::string(),
      ],
      AttributeName::CLIENT_REGION => [
        'name' => AttributeName::CLIENT_REGION,
        'type' => Type::string(),
      ],
      AttributeName::CLIENT_VEHICLE_MARK => [
        'name' => AttributeName::CLIENT_VEHICLE_MARK,
        'type' => Type::string(),
      ],
      AttributeName::CLIENT_VEHICLE_MODEL => [
        'name' => AttributeName::CLIENT_VEHICLE_MODEL,
        'type' => Type::string(),
      ],
      AttributeName::CLIENT_VEHICLE_RUN => [
        'name' => AttributeName::CLIENT_VEHICLE_RUN,
        'type' => Type::string(),
      ],
      AttributeName::CLIENT_VEHICLE_YEAR => [
        'name' => AttributeName::CLIENT_VEHICLE_YEAR,
        'type' => Type::string(),
      ],
      AttributeName::CLIENT_VEHICLE_BODY_TYPE => [
        'name' => AttributeName::CLIENT_VEHICLE_BODY_TYPE,
        'type' => Type::string(),
      ],
      AttributeName::CLIENT_VEHICLE_PRICE => [
        'name' => AttributeName::CLIENT_VEHICLE_PRICE,
        'type' => Type::string(),
      ],
      AttributeName::CLIENT_VEHICLE_OWNERS => [
        'name' => AttributeName::CLIENT_VEHICLE_OWNERS,
        'type' => Type::string(),
      ],
      AttributeName::CLIENT_VEHICLE_GEARBOX => [
        'name' => AttributeName::CLIENT_VEHICLE_GEARBOX,
        'type' => Type::string(),
      ],
      AttributeName::CLIENT_VEHICLE_ENGINE => [
        'name' => AttributeName::CLIENT_VEHICLE_ENGINE,
        'type' => Type::string(),
      ],
      AttributeName::CREDIT_INITIAL_FEE => [
        'name' => AttributeName::CREDIT_INITIAL_FEE,
        'type' => Type::string(),
      ],
      AttributeName::CREDIT_PERIOD => [
        'name' => AttributeName::CREDIT_PERIOD,
        'type' => Type::string(),
      ],
      AttributeName::UTM_SOURCE => [
        'name' => AttributeName::UTM_SOURCE,
        'type' => Type::string(),
      ],
      AttributeName::UTM_MEDIUM => [
        'name' => AttributeName::UTM_MEDIUM,
        'type' => Type::string(),
      ],
      AttributeName::UTM_CAMPAIGN => [
        'name' => AttributeName::UTM_CAMPAIGN,
        'type' => Type::string(),
      ],
      AttributeName::UTM_TERM => [
        'name' => AttributeName::UTM_TERM,
        'type' => Type::string(),
      ],
      AttributeName::UTM_CONTENT => [
        'name' => AttributeName::UTM_CONTENT,
        'type' => Type::string(),
      ],

      AttributeName::OFFER_TITLE => [
        'name' => AttributeName::OFFER_TITLE,
        'type' => Type::string(),
      ],
      AttributeName::OFFER_PRICE => [
        'name' => AttributeName::OFFER_PRICE,
        'type' => Type::string(),
      ],
      AttributeName::COMMENT => [
        'name' => AttributeName::COMMENT,
        'type' => Type::string(),
      ],
    ];

  }

  protected function rules(array $args = []): array
  {
    return [
      AttributeName::SITE_ID => ['required', 'integer'],
      AttributeName::TYPE => ['required', 'string'],
      AttributeName::CLIENT_PHONE => ['required','string'],
    ];
  }
  public function validationErrorMessages(array $args = []): array
  {
    return [
      'site_id.required' => 'Не указан ID сайта',
      'site_id.integer' => 'Your type must be a valid integer',
      'type.string' => 'Your type must be a valid string',
      'type.required' => 'Не указан тип заяки',
      'client_phone.required' => 'Пожалуйста введите телефон',
      'client_phone.string' => 'Your client_phone must be a valid string',
    ];
  }

  public function resolve($root, array $args)
  {

    $request = request();
//    Log::info($request->headers->get('origin'));
    $args[AttributeName::CLIENT_IP] = $request->ip();
    $args[AttributeName::CLIENT_SESSION] ?? $args[AttributeName::CLIENT_SESSION] = session()->getId();
    $args[AttributeName::CLIENT_USER_AGENT] = $request->userAgent();

    $feedbackDto = new CreateFeedbackDto($args);

    $feedbackArr = $feedbackDto->getFeedback();
    $offerArr = $feedbackDto->getOffer();

    $feedbackNew = Feedback::create(
      $feedbackArr
    );
    $feedbackNew->save();
    if($offerArr) {
      $feedbackNew->feedbackOffer()->create(
        $offerArr
      );
    }
    return $feedbackNew;
  }


}
