<div class="expressions-section">

  <h2>Εκφράσεις (Expressions) / ფრაზები</h2>

  {{-- Χαιρετισμοί (Greetings) --}}
  <h3>Χαιρετισμοί (Greetings) / მისალმებები</h3>
  <ul>
    @foreach($data['greetings'] ?? [] as $greek => $english)
      <li><strong>{{ $greek }}</strong> — {{ $english }} / 
        @switch($greek)
          @case('Γεια σου') გამარჯობა (არაკ. / შენთვის) @break
          @case('Γεια σας') გამარჯობა (ზრდ. / ოფიციალური) @break
          @case('Καλημέρα') დილა მშვიდობისა @break
          @case('Καλησπέρα') საღამო მშვიდობისა @break
          @case('Καληνύχτα') ღამე მშვიდობისა @break
          @default -
        @endswitch
      </li>
    @endforeach
  </ul>

  {{-- Ευγενικές Φράσεις (Polite Phrases) --}}
  <h3>Ευγενικές Φράσεις (Polite Phrases) / თავაზიანი ფრაზები</h3>
  <ul>
    @foreach($data['polite_phrases'] ?? [] as $greek => $english)
      <li><strong>{{ $greek }}</strong> — {{ $english }} / 
        @switch($greek)
          @case('Παρακαλώ') გთხოვ / არაფრის და @break
          @case('Ευχαριστώ') გმადლობთ @break
          @case('Συγγνώμη') ბოდიში / გამარჯობა (უკან მოვიხსენ) @break
          @case('Με συγχωρείτε') ბოდიში (ოფიციალური) @break
          @default -
        @endswitch
      </li>
    @endforeach
  </ul>

  {{-- Χρήσιμες Εκφράσεις (Useful Expressions) --}}
  <h3>Χρήσιμες Εκφράσεις (Useful Expressions) / სასარგებლო ფრაზები</h3>
  <ul>
    @foreach($data['useful_expressions'] ?? [] as $greek => $english)
      <li><strong>{{ $greek }}</strong> — {{ $english }} / 
        @switch($greek)
          @case('Πώς σε λένε;') რა გქვია? @break
          @case('Μιλάτε αγγλικά;') ინგლისურად საუბრობთ? @break
          @case('Δεν καταλαβαίνω') ვერ ვგავხარ / არ მესმის @break
          @case('Μπορείτε να με βοηθήσετε;') შეგიძლიათ დამეხმაროთ? @break
          @default -
        @endswitch
      </li>
    @endforeach
  </ul>

</div>
