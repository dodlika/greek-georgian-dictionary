<div class="nouns-section">

  <h2>Ουσιαστικά (Nouns / არსებითი სახელი)</h2>

  {{-- Cases Table --}}
  <h3>Πτώσεις (Cases / ბრუნვები)</h3>
  <table border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr>
        <th>Πτώση (Case)</th>
        <th>Λειτουργία (Function / ფუნქცია)</th>
        <th>Ερώτηση (Question / შეკითხვა)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data['cases'] ?? [] as $caseName => $caseInfo)
        <tr>
          <td>{{ ucfirst($caseName) }}</td>
          <td>{{ $caseInfo['function'] }}</td>
          <td>{{ $caseInfo['question'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{-- Declensions Table --}}
  <h3>Κλίσεις Ουσιαστικών (Noun Declensions / არსებითი სახელების ბრუნვა)</h3>

  @foreach($data['declensions'] ?? [] as $declensionName => $declension)
    <div class="declension-block" style="margin-bottom: 2rem;">

      <h4>{{ ucfirst(str_replace('_', ' ', $declensionName)) }}</h4>
      <p><strong>Παράδειγμα (Example / მაგალითი):</strong> {{ $declension['example'] }}</p>

      <table border="1" cellpadding="5" cellspacing="0" style="margin-bottom:1rem; width: 100%;">
        <thead>
          <tr>
            <th>Πτώση (Case / ბრუნვა)</th>
            <th>Ενικός (Singular / მხოლობითი)</th>
            <th>Πληθυντικός (Plural / მრავლობითი)</th>
          </tr>
        </thead>
        <tbody>
          @php
            $casesOrder = ['nominative', 'genitive', 'accusative', 'vocative'];
          @endphp

          @foreach($casesOrder as $index => $case)
            <tr>
              <td>{{ ucfirst($case) }}</td>
              <td>{{ $declension['singular'][$index] ?? '-' }}</td>
              <td>{{ $declension['plural'][$index] ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

    </div>
  @endforeach

</div>
