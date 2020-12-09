Запрос сверки от: {{ $user->phone }}, {{ $user->first_name }} {{ $user->last_name }}<br>
@if ($inn)
    ИНН: {{ $inn }}<br>
@endif
<br>
Прислать сверку за период: {{ $date_from }} - {{ $date_to }}<br>
<br>
Ответить на адрес: {{ $email }}.