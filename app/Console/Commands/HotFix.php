<?php

namespace App\Console\Commands;
ini_set('memory_limit', '-1');

use App\Constants\Attributes\AttributeName;
use App\Constants\Enums\FeedbackEnum;
use App\Constants\MediaConstants;
use App\Constants\TableConstants;
use App\Dto\FeedbackDto;
use App\Dto\Feeds\OfferFeedCollectionDto;
use App\Dto\Feeds\OfferFeedDto;
use App\Dto\PlexCrm\OfferExternalCollectionDto;
use App\Dto\PlexCrm\OfferExternalDto;
use App\Dto\PlexCrm\PaginationDto;
use App\Models\Article;
use App\Models\Bank;
use App\Models\Feedback;
use App\Models\FeedbackOffer;
use App\Models\FeedFilter;
use App\Models\Gearbox;
use App\Models\Generation;
use App\Models\Mark;
use App\Models\Modification;
use App\Models\Offer;
use App\Models\Region;
use App\Models\Site;
use App\Models\SiteText;
use App\Models\Station;
use App\Models\ArticleCategory;
use App\Models\Folder;
use App\Nova\Content\StationCategory;
use App\QueryBuilders\FeedbackQueryBuilder;
use App\Services\ApiClient\PlexCrmClient;
use App\Services\FeedService;
use App\Services\Filter\GetExternalIdArrayService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Support\Facades\Validator;

class HotFix extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'hotfix';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';
  protected FeedService $feedService;

  public function hasInputValue(array $name): bool
  {
    return Arr::hasAny($this->query, $name);
  }

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct(FeedService $feedService)
  {
    $this->query = [
      "mark_slug" => "audi",
      "page" => 1,
      "limit" => 50,
    ];
    $this->feedService = $feedService;
    parent::__construct();
  }

  public function downloadImage($url): ?string
  {
    $response = \Http::get($url);
    if ($response->successful()) {
      $path = public_path() . "/articles/".rand(1, 100000);
      is_dir($path) || mkdir($path, 0777, true);

      $path = $path.'/'.basename($url);

      if (file_put_contents($path, $response->body())) {
        return $path;
      }
    }
    return null;

  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function getArticlesFromCarro(int $parentExternalId, int $categoryId):void
  {
    $response = \Http::get('https://carro.ru/api_get_blog_cat/', [
      'token' => 'fkdvdlfkmvdjr854kfk',
      'parent_id' => $parentExternalId
    ]);

    if ($response->successful()) {
      $dataJson = $response->body();

      $posts = json_decode($dataJson, true);

      $postsCollection = collect($posts);

      $count = $postsCollection->count();
      if ($count < 1) {
        $this->info('no articles');
        return;
      }

      $i = 1;
      $arr = collect($posts)->each(function ($item, $key) use (&$i, $count, $categoryId) {

        $this->info('complete' . round(($i / $count) * 100) . '%');

        $article = Article::create([
          AttributeName::ARTICLE_CATEGORY_ID => $categoryId,
          AttributeName::EXTERNAL_ID => $item['id'],
          AttributeName::PAGE_TITLE => $item['pagetitle'],
          AttributeName::LONG_TITLE => $item['longtitle'],
          AttributeName::BODY => $item['content'],
          AttributeName::SHORT_DESCRIPTION => $item['introtext'],
          AttributeName::DESCRIPTION => $item['description'],
          AttributeName::VIEWS => $item['count_views'],
          AttributeName::SLUG => $item['slug'],
          AttributeName::URL => '/'.$item['uri'],
          AttributeName::URL_OVERRIDE => $item['uri_override'],
          AttributeName::IS_ACTIVE => $item['published'],
          AttributeName::CREATED_AT => \Carbon\Carbon::make($item[AttributeName::CREATED_AT])->toDateTimeString(),
          AttributeName::UPDATED_AT => \Carbon\Carbon::make($item[AttributeName::UPDATED_AT])->toDateTimeString(),
        ]);
        $article->save();
        if ($item['img_src']) {
          $image = $this->downloadImage($item['img_src']);
        }
        if ($item['img_preview_src']) {
          $imagePreview = $this->downloadImage($item['img_preview_src']);
        }
        $this->info('image creating...');
        if (isset($image) && $image) {
          $article->addMedia($image)->toMediaCollection(MediaConstants::MEDIA_ARTICLES);
          $this->info('image create!');
        } else {
          $this->info('NO image!');
        }
        $this->info('image preview...');
        if (isset($imagePreview) && $imagePreview) {
          $article->addMedia($imagePreview)->toMediaCollection(MediaConstants::MEDIA_ARTICLE_PREVIEWS);
          $this->info('image preview create!');
        } else {
          $this->info('NO image preview!');
        }
        $this->info('added');
        $i++;

      });
    }
  }
  public function validate(array $offersExternalArray): array
  {
    $rules = [
      'offerType.name' => 'required',
      'offerType.title' => 'required',
      'state.name' => 'required',
      'mark.name' => 'required',
      'mark.title' => 'required',
      'model.name' => 'required',
      'model.title' => 'required',
      'generation.yearBegin' => 'required',
      'bodyType.name' => 'required',
      'bodyType.title' => 'required',
      'category.name' => 'required',
      'category.title' => 'required',
      'section.name' => 'required',
      'section.title' => 'required',
      'engineType.name' => 'required',
      'engineType.title' => 'required',
      'gearbox.name' => 'required',
      'gearbox.title' => 'required',
      'driveType.name' => 'required',
      'driveType.title' => 'required',
      'color.name' => 'required',
      'color.title' => 'required',
      'wheel.name' => 'required',
      'wheel.title' => 'required',
      'owners.name' => 'required',
      'owners.title' => 'required',
      'images' => ['required','array','min:1']
    ];
    return collect($offersExternalArray)->filter(fn($offer) => !Validator::make($offer, $rules)->fails())->all();
  }
  public function handle()
  {


    $feedFilterQuery = FeedFilter::query();
    $feedFilterQuery->where(AttributeName::ID, '=', 12)
      ->with('site')
      ->get()
      ->each(function (FeedFilter $feedFilter) {
        $siteSlug = $feedFilter->site->slug;
        $filterSlug = $feedFilter->name;
        $this->info("start yandex yml for: feeds/yandex/yml/{$siteSlug}/{$filterSlug}.xml");
        try {
          Storage::disk('public')->put("feeds/yandex/yml/{$siteSlug}/{$filterSlug}.xml", $this->feedService->createFeedYandexYmlCatalogFilter($siteSlug, $filterSlug));
        }catch (\Exception $exception)
        {
          $this->error($exception->getMessage());
        }

        $this->info('success');
        #$feedFilter->generate_file_at = \Illuminate\Support\Carbon::now();
        #$feedFilter->save();
      });

    return;

    $test = [[
      'id' => 1,
      'test' => 112121
    ],
    [
      'id' => 2,
      'test' => 43443
    ],
    [
    'id' => 3,
    'test' => 343411
  ]];
    $pp = collect();

    collect($test)->each(function ($item) use ($pp){
      if($item['id'] == 2){
       return;
      }
      $pp->push($item);
    });
    dd($pp);

    $externalIdArray = (new GetExternalIdArrayService("carro-rf1.csv"))->getExternalIdArray();

    dd($externalIdArray);

    $url = 'https://cars-mega.ru/_exp/feedbacks21.json';

    $body =  Http::get($url)->json();


    collect($body)->each(function($feedback){
      $feedbackQuery = Feedback::query();

      $findFeedback = $feedbackQuery->where(AttributeName::EXTERNAL_ID, $feedback[AttributeName::EXTERNAL_ID])->first();
      if ($findFeedback){
        $this->info('Уже найдена заявка, пропуск...');
        return;
      }
      $newFeedbackData = Arr::except($feedback, ['id', 'feedback_offer']);

      $newFeedback = Feedback::create($newFeedbackData);
      $newFeedback->save();

      if (isset($feedback['feedback_offer']) && $feedback['feedback_offer']){
        $feedbackOffer = $feedback['feedback_offer'];
        $offerArr = [];
        $markId = Mark::where(AttributeName::SLUG, $feedbackOffer['mark']['slug'])->first()?->id;

        $folderId = Folder::where(AttributeName::SLUG, $feedbackOffer['folder']['slug'])
                          ->where(AttributeName::MARK_ID, $feedbackOffer['folder']['mark_id'])
                          ->first()?->id;

        $generationId = Generation::where(AttributeName::SLUG, $feedbackOffer['generation']['slug'])
                                    ->where(AttributeName::FOLDER_ID, $feedbackOffer['generation']['folder_id'])
                                    ->first()?->id;

        $modificationId = Modification::where(AttributeName::NAME, $feedbackOffer['modification']['name'])
                                      ->where(AttributeName::GENERATION_ID, $feedbackOffer['modification']['generation_id'])
                                      ->where(AttributeName::BODY_TYPE_ID, $feedbackOffer['modification']['body_type_id'])
                                      ->first()?->id;

        $gearboxId = Gearbox::where(AttributeName::NAME, $feedbackOffer['gearbox']['name'])->first()?->id;

        if($markId && $folderId && $generationId && $modificationId && $gearboxId) {
          $offerArr = [
            AttributeName::OFFER_TITLE => $feedbackOffer[AttributeName::OFFER_TITLE],
            AttributeName::EXTERNAL_ID => $feedbackOffer[AttributeName::EXTERNAL_ID],
            AttributeName::EXTERNAL_UNIQUE_ID => $feedbackOffer[AttributeName::EXTERNAL_UNIQUE_ID],
            AttributeName::DEALER_ID => $feedbackOffer[AttributeName::DEALER_ID],
            AttributeName::YEAR => $feedbackOffer[AttributeName::YEAR],
            AttributeName::ENGINE_POWER => $feedbackOffer[AttributeName::ENGINE_POWER],
            AttributeName::RUN => $feedbackOffer[AttributeName::RUN],
            AttributeName::ENGINE_VOLUME => $feedbackOffer[AttributeName::ENGINE_VOLUME],
            AttributeName::PRICE => $feedbackOffer[AttributeName::PRICE],
            AttributeName::PRICE_OLD => $feedbackOffer[AttributeName::PRICE_OLD],
            AttributeName::MARK_ID => $markId,
            AttributeName::FOLDER_ID => $folderId,
            AttributeName::GENERATION_ID => $generationId,
            AttributeName::MODIFICATION_ID => $modificationId,
            AttributeName::GEARBOX_ID => $gearboxId
          ];

        }else{
          $this->info('Не нашел в базе, ищу в объявлениях');
          $offerQuery = Offer::query();
          $offer = $offerQuery->whereExternalUniqueId($feedbackOffer[AttributeName::EXTERNAL_UNIQUE_ID])->first();
          if($offer) {
            $offerArr = [
              AttributeName::OFFER_TITLE => $offer->name,
              AttributeName::EXTERNAL_ID => $offer->external_id,
              AttributeName::EXTERNAL_UNIQUE_ID => $offer->external_unique_id,
              AttributeName::DEALER_ID => $offer->dealer_id,
              'year' => $offer->year,
              'engine_power' => $offer->engine_power,
              'run' => $offer->run,
              'engine_volume' => $offer->engine_volume,
              'price' => $feedbackOffer['price'],
              'price_old' => $feedbackOffer['price_old'],
              AttributeName::MARK_ID => $offer->mark_id,
              AttributeName::FOLDER_ID => $offer->folder_id,
              AttributeName::GENERATION_ID => $offer->generation_id,
              AttributeName::MODIFICATION_ID => $offer->modification_id,
              AttributeName::GEARBOX_ID => $offer->gearbox_id
            ];
          }else{
            $this->info('Не нашел объявление. Пропуск...');
          }
        }
          if($offerArr){
            $newFeedback->feedbackOffer()->create(
              $offerArr
            );
            $this->info("Прикрепил объявление к заявке {$newFeedback->id}");
          }
      }

      $this->info("Добавлена заявка {$newFeedback->id}");
    });




    return 1;

    $articles = Article::all();

    $articles->each(function (Article $article){

      $article->published_at = $article->created_at;
      $article->save();
      $this->info("update {$article->id}");

    });

    return parent::SUCCESS;

//    $seoPath = public_path('seo');
//    $marks = Mark::with('folders')->get();
//
//    $marks->each(function (Mark $mark) use ($seoPath){
//      $markPath = $seoPath.'/'.$mark->slug.'/';
//      $file = $markPath.$mark->slug.'.html';
//      if (file_exists($file)) {
//        $content = file_get_contents($file);
//        $siteText = new SiteText();
//        $siteText->site_id = 40;
//        $siteText->body = $content;
//        $mark->siteTexts()->save($siteText);
//        $this->info("add {$mark->slug}");
//      }
//      $mark->folders->each(function (\App\Models\Folder $folder) use ($markPath, $seoPath){
//        $file = $markPath.$folder->slug.'.html';
//        if (file_exists($file)) {
//          $content = file_get_contents($file);
//          $siteText = new SiteText();
//          $siteText->site_id = 40;
//          $siteText->body = $content;
//          $folder->siteTexts()->save($siteText);
//          $this->info("add {$folder->slug}");
//        }
//      });
//    });
//    return;
//    $offerQuery = Offer::query();
//    $csvFields = ['ID', 'Домен', 'Автосалон', 'Марка', 'Модель', 'Объем двигателя', 'Мощность двигателя', 'КПП', 'Год', 'Цена', 'Количество заявок', 'Опубликован', 'Тип', 'Ссылка'];
//    $dateFrom = Carbon::create(2022, 9, 1)->startOfDay();
//    $dateTo = Carbon::create(2022, 12, 1)->endOfDay();
//    $site = Site::where('id', '=', 21)->first();
//    $filter[AttributeName::SITE_ID] = [
//      'id' => $site->id,
//      'onlyActive' => false
//    ];
//
//    $sitesIdArray = $site->childrenSites->map(fn($childrenSite) => $childrenSite->id);
//    $sitesIdArray[] = $site->id;
//    $fileName = "export-offers-{$site->title}-from-{$dateFrom->toDateString()}-to-{$dateTo->toDateString()}.csv";
//    $file = \Storage::path("public/tmp_files/{$fileName}");
//    try {
//      $fd = fopen($file, 'w+');
//    }catch (\Exception $exception){
//      return Action::danger('Не удалось создать файл. Обратитесь к программисту');
//    }
//    fputs($fd, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
//    fputcsv($fd, $csvFields, ';');
//    $offerQuery
//      ->selectForOfferReport()
//      ->with('dealer:id,slug,title')
//      ->filter($filter)
//      ->withCount(['feedbackOffers AS count' => fn(Builder $builder) => $builder
//        ->whereHas('feedback', fn(FeedbackQueryBuilder $feedbackQueryBuilder) => $feedbackQueryBuilder
//          ->whereIn($feedbackQueryBuilder->qualifyColumn(AttributeName::SITE_ID), $sitesIdArray)
//          ->when($dateFrom && $dateTo, fn(FeedbackQueryBuilder $feedbackQueryBuilder) =>
//          $feedbackQueryBuilder->whereBetween($feedbackQueryBuilder->qualifyColumn(AttributeName::CREATED_AT), [$dateFrom, $dateTo]))
//        )
//      ])
//      ->with(['feedbackOffers.feedback' => fn($query) => $query
//        ->when($dateFrom && $dateTo, fn(FeedbackQueryBuilder $feedbackQueryBuilder) =>
//        $feedbackQueryBuilder->whereBetween($feedbackQueryBuilder->qualifyColumn(AttributeName::CREATED_AT), [$dateFrom, $dateTo]))
//        ->whereIn($query->qualifyColumn(AttributeName::SITE_ID), $sitesIdArray)
//      ])
//      ->chunk(1000, fn($offers) => $offers->each(fn(Offer $offer) =>
//      fputcsv($fd, (new OfferFeedDto($offer->toArray()))->getOfferArrayForReportCsv($site->url, $site->category_url, $site->generation_url), ';')
//      ));
//
//      $this->info(url("tmp_files/{$fileName}"));
//      return 0;
//    $feedbacks = Feedback::query();
//
//    $feedback = $feedbacks->where('id', '=', 48085)->with(['feedbackOffer.dealer', 'site.dealer'])->first();
//
//    $feedbackDto = new FeedbackDto($feedback->toArray());
//    $feedbackData = $feedbackDto->createArrayForPlexCrm();
//
//    dd($feedbackData);



    $siteQuery = Site::query();;
    $site = $siteQuery->where('id', '=', 40)->first();

    $plexCrmClient = new PlexCrmClient();
    $currentPage = 364;
    $totalPages = null;
    $datetimeFrom = $site->api_date_from?->toRfc3339String();
    $filter = [
      'page' => $currentPage,
      'all' => 1,
      'limit' => 50
    ];
//    if(isset($datetimeFrom) && $datetimeFrom){
//      $filter['datetime_from'] = $datetimeFrom;
//    }
    $this->info('Фильтр:'. json_encode($filter));
    while (!$totalPages || $totalPages >= $currentPage) {
      $filter['page'] = $currentPage;
      $offersExternalArray = $plexCrmClient->getOffers($filter, $site->external_id);
      if (!$offersExternalArray) {
        $this->info('empty offers');
        $currentPage++;
      }
//      $this->info(collect($offersExternalArray['items'])->pluck('dealerId')->implode(', '));
      $offersExternalArrayItems = $this->validate($offersExternalArray['items']);

      $paginationDto = new PaginationDto($offersExternalArray['pagination']);
      $totalPages = $paginationDto->totalPages;
      if ($currentPage == 1) {
        $this->info("всего {$paginationDto->totalItems}");
      }
      $this->info("страница {$paginationDto->currentPage} из {$paginationDto->totalPages}");
      $this->info(collect($offersExternalArrayItems)->pluck('id')->implode(', '));
      $offerExternalCollectionDto = OfferExternalCollectionDto::create($offersExternalArrayItems);
      $offerExternalCollectionDtoCollect = collect($offerExternalCollectionDto);
      $offersArr = $offerExternalCollectionDtoCollect->map(fn(OfferExternalDto $offerExternalDto) => ['id' => $offerExternalDto->id, 'description' => $offerExternalDto->description])->all();
//      Offer::query()->upsert($offersArr, ['external_unique_id']);
      dd($offersArr);
//      dd('ok');
      $currentPage++;

    }
//    $query = Feedback::query();
//    $feedbacks = $query->where($query->qualifyColumn(AttributeName::ID),  '>=', 1900)->where($query->qualifyColumn(AttributeName::ID),  '<=', 11541)->get();
//
//    $feedbacks->each(function ($item) {
//      if($item->feedback_status_id == 1){
//        $item->status_enum = FeedbackEnum::NEW;
//      }
//      if($item->feedback_status_id == 2){
//        $item->status_enum = FeedbackEnum::SUCCESS;
//      }
//      if($item->feedback_type_id == 1){
//        $item->type_enum = FeedbackEnum::CREDIT;
//      }
//      if($item->feedback_type_id == 2){
//        $item->type_enum = FeedbackEnum::CALLBACK;
//      }
//      if($item->feedback_type_id == 3){
//        $item->type_enum = FeedbackEnum::BUYOUT;
//      }
//      if($item->feedback_type_id == 4){
//        $item->type_enum = FeedbackEnum::TRADE_IN;
//      }
//      if($item->feedback_type_id == 5){
//        $item->type_enum = FeedbackEnum::HIRE_PURCHASE;
//      }
//      $item->save();
//
//      $this->info("{$item->id} complete");
//
//    });
//
//
//    return;

    $articleCategory = ArticleCategory::get();
    $articleCategory->each(fn($item) => $this->getArticlesFromCarro($item->external_id, $item->id));

//        AttributeName::EXTERNAL_ID => $item['id'],
//        AttributeName::PAGE_TITLE => $item['pagetitle'],
//        AttributeName::LONG_TITLE => $item['longtitle'],
//        AttributeName::SLUG => $item['slug'],
//        AttributeName::URL => '/'.$item['uri'],
//        AttributeName::URL_OVERRIDE => $item['url_override'],
//        AttributeName::IS_ACTIVE => $item['published'],
//        AttributeName::CREATED_AT => \Carbon\Carbon::make($item[AttributeName::CREATED_AT])->toDateTimeString(),
//        AttributeName::UPDATED_AT => \Carbon\Carbon::make($item[AttributeName::UPDATED_AT])->toDateTimeString(),
//      ])->all();


    return;


    $json = '{
  "detaling_services": [
    {
      "title": "Химчистка",
      "services": [
        {
          "icon": "icon-wash--cross",
          "price": "от 7000 ₽",
          "title": "Химчистка кроссовера",
          "description": "Вернем свежесть вашему кроссоверу. Бережно удалим загрязнения с использованием профессионального оборудования"
        },
        {
          "icon": "icon-wash--all",
          "price": "от 7000 ₽",
          "title": "Химчистка внедорожника",
          "description": "Внедорожники — тот тип автомобилей, которые чаще остальных нуждаются в химчистке. Удалим загрязнения из самых труднодоступных мест."
        },
        {
          "icon": "icon-wash--sedan",
          "price": "от 5000 ₽",
          "title": "Химчистка легкового автомобиля",
          "description": "Выполним полную химчистку автомобиля любой марки по лучшей цене в Москве. Доверьте свой седан профессионалам."
        },
        {
          "icon": "icon-wash--comm",
          "price": "от 5000 ₽",
          "title": "Химчистка коммерческого транспорта",
          "description": "Специалисты нашего техцентра имеют большой опыт чистки коммерческого транспорта, чья специфика требует особого подхода и инструментов."
        },
        {
          "icon": "icon-wash--pass",
          "price": "от 5000 ₽",
          "title": "Химчистка пассажирского транспорта",
          "description": "Ввиду большой проходимости салон пассажирского транспорта нуждается в своевременной химчистке. Клиенты будут довольны!"
        }
      ]
    },
    {
      "title": "Полировка",
      "services": [
        {
          "icon": "icon-polish--sedan",
          "price": "от 3000 ₽",
          "title": "Полировка легкового автомобиля",
          "description": "Бережно удалим царапины и вернем первозданный блеск вашему автомобилю. Отлично подойдет для личного удовлетворения и деловых встреч."
        },
        {
          "icon": "icon-polish--cross",
          "price": "от 5000 ₽",
          "title": "Полировка кроссовера",
          "description": "Ваш кроссовер снова станет сиять после профессиональной полировки. Используем только профессиональное оборудование."
        },
        {
          "icon": "icon-polish--all",
          "price": "от 5000 ₽",
          "title": "Полировка внедорожника",
          "description": "Внедорожники зачастую нуждаются в полировки в силу частых поездок за город. Большой автомобиль должен всегда быть красивым!"
        },
        {
          "icon": "icon-polish--comm",
          "price": "от 8000 ₽",
          "title": "Полировка коммерческого транспорта",
          "description": "Всегда очень важно произвести на клиентов положительное впечатление. Держите свой коммерческий автопарк в порядке и чистоте!"
        }
      ]
    },
    {
      "title": "Ремонт",
      "services": [
        {
          "icon": "icon-repair--body",
          "price": "Цену уточняйте",
          "title": "Кузовные работы",
          "description": "Любые типы кузовных работ качественно и в срок! Ювелирная работа лучших мастеров столицы."
        },
        {
          "icon": "icon-other--engine",
          "price": "Цену уточняйте",
          "title": "Ремонт двигателя",
          "description": "Держать основной силовой агрегат автомобиля в чистоте — отличная привычка. И копот открыть приятно, и друзьям показать не стыдно!"
        },
        {
          "icon": "icon-repair--chassis",
          "price": "от 5000 ₽",
          "title": "Ремонт ходовой",
          "description": "Один из основных узлов автомобиля, качественный ремонт которого под силу только настоящим профессионалам."
        },
        {
          "icon": "icon-repair--gear",
          "price": "Цена договорная",
          "title": "Ремонт КПП",
          "description": "Починим коробку передач любого типа, беремся даже за самые сложные случаи."
        }
      ]
    },
    {
      "title": "Другое",
      "services": [
        {
          "icon": "icon-other--wheel",
          "price": "Цену уточняйте",
          "title": "Покраска руля",
          "description": "Даже самые качественные материалы руля могут износиться со временем. Восстановим ткань и текстуру, сделаем лучше, чем было!"
        },
        {
          "icon": "icon-other--engine",
          "price": "Цену уточняйте",
          "title": "Мойка двигателя",
          "description": "Держать основной силовой агрегат автомобиля в чистоте — отличная привычка. И копот открыть приятно, и друзьям показать не стыдно!"
        },
        {
          "icon": "icon-other--seat",
          "price": "от 5000 ₽",
          "title": "Покраска сидений",
          "description": "Сиденья автомобиля больше остальных элементов салона подвержены износу по понятным причинам. Наши профессионалы восстановят сиденья по лучшей цене!"
        },
        {
          "icon": "icon-other--seat-paint",
          "price": "Цена договорная",
          "title": "Перешив сидений",
          "description": "Когда ткань сидений износилась или получила механчиеские повреждения идеальным вариантом станет полный перешив, для которого у нас есть весь инструментарий."
        },
        {
          "icon": "icon-other--brake",
          "price": "Цена договорная",
          "title": "Покраска суппортов",
          "description": "Суппорты красного, зеленого или любого другого цвета привлекут внимание не только тюнинг-любителей, но и простых людей. Хит-услуга!"
        }
      ]
    }
  ]
}';

    $stations = Bank::get();
    $stations->each(function ($bank) {
      $img = public_path() . "/banks/{$bank->slug}.png";
      if (file_exists($img)) {
        $bank->addMedia($img)->toMediaCollection(MediaConstants::MEDIA_BANKS_CAR);
        $this->info('added');
      } else {
        $this->info('continue');
      }
    });

    return;
    $args = [
      'site' => [
        'id' => 1,
        'isActive' => true
      ]
    ];
    $test = [
      'tete' => 3
    ];
    $args = array_merge($args, $test);
    dd($args);


    return;
    $dependenciesArray = [
      'folder' => ['mark_slug', 'mark_slug_array'],
      'generation' => ['folder_slug', 'folder_slug_array']
    ];
    $dependencies = [
      'folder' => 'mark_slug',
      'generation' => 'folder_slug'
    ];
    $name = 'folder';
    if (Arr::exists($dependenciesArray, $name)) {
      $tt = $this->hasInputValue($dependenciesArray[$name]);
      dd($tt);
      dd($this->hasInputValue($dependencies[$name]));
      #dd( $dependenciesArray[$name]);
    }
    return 0;
    $rules = [
      'driveType.title' => 'required',
      'driveType.name' => 'required',
      'engineType.title' => 'required'
    ];
    $arr = [
      [
        'id' => 1,
        'driveType' => [
          'title' => 'wef',
          'name' => 'wef'
        ],
        'engineType' => [
          'title' => 'wefwef',
          'name' => 'wefwef'
        ]
      ],
      [
        'id' => 2,
        'driveType' => [
          'title' => 'wef',
          'name' => 'wef'
        ],
        'engineType' => [
          'title' => 'ascasc',
          'name' => 'ascas'
        ]
      ]
    ];
    $t = collect($arr)->filter(fn($item) => !Validator::make($item, $rules)->fails());
    dd($t->all());
    $v = Validator::make($arr, [
      'driveType.title' => 'required',
      'driveType.name' => 'required',
      'engineType.title' => 'required'
    ]);
    dd($v->fails());

    return;
    $plexCrmClient = new PlexCrmClient();
    $feedbacks = Feedback::query()->whereNew()->with(['feedbackOffer.dealer', 'feedbackStatus', 'feedbackType', 'site.dealer'])->get();

    $feedbacks->each(function ($feedback) use ($plexCrmClient) {
      $feedbackDto = new FeedbackDto($feedback->toArray());
      $feedbackData = $feedbackDto->createArrayForPlexCrm();
      dd($feedbackData);
    });


    $fb = Feedback::query()->where('id', '=', 23)->with(['feedbackOffer', 'feedbackStatus', 'feedbackType', 'site'])->first();
    $feedbackDto = new FeedbackDto($fb->toArray());
    dd($feedbackDto->createArrayForPlexCrm());

//    $sites = Site::with(['regions'])->where('id', '4')->get();
//    dd($sites->toArray());
    $regionQuery = Region::query();

    $siteId = 4;
//    $t = $regionQuery->orderBy($regionQuery
//    ->join('region_site', function(JoinClause $join) use($siteId, $regionQuery){
//      $join->on($regionQuery->qualifyColumn(AttributeName::ID), '=', AttributeName::REGION_ID)
//        ->select(\DB::raw('order_column as order_column_site'))
//        ->where(AttributeName::SITE_ID, '=', $siteId);
//    }))->get()->toArray();
    $t = $regionQuery->leftJoin('region_site', function (JoinClause $join) use ($siteId, $regionQuery) {
      $join->on($regionQuery->qualifyColumn(AttributeName::ID), '=', AttributeName::REGION_ID)
        ->where(AttributeName::SITE_ID, '=', $siteId);
    })
      ->select([
        $regionQuery->qualifyColumn('*'),
        \DB::raw('coalesce(region_site.order_column, regions.order_column) AS order_column_site'),
      ])
      ->orderBy('order_column_site', 'asc')
      ->orderBy('title', 'asc')
      ->get()->toArray();
    dd($t);
    dd(Region::with(['sites'])->get()->toArray());


    return;
    $feedbacks = Feedback::query()->whereNew()->with(['feedbackOffer', 'feedbackStatus', 'feedbackType', 'site'])->get();

    $feedbacks->each(function ($feedback) {

      $feedbackDto = new FeedbackDto($feedback->toArray());
      dd($feedbackDto->createArrayForPlexCrm());


    });

    dd($feedbacks->get()->toArray());
    $offers = Offer::query()->withPriceForSite(1)->with(['mark', 'folder', 'modification', 'bodyType', 'wheel', 'color', 'owner'])->limit(4)->get()->toArray();
//    dd($offers);
    $offersDto = OfferFeedCollectionDto::getOffersArrayForYandexFeedXml($offers, 'https://test.ru', 'cars');
//    dd($offersDto);
    $result = ArrayToXml::convert($offersDto, 'data', true, 'UTF-8', '1.1', [], true);
    dd($result);


    $offer = Offer::query()->where('external_id', '=', 83294)->first();
    $equipment = $offer->equipment;
    $newEquipment = collect();
    $twe = collect($equipment)->map(function ($item, $key) use ($newEquipment) {
      return
        [
          'key' => $key,
          'value' => $item['value']
        ];
    })->values();
    dd($twe->toArray());

    $equipmentGroup = $offer->equipment_groups;
    $newEquipmentGroup = collect();
    $test = collect($equipmentGroup)->map(function ($item) {
      return
        [
          'title' => $item['title'],
          'values' => collect($item['values'])->map(function ($t, $k) {
            return $t['value'];
          })->values()->all()
        ];
    })->values();

    dd($test->toJson(JSON_UNESCAPED_UNICODE));


  }
}
