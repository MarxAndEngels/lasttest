<car>
  <mark_id>{{$mark}}</mark_id>
  <folder_id>{{$car->folder->name}}</folder_id>
  <modification_id>{{$car->generation}}</modification_id>
  <body_type>{{$car->bodytype->name}}</body_type>
  <url>{{$domain_url}}{{$car->uri}}</url>
  <wheel>{{$car->wheel->name}}</wheel>
  <availability>В наличии</availability>
  <custom>Растаможен</custom>
  <state>Отличное</state>
  <owners_number>@owners($car->owners)</owners_number>
  <run>{{$car->run}}</run>
  <year>{{$car->year}}</year>
  <registry_year>{{$car->year}}</registry_year>
  <price>{{$car->price}}</price>
  <currency>RUR</currency>
  <vin>{{$car->vin}}</vin>
  <description>{{$car->description}}</description>
  <unique_id>{{$car->id}}</unique_id>
  <images>
    @if($car->images)
      @foreach(json_decode($car->images) as $img)
        <image>{{$img->src}}</image>
      @endforeach
    @endif
  </images>
  <action>show</action>
  <contact_info>
    <contact>
      <name>{{get_setting('dealer_name')}}</name>
      <phone>{{get_setting('phone')}}</phone>
      <time>с 09:00 по 20:00, без выходных</time>
    </contact>
  </contact_info>
  <pts>Оригинал</pts>
  <booking_allowed>true</booking_allowed>
</car>
