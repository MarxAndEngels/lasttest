@component('mail::header', ['url' => $feedback->site->url])
  {{$feedback->site->title}}
@endcomponent
@component('mail::table')
  # Заявка №{{$feedback->id}}
  ### Данные с формы
  |   |   |
  |---|---|
  |__Ф.И.О__| {{$feedback->client_name ?? 'Не заполнено'}} |
  |__Телефон__| {{$feedback->client_phone ?? 'Не заполнено'}} |
  |__Комментарий__| {{$feedback->comment ?? 'Не заполнено'}} |
  |__Автомобиль__| {{$feedback->feedback_offer? 'ID: '.$feedback->feedback__offer->external_id.' '.$feedback->feedback__offer->offer_title.' '.$feedback->feedback__offer->price .' руб.'  : 'Не указан'}} |
  |__Сайт__| {{$feedback->site->title}} |
@endcomponent
