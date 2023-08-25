@component('mail::header', ['url' => $feedback->site->url])
  {{$feedback->site->title}}
@endcomponent
@component('mail::table')
  # Заявка №{{$feedback->id}} на подбор автомобиля
  ### Данные с формы
  |   |   |
  |---|---|
  |__Ф.И.О__| {{$feedback->client_name ?? 'Не заполнено'}} |
  |__Телефон__| {{$feedback->client_phone ?? 'Не заполнено'}} |
  |__Марка__| {{$feedback->client_vehicle_mark ?? 'Не заполнено'}} |
  |__Модель__| {{$feedback->client_vehicle_model ?? 'Не заполнено'}} |
  |__Год от__| {{$feedback->client_vehicle_year ?? 'Не заполнено'}} |
  |__КПП__| {{$feedback->client_vehicle_gearbox ?? 'Не заполнено'}} |
  |__КПП__| {{$feedback->client_vehicle_gearbox ?? 'Не заполнено'}} |
  |__Цена до__| {{$feedback->client_vehicle_price ?? 'Не заполнено'}} |
  |__Двигатель__| {{$feedback->client_vehicle_engine ?? 'Не заполнено'}} |
  |__Сайт__| {{$feedback->site->title}} |

@endcomponent
