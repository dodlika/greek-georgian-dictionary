<div>
    <h3>ზმნების დროები (Verb Tenses)</h3>
    <p>ეს სექცია წარმოგიდგენთ ბერძნული ზმნის ძირითადი დროების ფორმებს და მათ მნიშვნელობებს ინგლისურად.<br>
    This section introduces the main Greek verb tenses along with their English meanings.</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Χρόνος (Tense)</th>
                <th>Παράδειγμα (Example)</th>
                <th>მნიშვნელობა (Meaning)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $tense => $value)
                <tr>
                    <td>
                        @switch($tense)
                            @case('present') Ενεστώτας (Present) @break
                            @case('imperfect') Παρατατικός (Imperfect) @break
                            @case('aorist') Αόριστος (Aorist) @break
                            @case('perfect') Παρακείμενος (Perfect) @break
                            @case('pluperfect') Υπερσυντέλικος (Pluperfect) @break
                            @case('future') Μέλλοντας (Future) @break
                            @case('future_continuous') Εξακολουθητικός Μέλλοντας (Future Continuous) @break
                            @default {{ ucfirst($tense) }}
                        @endswitch
                    </td>
                    <td>{{ $value[0] }}</td>
                    <td>{{ $value[1] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
