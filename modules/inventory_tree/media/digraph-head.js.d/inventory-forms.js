// TODO: QR code scanner fields
$(() => {
    var $scannerFields = $('input#search_q, input.class-TagField');
});

// Auto-select TagSearchForm scanner fields
$(()=>{
    $('form.class-TagSearchForm input.class-TagField').focus();
});