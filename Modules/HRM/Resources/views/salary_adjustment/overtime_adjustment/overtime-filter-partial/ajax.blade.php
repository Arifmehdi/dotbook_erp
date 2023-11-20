$.ajax({
url: "{{ route('hrm.arrivals.index') }}"
, type: 'get'
, dataType: 'json'
, success: function(data) {
$.each(data.data, function(key, val) {
$('#employee_id').append('<option value="' + val.id + '">' + val.name +
    '</option>');
});
}
});

//Submit filter form by select input changing
$(document).on('change', '.submitable', function() {

table.ajax.reload();

});

$('.submitable_input').on('hide.daterangepicker', function(ev, picker) {
// $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
table.ajax.reload();
});

$('.submitable_input').on('apply.daterangepicker', function(ev, picker) {
$(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
table.ajax.reload();
});

//date range picker
$(function() {
$('.reportrange').daterangepicker({
autoUpdateInput: false
, applyClass: 'btn-primary'
, cancelClass: 'btn-secondary'
, ranges: {
'Today': [moment(), moment()]
, 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')]
, 'Last 7 Days': [moment().subtract(6, 'days'), moment()]
, 'Last 30 Days': [moment().subtract(29, 'days'), moment()]
, 'This Month': [moment().startOf('month'), moment().endOf('month')]
, 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
, 'This Year': [moment().startOf('year'), moment().endOf('year')]
, 'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year').subtract(1, 'year')]
, }

});
})
