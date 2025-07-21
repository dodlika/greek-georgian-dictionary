<h3>Greek Alphabet Letters / ბერძნული ანბანი</h3>
<ul>
    @foreach($data['letters'] as $letter)
        <li>
            <strong>{{ $letter['upper'] }}</strong> / <em>{{ $letter['lower'] }}</em> — 
            Name (Όνομα / სახელი): {{ $letter['name'] }}, 
            Sound (Ήχος / ხმა): <code>{{ $letter['sound'] }}</code>
        </li>
    @endforeach
</ul>

<h3>Accents / ტონები</h3>
<ul>
    @foreach($data['accents'] as $accentName => $accentDetails)
        <li>{{ ucfirst($accentName) }} ({{ __('accents.' . $accentName) }}):
            <ul>
                @foreach($accentDetails as $detail)
                    <li>{{ $detail }}</li>
                @endforeach
            </ul>
        </li>
    @endforeach
</ul>
