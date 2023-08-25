@component('mail::header', ['url' => $feedback->site->url])
  {{$feedback->site->title}}
@endcomponent
@component('mail::table')
  # Заявка №{{$feedback->id}} на trade-in автомобиля
  ### Данные с формы
  |   |   |
  |---|---|
  |__Ф.И.О__| {{$feedback->client_name ?? 'Не заполнено'}} |
  |__Телефон__| {{$feedback->client_phone ?? 'Не заполнено'}} |
  |__Марка пользователя__| {{$feedback->client_vehicle_mark ?? 'Не заполнено'}} |
  |__Модель пользователя__| {{$feedback->client_vehicle_model ?? 'Не заполнено'}} |
  |__Год пользователя__| {{$feedback->client_vehicle_year ?? 'Не заполнено'}} |
  |__КПП пользователя__| {{$feedback->client_vehicle_gearbox ?? 'Не заполнено'}} |
  |__Двигатель пользователя__| {{$feedback->client_vehicle_engine ?? 'Не заполнено'}} |
  |__Желаемый автомобиль__| {{$feedback->feedbackOffer ? 'ID: '.$feedback->feedbackOffer->external_id.' '.$feedback->feedbackOffer->offer_title.' '.$feedback->feedbackOffer->price .' руб.'  : 'Не указан'}} |
  |__Сайт__| {{$feedback->site->title}} |

@endcomponent
