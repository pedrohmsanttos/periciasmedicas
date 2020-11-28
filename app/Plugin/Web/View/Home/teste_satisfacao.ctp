<button id="btn-satisfacaoModal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#satisfacaoModal">
    Satisfação
</button>

<?php echo $this->element('satisfacao'); ?>

<script>

    $('#btn-satisfacaoModal').on('click', function (e) {
        $("#rateYo").rateYo({
            rating: 2,
            fullStar: true
        });
    });
</script>
