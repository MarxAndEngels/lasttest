<?php

namespace App\GraphQL\Types;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Fields\FormatDate;
use App\GraphQL\Types\Digest\DealerType;
use App\GraphQL\Types\Images\OfferImageType;
use App\GraphQL\Types\TechnicalTypes\BodyTypeType;
use App\GraphQL\Types\TechnicalTypes\ColorType;
use App\GraphQL\Types\TechnicalTypes\ComplectationType;
use App\GraphQL\Types\TechnicalTypes\DriveTypeType;
use App\GraphQL\Types\TechnicalTypes\EngineTypeType;
use App\GraphQL\Types\TechnicalTypes\GearboxType;
use App\GraphQL\Types\TechnicalTypes\GenerationType;
use App\GraphQL\Types\TechnicalTypes\ModificationType;
use App\GraphQL\Types\TechnicalTypes\OwnerType;
use App\GraphQL\Types\TechnicalTypes\RatingType;
use App\GraphQL\Types\TechnicalTypes\WheelType;
use App\Models\Offer;
use App\Services\Offer\GenerateOfferPriceOldService;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

final class OfferType extends GraphQLType
{
  protected string $name = 'Offer';
  protected string $model = Offer::class;

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int()))
        ->description('Идентификатор'),

      Field::make(AttributeName::EXTERNAL_ID)->type(Type::int())->description("Внешний идентификатор"),

      Field::make(AttributeName::EXTERNAL_UNIQUE_ID)->type(Type::string())->description("Внешний уникальный идентификатор"),

      Field::make(AttributeName::CATEGORY_ENUM)
        ->type(Type::string())
        ->resolve(function (Offer $offer): string{
          #$categoryAssociation = \Safe\json_decode($offer->category_association, true);
          return $offer->category_association[$offer->category_enum];
        })
        ->description("Категория"),

      Field::make('name')->type(Type::nonNull(Type::string())),

      Field::make('vin')->type(Type::string())->description("VIN-номер"),

      Field::make('video')->type(Type::string())->description("Ссылка на видео"),

      Field::make('year')->type(Type::int())->description("Год"),

      Field::make('run')->type(Type::int())->description("Пробег, км"),

      Field::make('engine_power')->type(Type::int())->description("Мощность двигателя"),

      Field::make('engine_volume')->type(Type::float())->description("Объем двигателя"),

      Field::make(AttributeName::RATING)
        ->isNotSelectable()
        ->type(GraphQL::type(RatingType::class))
        ->description("Рейтинг"),

      Field::make('images')->type(Type::listOf(GraphQL::type(OfferImageType::class)))->isNotRelation()->description("Изображения"),

      Field::make('equipment_groups')
        ->type(Type::listOf(GraphQL::type(TitleValuesType::class)))
        ->isNotRelation()
        ->description("Сгруппированные дополнительные характеристики"),

      Field::make('mark')->type(GraphQL::type(MarkType::class))->description("Марка"),

      Field::make('folder')->type(GraphQL::type(FolderType::class))->description("Модель"),

      Field::make('generation')->type(GraphQL::type(GenerationType::class))->description("Поколение"),

      Field::make('modification')->type(GraphQL::type(ModificationType::class))->description("Модификация"),

      Field::make('complectation')->type(GraphQL::type(ComplectationType::class))->description("Название комплектации"),

      Field::make('gearbox')->type(GraphQL::type(GearboxType::class))->description("КПП"),

      Field::make('driveType')->type(GraphQL::type(DriveTypeType::class))->description("Привод"),

      Field::make('engineType')->type(GraphQL::type(EngineTypeType::class))->description("Двигатель"),

      Field::make('bodyType')->type(GraphQL::type(BodyTypeType::class))->description("Кузов"),

      Field::make('color')->type(GraphQL::type(ColorType::class))->description("Цвет"),

      Field::make('wheel')->type(GraphQL::type(WheelType::class))->description("Руль"),

      Field::make('owner')->type(GraphQL::type(OwnerType::class))->description("Владельцы"),

      Field::make('dealer')->type(GraphQL::type(DealerType::class))->description("Автосалон"),

      Field::make('price')->type(Type::nonNull(Type::int()))->isNotSelectable()->description("Цена, руб"),

      Field::make('price_old')->type(Type::int())->isNotSelectable()
        ->resolve(function (Offer $offer): int{
         return (new GenerateOfferPriceOldService($offer))->getGeneratedPriceOld();
        })
      ->description("Старая цена, руб"),

      Field::make('is_active')->type(Type::boolean())->isNotSelectable()->description("Активное объявление"),

      Field::make(AttributeName::DESCRIPTION)->type(Type::string())
        ->isNotSelectable()
        ->description("Текстовое описание для сайта"),

      Field::make(AttributeName::IS_STOCK)
        ->type(Type::boolean())
        ->isNotSelectable()
        ->resolve(function (Offer $offer): bool {
          #Если КОММ АВТО, то в наличии
          if(isset($offer->dealer_id) && $offer->dealer_id == 59){
            return false;
          }
          if ($offer->communications_count > 0){
            return false;
          }
          return true;
        })
        ->description("На складе"),

      FormatDate::make('createdAt')->alias('created_at'),

      FormatDate::make('updatedAt')->alias('updated_at'),
    ];
  }
}
