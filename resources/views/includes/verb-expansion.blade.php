<div class="verb-conjugation">
    <h4>Conjugations for <strong>{{ $word->greek_word }}</strong></h4>

    @php
        $tenses = ['present_tense', 'past_tense', 'future_tense'];
        $tenseLabels = ['Present', 'Past', 'Future'];
    @endphp

    @foreach($tenses as $index => $tense)
        @if(isset($word->$tense))
            <h5>{{ $tenseLabels[$index] }} Tense</h5>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border-bottom: 1px solid #ccc; text-align:left;">Person</th>
                        <th style="border-bottom: 1px solid #ccc; text-align:left;">Greek</th>
                        <th style="border-bottom: 1px solid #ccc; text-align:left;">Georgian</th>
                        <th style="border-bottom: 1px solid #ccc; text-align:left;">English</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($word->$tense as $person => $forms)
                    <tr>
                        <td style="padding: 5px 10px;">{{ ucwords(str_replace('_', ' ', $person)) }}</td>
                        <td style="padding: 5px 10px;">{{ $forms['greek'] ?? '' }}</td>
                        <td style="padding: 5px 10px;">{{ $forms['georgian'] ?? '' }}</td>
                        <td style="padding: 5px 10px;">{{ $forms['english'] ?? '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
</div>
