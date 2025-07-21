<div class="pronouns-section">

  <h2>Αντωνυμίες (Pronouns / ნაცვალსახელები)</h2>

  {{-- Προσωπικές Αντωνυμίες --}}
  <h3>Προσωπικές Αντωνυμίες (Personal Pronouns / პირის ნაცვალსახელები)</h3>
  <table border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr>
        <th>Πρόσωπο (Person)</th>
        <th>Ενικός (Singular / მხოლობითი)</th>
        <th>Πληθυντικός (Plural / მრავლობითი)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Πρώτο Πρόσωπο (1st person)</td>
        <td>{{ $data['personal']['first_person'][0] ?? '-' }}</td>
        <td>{{ $data['personal']['first_person'][1] ?? '-' }}</td>
      </tr>
      <tr>
        <td>Δεύτερο Πρόσωπο (2nd person)</td>
        <td>{{ $data['personal']['second_person'][0] ?? '-' }}</td>
        <td>{{ $data['personal']['second_person'][1] ?? '-' }}</td>
      </tr>
      <tr>
        <td>Τρίτο Πρόσωπο (3rd person)</td>
        <td>{{ $data['personal']['third_person'][0] ?? '-' }}</td>
        <td>{{ $data['personal']['third_person'][1] ?? '-' }}</td>
      </tr>
    </tbody>
  </table>

  {{-- Κτητικές Αντωνυμίες --}}
  <h3>Κτητικές Αντωνυμίες (Possessive Pronouns / კუთვნილი ნაცვალსახელები)</h3>
  <ul>
    <li><strong>Δικός μου / μου (My / ჩემი):</strong> {{ $data['possessive']['my'] ?? '-' }}</li>
    <li><strong>Δικός σου / σου (Your / შენი):</strong> {{ $data['possessive']['your'] ?? '-' }}</li>
    <li><strong>Δικός του/της/του (His/Her/Its / მისი):</strong> {{ $data['possessive']['his/her/its'] ?? '-' }}</li>
    <li><strong>Δικός μας / μας (Our / ჩვენი):</strong> {{ $data['possessive']['our'] ?? '-' }}</li>
    <li><strong>Δικός σας / σας (Your - plural / თქვენი):</strong> {{ $data['possessive']['your_pl'] ?? '-' }}</li>
    <li><strong>Δικός τους / τους (Their / მათი):</strong> {{ $data['possessive']['their'] ?? '-' }}</li>
  </ul>

</div>
