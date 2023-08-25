@component('mail::header', ['url' => $feedback->site->url])
  {{$feedback->site->title}}
@endcomponent
@component('mail::table')
  # Заявка №{{$feedback->id}} на обратный звонок
  ### Данные с формы
  |   |   |
  |---|---|
  |__Ф.И.О__| {{$feedback->client_name ?? 'Не заполнено'}} |
  |__Телефон__| {{$feedback->client_phone ?? 'Не заполнено'}} |
  |__Автомобиль__| {{$feedback->feedbackOffer ? 'ID: '.$feedback->feedbackOffer->external_id.' '.$feedback->feedbackOffer->offer_title.' '.$feedback->feedbackOffer->price .' руб.'  : 'Не указан'}} |
  |__Сайт__| {{$feedback->site->title}} |
@endcomponent
