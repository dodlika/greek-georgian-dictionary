<div>
    <h3>ზმნების დროები (Verb Tenses)</h3>
    <p>ეს სექცია წარმოგიდგენთ ბერძნული ზმნის ძირითადი დროების ფორმებს და მათ მნიშვნელობებს ინგლისურად.<br>
    This section introduces the main Greek verb tenses along with their English meanings.</p>

    <!-- Mobile-First Card Layout for Small Screens -->
    <div class="d-block d-lg-none">
        @foreach($data as $tense => $value)
            <div class="card mb-3 border-start border-4 border-primary">
                <div class="card-body p-3">
                    <h6 class="card-title text-primary mb-2">
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
                    </h6>
                    <div class="mb-2">
                        <small class="text-muted d-block">Παράδειγμα (Example):</small>
                        <span class="greek-text">{{ $value[0] }}</span>
                    </div>
                    <div>
                        <small class="text-muted d-block">მნიშვნელობა (Meaning):</small>
                        <span class="georgian-text">{{ $value[1] }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Table Layout for Larger Screens -->
    <div class="d-none d-lg-block">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
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
                            <td class="greek-text">{{ $value[0] }}</td>
                            <td class="georgian-text">{{ $value[1] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
