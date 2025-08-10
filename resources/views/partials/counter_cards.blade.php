<!-- resources/views/partials/counter_cards.blade.php -->
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Bautizos</h5>
                    <p class="card-text display-4">{{ $bautizoCount ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Matrimonios</h5>
                    <p class="card-text display-4">{{ $matrimonioCount ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Confirmaciones</h5>
                    <p class="card-text display-4">{{ $confirmacionCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>