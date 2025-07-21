<div class="verbs-section">

  <h2>Ρήματα (Verbs / ზმნები)</h2>

  {{-- Ομάδες Συζυγίας --}}
  <h3>Ομάδες Συζυγίας (Conjugation Groups / თავისებურებები)</h3>
  <ul>
    <li><strong>Ομάδα 1 (Group 1):</strong> {{ $data['conjugation_groups']['group_1'] ?? '-' }}</li>
    <li><strong>Ομάδα 2 (Group 2):</strong> {{ $data['conjugation_groups']['group_2'] ?? '-' }}</li>
  </ul>

  {{-- Παράδειγμα Ενεστώτα --}}
  <h3>Παράδειγμα Ενεστώτα (Present Tense Example / მაგალითი განმარტებითი დრო)</h3>
  <p><strong>Ρήμα (Verb / ზმნა):</strong> {{ $data['example_present']['verb'] ?? '-' }}</p>
  <ul>
    @foreach($data['example_present']['conjugation'] ?? [] as $form)
      <li>{{ $form }}</li>
    @endforeach
  </ul>

  {{-- Ανώμαλα Ρήματα --}}
  <h3>Ανώμαλα Ρήματα (Irregular Verbs / არარეგულარული ზმნები)</h3>
  <ul>
    @foreach($data['irregular_verbs'] ?? [] as $verb => $meaning)
      <li><strong>{{ $verb }}</strong> — {{ $meaning }}</li>
    @endforeach
  </ul>

</div>
