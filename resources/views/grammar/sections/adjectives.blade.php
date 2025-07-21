<div class="adjectives-section">

  <h2>Επίθετα (Adjectives) / ზედსართავი სახელები</h2>

  {{-- Agreement --}}
  <p>
    <strong>Συμφωνία (Agreement):</strong>
    {{ $data['agreement'] ?? 'Τα επίθετα συμφωνούν με το ουσιαστικό σε γένος, αριθμό και πτώση.' }}<br>
    <strong>შეთანხმება:</strong> ზედსართავი სახელი ეთანხმება არსებით სახელს სქესში, რიცხვში და ბრუნვაში.
  </p>

  {{-- Examples by gender --}}
  <h3>Παραδείγματα (Examples) / მაგალითები</h3>
  <ul>
    <li><strong>Αρσενικό (Masculine):</strong> {{ $data['example']['masculine'] ?? '-' }}</li>
    <li><strong>Θηλυκό (Feminine):</strong> {{ $data['example']['feminine'] ?? '-' }}</li>
    <li><strong>Ουδέτερο (Neuter):</strong> {{ $data['example']['neuter'] ?? '-' }}</li>
  </ul>

  {{-- Comparison --}}
  <h3>Σύγκριση (Comparison) / შედარება</h3>
  <table border="1" cellpadding="5" cellspacing="0" style="width: 50%;">
    <thead>
      <tr>
        <th>Μορφή (Form)</th>
        <th>Παράδειγμα (Example)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Θετική (Positive) / დადებითი</td>
        <td>{{ $data['comparison']['positive'] ?? '-' }}</td>
      </tr>
      <tr>
        <td>Συγκριτική (Comparative) / შედარებითი</td>
        <td>{{ $data['comparison']['comparative'] ?? '-' }}</td>
      </tr>
      <tr>
        <td>Υπερθετική (Superlative) / უმაღლესი</td>
        <td>{{ $data['comparison']['superlative'] ?? '-' }}</td>
      </tr>
    </tbody>
  </table>

</div>
