<!-- Confirmación Section -->
<div class="content">
    <p>Por medio de la presente, se hace constar que en los registros de esta parroquia se encuentra inscrito el Sacramento de la Confirmación administrado a la persona que a continuación se menciona:</p>
</div>

<!-- Datos del Confirmado -->
@if($acta->persona)
    <div class="data-section">
        <h4>Datos del Confirmado</h4>
        <div class="data-row">
            <span class="data-label">Nombre:</span>
            <span class="data-value">{{ $acta->persona->nombre }} {{ $acta->persona->apellido_paterno }} {{ $acta->persona->apellido_materno }}</span>
        </div>
        <div class="data-row">
            <span class="data-label">Nacimiento:</span>
            <span class="data-value">{{ $acta->persona->fecha_nacimiento ? \Carbon\Carbon::parse($acta->persona->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}</span>
        </div>
        <div class="data-row">
            <span class="data-label">Lugar:</span>
            <span class="data-value">{{ $acta->persona->lugar_nacimiento ?? 'N/A' }}</span>
        </div>
        @if($acta->persona->municipios)
            <div class="data-row">
                <span class="data-label">Municipio:</span>
                <span class="data-value">{{ $acta->persona->municipios->nombre }}</span>
            </div>
        @endif
        @if($acta->persona->municipios && $acta->persona->municipios->estado)
            <div class="data-row">
                <span class="data-label">Estado:</span>
                <span class="data-value">{{ $acta->persona->municipios->estado->nombre }}</span>
            </div>
        @endif
        <!-- Padres del Confirmado -->
        @php
            $sexo = $acta->persona ? $acta->persona->sexo : null;
            $esHombre = $sexo == 1 || $sexo == '1' || strtolower($sexo) == 'm';
            $padre = $esHombre ? $acta->padre : $acta->padre1;
            $madre = $esHombre ? $acta->madre : $acta->madre1;
        @endphp
        
        @if($padre)
            <div class="data-row">
                <span class="data-label">Padre:</span>
                <span class="data-value">{{ $padre->nombre }} {{ $padre->apellido_paterno }} {{ $padre->apellido_materno }}</span>
            </div>
        @endif
        @if($madre)
            <div class="data-row">
                <span class="data-label">Madre:</span>
                <span class="data-value">{{ $madre->nombre }} {{ $madre->apellido_paterno }} {{ $madre->apellido_materno }}</span>
            </div>
        @endif
    </div>
@endif

<!-- Padrinos de Confirmación -->
@php
    $sexo = $acta->persona ? $acta->persona->sexo : null;
    $esHombre = $sexo == 1 || $sexo == '1' || strtolower($sexo) == 'm';
    $padrino = $esHombre ? $acta->padrino1 : $acta->padrino;
    $madrina = $esHombre ? $acta->madrina1 : $acta->madrina;
@endphp

@if($padrino || $madrina)
    <div class="data-section">
        <h4>Padrinos de Confirmación</h4>
        <div class="personas-grid">
            @if($padrino)
                <div class="persona-box">
                    <div class="persona-title">Padrino</div>
                    <div style="font-size: 11px;">{{ $padrino->nombre }} {{ $padrino->apellido_paterno }} {{ $padrino->apellido_materno }}</div>
                </div>
            @endif
            @if($madrina)
                <div class="persona-box">
                    <div class="persona-title">Madrina</div>
                    <div style="font-size: 11px;">{{ $madrina->nombre }} {{ $madrina->apellido_paterno }} {{ $madrina->apellido_materno }}</div>
                </div>
            @endif
        </div>
    </div>
@endif

<!-- Información del Sacramento -->
<div class="data-section">
    <h4>Información del Sacramento</h4>
    <div class="data-row">
        <span class="data-label">Fecha:</span>
        <span class="data-value">{{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d \d\e F \d\e Y') : 'N/A' }}</span>
    </div>
    @if($acta->ermita)
        <div class="data-row">
            <span class="data-label">Lugar:</span>
            <span class="data-value">{{ $acta->ermita->nombre }}</span>
        </div>
    @endif
    @if($acta->observaciones)
        <div class="data-row">
            <span class="data-label">Observaciones:</span>
            <span class="data-value">{{ $acta->observaciones }}</span>
        </div>
    @endif
</div>

<!-- Información adicional de Confirmación -->
<div class="data-section">
    <h4>Datos Bautismales</h4>
    <div class="content">
        <p style="text-indent: 10px; font-size: 11px; margin: 5px 0;">Para la administración del Sacramento de la Confirmación, se verificó que el confirmando había recibido previamente el Sacramento del Bautismo, cumpliendo así con los requisitos canónicos establecidos.</p>
    </div>
</div>

<div class="content">
    <p>El presente documento certifica que el Sacramento de la Confirmación fue administrado conforme a las disposiciones del Derecho Canónico y se encuentra debidamente registrado en los libros parroquiales, fortaleciendo así los dones recibidos en el Bautismo.</p>
</div>
