<div class="articles-section">

  <h2>Οριστικά Άρθρα (Definite Articles / განსაზღვრული არტიკლები)</h2>

  @foreach(['masculine', 'feminine', 'neuter'] as $gender)
    <h3>
      {{ ucfirst($gender) }} (სქესი: {{ 
        $gender == 'masculine' ? 'მამრობითი' : ($gender == 'feminine' ? 'მდედრობითი' : 'საშუალო' /* neuter */) 
      }})
    </h3>

    <table border="1" cellpadding="5" cellspacing="0">
      <thead>
        <tr>
          <th>Πτώση (Case / ბრუნვა)</th>
          <th>Μορφή (Form / ფორმა)</th>
          <th>მიმართვა (Usage / გამოყენება)</th>
        </tr>
      </thead>
      <tbody>
        @php
          $cases = ['nom_sg', 'gen_sg', 'acc_sg', 'nom_pl', 'gen_pl', 'acc_pl'];
          $georgianDescriptions = [
            'nom_sg' => 'სუბიექტის ფორმა მხოლობითში',
            'gen_sg' => 'მფლობელობითი ფორმა მხოლობითში',
            'acc_sg' => 'ობიექტის ფორმა მხოლობითში',
            'nom_pl' => 'სუბიექტის ფორმა მრავლობითში',
            'gen_pl' => 'მფლობელობითი ფორმა მრავლობითში',
            'acc_pl' => 'ობიექტის ფორმა მრავლობითში',
          ];
        @endphp

        @foreach($cases as $case)
          <tr>
            <td>{{ $case }} ({{ $georgianDescriptions[$case] ?? '' }})</td>
            <td>{{ $data['definite'][$gender][$case] ?? '-' }}</td>
            <td>
              @switch($case)
                @case('nom_sg') სუბიექტი მხოლობითში @break
                @case('gen_sg') მფლობელობა მხოლობითში @break
                @case('acc_sg') ობიექტი მხოლობითში @break
                @case('nom_pl') სუბიექტი მრავლობითში @break
                @case('gen_pl') მფლობელობა მრავლობითში @break
                @case('acc_pl') ობიექტი მრავლობითში @break
                @default -
              @endswitch
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endforeach

  <h2>Αόριστα Άρθρα (Indefinite Articles / განუსაზღვრელი არტიკლები)</h2>

  @foreach(['masculine', 'feminine', 'neuter'] as $gender)
    <h3>
      {{ ucfirst($gender) }} (სქესი: {{ 
        $gender == 'masculine' ? 'მამრობითი' : ($gender == 'feminine' ? 'მდედრობითი' : 'საშუალო') 
      }})
    </h3>

    <table border="1" cellpadding="5" cellspacing="0">
      <thead>
        <tr>
          <th>Πτώση (Case / ბრუნვა)</th>
          <th>Μορφή (Form / ფორმა)</th>
          <th>მიმართვა (Usage / გამოყენება)</th>
        </tr>
      </thead>
      <tbody>
        @php
          $casesIndef = ['nom', 'gen', 'acc'];
          $georgianDescriptionsIndef = [
            'nom' => 'სუბიექტის ფორმა',
            'gen' => 'მფლობელობითი ფორმა',
            'acc' => 'ობიექტის ფორმა',
          ];
        @endphp

        @foreach($casesIndef as $case)
          <tr>
            <td>{{ $case }} ({{ $georgianDescriptionsIndef[$case] ?? '' }})</td>
            <td>{{ $data['indefinite'][$gender][$case] ?? '-' }}</td>
            <td>
              @switch($case)
                @case('nom') სუბიექტი @break
                @case('gen') მფლობელობა @break
                @case('acc') ობიექტი @break
                @default -
              @endswitch
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endforeach

</div>
