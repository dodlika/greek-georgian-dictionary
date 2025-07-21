<div class="prepositions-section">

  <h2>Προθέσεις (Prepositions / წინდებული)</h2>

  <ul>
    @foreach($data['common_prepositions'] ?? [] as $greek => $english)
      <li>
        <strong>{{ $greek }}</strong> — {{ $english }}
        {{-- Optionally add Georgian translations if you have them --}}
        {{-- <em>(ქართულად: ... )</em> --}}
      </li>
    @endforeach
  </ul>

</div>
