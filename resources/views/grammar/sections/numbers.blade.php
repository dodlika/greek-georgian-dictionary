<div class="numbers-section">

  <h2>Αριθμοί (Numbers) / რიცხვები</h2>

  {{-- Cardinal Numbers --}}
  <h3>Αριθμοί Κατηγορίας (Cardinal Numbers) / ძირითადი რიცხვები</h3>
  <ul>
    @foreach($data['cardinal'] ?? [] as $num => $greek)
      @php
        $geoCardinal = match($num) {
          1 => 'ერთი (erti)',
          2 => 'ორი (ori)',
          3 => 'სამი (sami)',
          4 => 'ოთხი (otkhi)',
          5 => 'ხუთი (khuti)',
          6 => 'ექვსი (ekvsi)',
          7 => 'შვიდი (shvidi)',
          8 => 'რვა (rva)',
          9 => 'ცხრა (tskhrа)',
          10 => 'ათი (ati)',
          default => '-',
        };
      @endphp
      <li>
        <strong>{{ $num }}</strong> — {{ $greek }} / <em>{{ $geoCardinal }}</em>
      </li>
    @endforeach
  </ul>

  {{-- Ordinal Numbers --}}
  <h3>Τακτικοί Αριθμοί (Ordinal Numbers) / თანმიმდევრული რიცხვები</h3>
  <ul>
    @foreach($data['ordinal'] ?? [] as $ord => $greek)
      @php
        $geoOrdinal = match($ord) {
          '1st' => 'პირველი (pirveli)',
          '2nd' => 'მეორე (meore)',
          '3rd' => 'მესამე (mesame)',
          '4th' => 'მეოთხე (meotkhe)',
          '5th' => 'მეხუთე (mekhupte)',
          default => '-',
        };
      @endphp
      <li>
        <strong>{{ $ord }}</strong> — {{ $greek }} / <em>{{ $geoOrdinal }}</em>
      </li>
    @endforeach
  </ul>

</div>
