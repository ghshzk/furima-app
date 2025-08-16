@component('mail::message')
# 取引が完了しました

{{ $buyer->name }}さんとの取引が完了しました。

**商品名** {{ $item->name }}

@component('mail::button', ['url' => route('mypage')])
マイページを確認する
@endcomponent

ご利用ありがとうございます。<br>
{{ config('app.name') }}
@endcomponent
