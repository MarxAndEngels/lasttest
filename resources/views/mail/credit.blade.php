@component('mail::header', ['url' => $feedback->site->url])
 {{$feedback->site->title}}
@endcomponent
@component('mail::table')
  # Заявка №{{$feedback->id}} на автокредит
  ### Данные с формы
  |   |   |
  |---|---|
  |__Ф.И.О__| {{$feedback->client_name ?? 'Не заполнено'}} |
  |__Телефон__| {{$feedback->client_phone ?? 'Не заполнено'}} |
  |__Срок кредитования, мес.__| {{$feedback->credit_initial_fee ?? 'Не заполнено'}} |
  |__Первоначальный взнос__| {{$feedback->credit_period ?? 'Не заполнено'}} |
  |__Автомобиль__| {{$feedback->feedbackOffer ? 'ID: '.$feedback->feedbackOffer->external_id.' '.$feedback->feedbackOffer->offer_title.' '.$feedback->feedbackOffer->price .' руб.'  : 'Не указан'}} |
  |__Сайт__| {{$feedback->site->title}} |
@endcomponent
