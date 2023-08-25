@component('mail::header', ['url' => $feedback->site->url])
  {{$feedback->site->title}}
@endcomponent
@component('mail::table')
  # Заявка №{{$feedback->id}} на услугу
  ### Данные с формы
  |   |   |
  |---|---|
  |__Ф.И.О__| {{$feedback->client_name ?? 'Не заполнено'}} |
  |__Телефон__| {{$feedback->client_phone ?? 'Не заполнено'}} |
  |__Комментарий__| {{$feedback->comment ?? 'Не заполнено'}} |
  |__Сайт__| {{$feedback->site->title}} |

@endcomponent
